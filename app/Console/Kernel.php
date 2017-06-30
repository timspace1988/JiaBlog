<?php

namespace App\Console;

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
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
        // Run once a minute
        $schedule->command('queue:work')->everyMinute();
        //$schedule->command('queue:work')->cron('* * * * * *');
        //->cron('* * * * * *')Run the task on a custom Cron schedule(here are 6 * signs)
        //as we set Cron to call laravel scheduler every miute, the 'queue:work' will run once a minute

        //if we set a commond to run every 5 mins in scheduler, the Cron will call scheduler every minute,
        //when it found a commond like 'queue:work' were due at that moment, the command will be executed

        /**
         * in crontab file, we add a line: * * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
         * path/to should be replaced with you actual path, here should be /home/vagrant/Code/JiaBlog if on homestead
         * it will run anything that are currently due in scheduler and sending any output to the null device
         * '* * * * *' (here are 5 * signs)stands for field of minute, hour, day of month, month and day of week
         * '25 10 * * *' means 10:25 everyday
         * PS: the crontab file is a file stored on your machine(server) to do scheduled task
         * edit it e.g. add new line(enty) to it, simpley run crontab -e from command line, then select an editor(1~4)
         */

        // // Run every 5 minutes
        // $schedule->command('queue:work')->everyFiveMinutes();
        //
        // // Run once a day
        // $schedule->command('queue:work')->daily();
        //
        // // Run Mondays at 8:15am
        // $schedule->command('queue:work')->weeklyOn(1, '8:15');
    }
}
