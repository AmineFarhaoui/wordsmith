<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Translation extends Model implements HasMedia
{
    use HasFactory, HasTags, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id', 'key', 'description', 'is_nested',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_nested' => 'boolean',
    ];

    /**
     * The project the translation belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the translation values for the translation.
     */
    public function translationValues(): HasMany
    {
        return $this->hasMany(TranslationValue::class);
    }

    /**
     * Set the translation value for the translation.
     */
    public function setTranslation(
        string $language,
        ?string $value,
        bool $overwrite = false,
        bool $verified = false,
    ): TranslationValue {
        $translationValue = $this->translationValues()
            ->firstOrCreate(
                ['language' => $language],
                [
                    'value' => $value,
                    'verified_at' => $verified ? now() : null,
                ],
            );

        if ($overwrite) {
            $translationValue->update([
                'value' => $value,
                'verified_at' => $verified ? now() : $translationValue->verified_at,
            ]);
        }

        return $translationValue;
    }

    /**
     * {@inheritdoc}
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('screenshots');
    }

    /**
     * {@inheritdoc}
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->crop(Manipulations::CROP_CENTER, 128, 128)
            ->performOnCollections('screenshots');
    }
}
