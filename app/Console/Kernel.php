<?php

namespace ChingShop\Console;

use ChingShop\Console\Commands\BuildSiteMap;
use ChingShop\Console\Commands\MakeUser;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        MakeUser::class,
        BuildSiteMap::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Daily back ups and clean-up.
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');

        // Daily sitemap generation.
        $schedule->command('sitemap:build')->daily()->at('23:00');
    }
}
