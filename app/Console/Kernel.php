<?php

namespace App\Console;

use App\Http\Controllers;
use App\Http\Controllers\Controller;
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
        'App\Console\Commands\BackupDatabase',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('\App\Http\Controllers\AdminItemsController@getItemsUpdatedAPI')->hourly()->between('9:00', '21:00');
        $schedule->call('\App\Http\Controllers\AdminItemsController@getItemsCreatedAPI')->hourly()->between('9:00', '21:00');
        $schedule->call('\App\Http\Controllers\BrandController@getBrandUpdatedAPI')->hourly()->between('9:00', '21:00');
        $schedule->call('\App\Http\Controllers\BrandController@getBrandCreatedAPI')->hourly()->between('9:00', '21:00');
        $schedule->call('\App\Http\Controllers\CategoryController@getCategoryUpdatedAPI')->hourly()->between('9:00', '21:00');
        $schedule->call('\App\Http\Controllers\CategoryController@getCategoryCreatedAPI')->hourly()->between('9:00', '21:00');
        //$schedule->call('\App\Http\Controllers\AdminItemsController@getItemsUpdatedAPI')->everyMinute();
        //$schedule->call('\App\Http\Controllers\AdminItemsController@getItemsCreatedAPI')->everyMinute();
        //$schedule->call('\App\Http\Controllers\AdminItemsController@getItemsUpdatedAPI')->hourly()->between('9:00', '21:00');
        //$schedule->call('\App\Http\Controllers\AdminItemsController@getItemsCreatedAPI')->hourly()->between('9:00', '21:00');
        $schedule->command('mysql:backup')->dailyAt('01:10');
        
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
