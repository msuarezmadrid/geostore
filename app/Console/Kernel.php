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
        \App\Console\Commands\LoadLardaniFromCSV::class,
        \App\Console\Commands\LoadIndustriesFromCsv::class,
        \App\Console\Commands\LoadComunesToClientFromCsv::class,
        \App\Console\Commands\LoadComunesFromCsv::class,
        \App\Console\Commands\LoadBlockDiscountToItem::class,
        \App\Console\Commands\CreateFakeStock::class,
        \App\Console\Commands\LardaniLoadSellers::class,
        \App\Console\Commands\TodosportMigrate::class,
        \App\Console\Commands\AddProductsFromExcel::class,
        \App\Console\Commands\MakeUseCase::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
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
