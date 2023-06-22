<?php

namespace App\Library\Translations\Imports;

use App\Library\Translations\RawTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

abstract class Import
{
    /**
     * Indicates if the file has been read.
     */
    protected bool $read = false;

    /**
     * The imported data.
     */
    protected array $data = [];

    /**
     * Create a new import instance.
     */
    public function __construct(protected UploadedFile $file)
    {
        //
    }

    /**
     * Get the imported data.
     */
    public function data(): Collection
    {
        if (! $this->read) {
            $this->parse();

            $this->read = true;
        }

        return collect($this->data);
    }

    /**
     * Parse the file and extract the data.
     */
    protected function parse(): void
    {
        $raw = $this->read();

        foreach ($raw as $key => $value) {
            $this->data[] = $this->map($key, $value);
        }
    }

    /**
     * Read the file and extract the data.
     */
    abstract protected function read(): array;

    /**
     * Map the data to a RawTranslation instance.
     */
    abstract protected function map(mixed $key, mixed $value): RawTranslation;
}
