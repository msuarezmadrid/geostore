<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Comune;

class LoadComunesFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:comunes';

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
        $comunes = fopen(storage_path('csv/comuna.csv'), "r");
        $first = true;
        while (($row = fgetcsv($comunes)) !== FALSE) {
            if (!$first) {
                $comune = new Comune();
                $comune->id = $this->getDataOrNull($row, 0);
                $comune->comune_detail = $this->getDataOrNull($row, 1);
                $comune->save();
            }
            $first = false;
        }
    }

    public function getDataOrNull($cols, $index){
        if(count($cols) > $index){
            return utf8_encode($cols[$index]);
        }else{
            return null;
        }
    }
}
