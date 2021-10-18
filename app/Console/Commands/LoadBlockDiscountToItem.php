<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Item;

class LoadBlockDiscountToItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:itemblockdiscount';

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

        $items = DB::table('items')->where('name','LIKE', '***%')->get();
        if ($items != null) {
            foreach ($items as $item){
            $item->block_discount = 1;
            $item->save();
            }
        } 
    }
}
