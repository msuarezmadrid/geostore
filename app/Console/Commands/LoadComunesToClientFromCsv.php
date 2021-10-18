<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Client;

class LoadComunesToClientFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:comunesclient';

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

        $clients = fopen(storage_path('csv/cliente-comuna.csv'), "r");
        $first = true;
        while (($row = fgetcsv($clients)) !== FALSE) {
            if (!$first) {
                $client = Client::where('rut', $row[0])->first();
                if ($client != null) {
                    $client->comune_id = $row[1];
                    $client->save();
                }    

            }
            $first = false;            
        }
    }
}
