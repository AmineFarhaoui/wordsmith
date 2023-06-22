<?php

namespace App\Library\Translations;

use Illuminate\Contracts\Support\Arrayable;
use Laravel\Nova\Makeable;

class RawTranslation implements Arrayable
{
    use Makeable;

    private string $key;

    private ?string $value = null;

    private ?string $description = null;

    private ?string $language;

    private ?array $tags = null;

    private bool $isNested = false;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string|array|null $value): self
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->value = $value;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getIsNested(): bool
    {
        return $this->isNested;
    }

    public function setIsNested(bool $isNested): self
    {
        $this->isNested = $isNested;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'is_nested' => $this->isNested,
            'description' => $this->description,
            'tags' => $this->tags,
        ];
    }
}
