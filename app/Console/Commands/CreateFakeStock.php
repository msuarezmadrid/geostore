<?php

namespace App\Console\Commands;

use App\Item;
use App\StockItem;
use App\Location;
use Illuminate\Console\Command;

class CreateFakeStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fake stock for each item';

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
        $this->info('Init charge');
        $items = Item::all();

        $locations = Location::where('enterprise_id', 1)->get(); 

        foreach ($items as $item) {
            $amounts = rand(5, 20);
            for($x = 1; $x < $amounts; $x++) {
                foreach($locations as $location) {
                    $stockItem           = new StockItem();
                    $stockItem->item_id     = $item->id;
                    $stockItem->price       = 1;
                    $stockItem->location_id = $location->id;
                    $stockItem->save();
                }
            }
        }
        $this->info('End charge');

    }
}
