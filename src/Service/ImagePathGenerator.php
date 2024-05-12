<?php

namespace App\Service;

use League\Glide\Urls\UrlBuilderFactory;

class ImagePathGenerator
{
    public function generate(string $path, int $width, int $height)
    {
        $urlBuilder = UrlBuilderFactory::create('/image/');
        return $urlBuilder->getUrl($path, ['w' => $width, 'h' => $height, 'fit' => 'crop']);
    }
}