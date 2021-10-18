<?php

namespace App\Console\Commands;


use App\Client;
use Illuminate\Console\Command;

class LoadIndustriesFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:industries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $clients = fopen(storage_path('csv/CLIENTE.csv'), "r");
        $first = true;
        while (($row = fgetcsv($clients)) !== FALSE) {
            if (!$first) {
                $client = Client::where('rut', $row[0])->first();
                if ($client != null) {
                    $client->industries = $row[1];
                    $client->save();
                }    

            }
            $first = false;            
        }
    }
}
