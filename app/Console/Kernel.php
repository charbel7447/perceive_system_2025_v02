<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\ClientBalanceSchedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ApexInstaller::class,
        Commands\ClientBalance::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('client:balance')
        ->everyFiveMinutes();
        
        // ->everyMinute();

        // $sch = new ClientBalanceSchedule;
        // $sch->date = Now();
        // $sch->save;
        
        // $schedule->command('client:balance')
        // ->hourly();

        // $schedule->command('backup:run')->daily()->at('19:57');
        // $schedule->command('backup:run')->daily()->at('17:45');
        // $schedule->command('backup:run')->daily()->at('5:45');
        //  $schedule->command('backup:run')->daily()->at('17:48');
        // $schedule->command('backup:run')->daily()->at('5:48');
    }

  
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
