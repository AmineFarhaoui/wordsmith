<?php

namespace App\Library\Translations\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

abstract class Export
{
    /**
     * The unique identifier for this export.
     */
    public readonly string $uuid;

    /**
     * Export constructor.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Translation>  $models
     */
    public function __construct(
        public readonly string $language,
        protected Collection $models,
    ) {
        $this->uuid = Str::uuid();
    }

    /**
     * Save this export to storage.
     */
    public function save(string $disk = null): self
    {
        Storage::disk($disk)->put($this->getRelativeStoragePath(), $this->getContents());

        return $this;
    }

    /**
     * Get the relative path for this export.
     */
    public function getRelativeStoragePath(): string
    {
        return 'exports/translations/'.$this->getFileName();
    }

    /**
     * Get the file name for this export.
     */
    public function getFileName(): string
    {
        return $this->uuid.'.'.$this->getFileExtension();
    }

    /**
     * Get the file name for this export that will be used when downloading.
     */
    public function getDownloadableFileName(): string
    {
        return $this->language.'.'.$this->getFileExtension();
    }

    /**
     * Get the URL for this export to download the file.
     */
    public function getDownloadableUrl(): string
    {
        return URL::temporarySignedRoute(
            'translations.export',
            now()->addMinute(),
            [
                'filename' => $this->getDownloadableFileName(),
                'path' => encrypt($this->getRelativeStoragePath()),
            ],
        );
    }

    /**
     * Get the contents for this export.
     */
    abstract public function getContents(): string;

    /**
     * Get the file extension for this export.
     */
    abstract public function getFileExtension(): string;
}
