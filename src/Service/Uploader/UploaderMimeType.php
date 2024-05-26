<?php
namespace App\Service\Uploader;

class UploaderMimeType
{

    public const IMAGE_EXTENSIONS = ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'svg', 'tif', 'tiff', 'webp'];
    public const PDF_EXTENSIONS = ['pdf'];
    public const DOCUMENT_EXTENSIONS = [];


    public function getExtensions(string $type = 'all'): array
    {
        return match ($type) {
            'image' => static::IMAGE_EXTENSIONS,
            'document' => static::DOCUMENT_EXTENSIONS,
            'pdf' => static::PDF_EXTENSIONS,
            default => [...static::DOCUMENT_EXTENSIONS, static::IMAGE_EXTENSIONS, static::PDF_EXTENSIONS],
        };
    }
}
