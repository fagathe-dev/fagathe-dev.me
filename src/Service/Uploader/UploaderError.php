<?php

namespace App\Service\Uploader;

class UploaderError
{

    public const UPLOADER_ERROR_TYPE = 'type';
    public const UPLOADER_ERROR_SIZE = 'size';
    public const UPLOADER_ERROR_NO_CONTENT = 'file';
    public const UPLOADER_ERROR_INTERNAL = 'upload';

    private array $errors = [];

    public function __construct()
    {
    }

    public function addError(string $type, string $message = ''): self
    {
        $this->errors = [...$this->errors, [$type => $message]];

        return $this;
    }

    public function hasErrors(): bool
    {
        return count($this->getErrors()) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
