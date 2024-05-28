<?php

namespace App\Service\Uploader;

use App\Helpers\FileHelperTrait;
use App\Service\Token\TokenGenerator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    use FileHelperTrait;

    public const MIME_TYPE_PDF = ['application/pdf'];
    public const MIME_TYPE_IMAGE = ['image/png', 'image/gif', 'image/jpeg', 'image/bmp', 'image/tiff', 'image/x-icon', 'image/webp', 'image/svg-xml',];

    private UploaderError $uploaderError;
    private ?UploadedFile $uploadedFile = null;
    private ?string $filename = null;
    private array $options = [];
    private ?Filesystem $fs = null;
    private ?string $filesize = null;

    public function __construct(
        private TokenGenerator $tokenGenerator,
        private LoggerInterface $logger,
        private UploaderMimeType $uploaderMimeType,
    ) {
        $this->uploaderError = new UploaderError();
        $this->fs = new Filesystem();
    }

    /**
     * @param array $options
     * 
     * @return array
     */
    private function setUpOptions(array $options = []): array
    {
        /** FileType */
        $options['type'] = array_key_exists('type', $options) ? $options['type'] : 'all';
        /** MaxFileSize */
        $options['maxFileSize'] = (float) (array_key_exists('maxFileSize', $options) ? $options['maxFileSize'] : UPLOAD_MAX_FILE_SIZE);
        /** UploadDir */
        $options['uploadDir'] = array_key_exists('uploadDir', $options) ? $options['uploadDir'] : '';
        /** GenerateRandomFileName */
        $options['renameFile'] = array_key_exists('renameFile', $options) ? $options['renameFile'] : true;

        return $options;
    }

    /**
     * @return self
     */
    public function upload(?UploadedFile $uploadedFile = null, array $options = []): self
    {
        $this->uploadedFile = $uploadedFile;
        $this->options = $this->setUpOptions($options);

        if ($uploadedFile === null) {
            $type = UploaderError::UPLOADER_ERROR_NO_CONTENT;
            $message = 'Aucun fichier reçu';
            $this->uploaderError->addError($type, $message);

            $this->logger->error('{type} ::: {message}', compact('type', 'message'));

            return $this;
        }

        if ($this->checkFileSize()) {
            $type = UploaderError::UPLOADER_ERROR_SIZE;
            $message = 'Fichier trop lourd';
            $this->uploaderError->addError($type, $message);

            $this->logger->error('{type} ::: {message}', compact('type', 'message'));

            return $this;
        }

        $this->getFileSize();

        if ($this->checkFileType() === false) {
            $type = UploaderError::UPLOADER_ERROR_TYPE;
            $message = 'Type de fichier non accepté';
            $fileExtension = $this->uploadedFile->guessClientExtension();
            $fileExtensionsAllowed = join(', ', $this->uploaderMimeType->getExtensions($this->getOption('type')));

            $this->logger->error(
                '{type} ::: {message}, fichier reçu => {fileExtension}, type de fichier attendu => {fileExtensionsAllowed}',
                compact('type', 'message', 'fileExtensionsAllowed', 'fileExtension')
            );
        }

        if ($this->hasErrors()) {
            return $this;
        }

        $this->generateDir();

        try {
            $uploadedFile->move($this->getUploadedFileDir(), $this->getFileName());
            $this->logger->info('Un fichier de {type} de {size} a été uploadé dans le dossier {dir}', [
                'dir' => $this->getUploadedFileDir(),
                'type' => $this->getOption('type'),
                'size' => $this->getFileSize(),
            ]);
        } catch (FileException $e) {
            $this->logger->error('Une erreur est survenue lors de l\'enregistrement du fichier dans le dossier {dir} :::: {message}', [
                'dir' => $this->getUploadedFileDir(),
                'message' => $e->getMessage(),
            ]);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this;
    }

    /**
     * @param string $typeVerif
     * 
     * @return bool
     */
    private function checkFileType(string $typeVerif = 'all'): bool
    {
        return in_array($this->uploadedFile->getMimeType(), $this->getMimeType($typeVerif));
    }

    /**
     * @param string $type
     * 
     * @return array
     */
    private function getMimeType(string $type = 'all'): array
    {
        return match ($type) {
            'image' => static::MIME_TYPE_IMAGE,
            'pdf' => static::MIME_TYPE_PDF,
            default => [...static::MIME_TYPE_IMAGE, ...static::MIME_TYPE_PDF]
        };
    }

    /**
     * @return string
     */
    public function getFileSize(): string
    {
        $this->filesize = $this->convertFileSize($this->uploadedFile->getSize());

        return $this->filesize;
    }

    /**
     * @return bool
     */
    private function checkFileSize(): bool
    {
        return $this->uploadedFile->getSize() > $this->getMaxSize();
    }

    /**
     * @return int
     */
    private function getMaxSize(): int
    {
        return $this->getOption('maxFileSize') * 1024 * 1024;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->uploaderError->getErrors();
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->uploaderError->hasErrors();
    }

    /**
     * @param string $key
     * 
     * @return mixed
     */
    private function getOption(string $key): mixed
    {
        return $this->options[$key] ?? null;
    }

    /**
     * @return string
     */
    public function getUploadedFilePath(): string
    {
        return DEFAULT_UPLOAD_DIR . $this->getOption('uploadDir') . DIRECTORY_SEPARATOR . $this->getFilename();
    }

    /**
     * generateFileName
     *
     * @return string
     */
    public function getFileName(): string
    {
        if ($this->getOption('renameFile')) {
            $this->filename = str_replace('.', '', $this->tokenGenerator->generate(length: 40, unique: true)) . '.' . $this->uploadedFile->guessClientExtension();
        } else {
            $this->filename = $this->uploadedFile->getClientOriginalName();
        }

        return $this->filename;
    }

    /**
     * @return string
     */
    private function getUploadedFileDir(): string
    {
        return $this->getUploadedDir() . $this->getOption('uploadDir');
    }

    /**
     * @return string
     */
    private function getUploadedDir(): string
    {
        return ROOT_DIR . DEFAULT_UPLOAD_DIR;
    }

    /**
     * generateDir
     *
     * @return void
     */
    private function generateDir(): void
    {
        if ($this->fs->exists($this->getUploadedFileDir())) {
            $this->fs->mkdir($this->getUploadedFileDir());
        }
    }
}
