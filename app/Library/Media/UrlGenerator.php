<?php

namespace App\Library\Media;

use App\Library\Services\AwsService;
use DateTimeInterface;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class UrlGenerator extends DefaultUrlGenerator
{
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        switch ($this->getDiskName()) {
            // Force urls generated for media stored on s3 to be signed.
            case 's3':
                $unsignedUrl = sprintf(
                    '%s/%s',
                    config('filesystems.disks.s3.url'),
                    $this->getPathRelativeToRoot(),
                );

                $url = app(AwsService::class)->cloudFrontSignedUrl(
                    $unsignedUrl,
                    $expiration,
                );

                break;
            default:
                $url = parent::getTemporaryUrl($expiration, $options);

                break;
        }

        return str_replace(' ', '%20', $url);
    }
}
