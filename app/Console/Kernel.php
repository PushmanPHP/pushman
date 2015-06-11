<?php

namespace Pushman\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'Pushman\Console\Commands\Pushman',
        'Pushman\Console\Commands\RefreshTokens',
        'Pushman\Console\Commands\UpdateBans',
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
        $schedule->command('pushman:refresh')->hourly();

        $schedule->command('pushman:bans')->daily();
    }
}
