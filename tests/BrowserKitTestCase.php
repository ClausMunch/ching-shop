<?php

namespace Testing;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use NotificationChannels\Telegram\TelegramChannel;
use Testing\Unit\MockObject;

/**
 * Class TestCase.
 */
abstract class BrowserKitTestCase extends BaseTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'https://www.ching-shop.dev';

    /** @var TelegramChannel|MockObject */
    protected $telegramChannel;

    /**
     * Creates the application.
     *
     * @throws \PHPUnit_Framework_Exception
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var Application $app */
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->telegramChannel = $this->createMock(TelegramChannel::class);
        $app->bind(
            TelegramChannel::class,
            function () {
                return $this->telegramChannel;
            }
        );

        return $app;
    }
}
