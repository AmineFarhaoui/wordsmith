<?php

namespace App\Library\Services;

use Aws\CloudFront\UrlSigner;
use Carbon\Carbon;

class AwsService
{
    /**
     * Get a signed URL for a CloudFront resource.
     */
    public function cloudFrontSignedUrl(
        string $url,
        Carbon $expiration,
    ): string {
        $urlSigner = new UrlSigner(
            config('services.cloudfront.key_pair_id'),
            base_path(config('services.cloudfront.private_key_path')),
        );

        return $urlSigner->getSignedUrl($url, $expiration->unix());
    }
}
