<?php

namespace ChingShop\Providers;

use ChingShop\Image\Imagick\ImagePreProcessor;
use ChingShop\Image\Imagick\ImageSizeSet;
use ChingShop\Image\Imagick\ImagickAdapter;
use ChingShop\Image\Imagick\ImagickContract;
use ChingShop\Image\Imagick\OptimiseImage;
use ChingShop\Image\Imagick\WaterMark;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Imagick;

class ImageServiceProvider extends ServiceProvider
{
    /** @var string[] */
    private $transformerClasses = [
        WaterMark::class,
        OptimiseImage::class,
        ImageSizeSet::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImagickContract::class, function () {
            return new ImagickAdapter(new Imagick());
        });

        $this->app->bind(ImagePreProcessor::class, function (Application $app) {
            $processor = new ImagePreProcessor(
                $app->make(ImagickContract::class)
            );
            foreach ($this->transformerClasses as $transformerClass) {
                $processor->addTransformer($app->make($transformerClass));
            }

            return $processor;
        });
    }
}
