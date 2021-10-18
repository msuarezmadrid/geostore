<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LardaniLoadSellers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lardani:sellers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga los vendedores de LARDANI desde una arreglo hacia el servidor. Solo se debería ejecutar una vez.';

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
        $sellers = [[
            'code' => 0,
            'name' => 'DORIS'
        ], [
            'code' => 1,
            'name' => 'ROSA'
        ], [
            'code' => 2,
            'name' => 'JUAN'
        ], [
            'code' => 3,
            'name' => 'KAREN'
        ], [
            'code' => 4,
            'name' => 'MAIRA'
        ], [
            'code' => 6,
            'name' => 'ALFREDO'
        ], [
            'code' => 7,
            'name' => 'PATRICIA'
        ], [
            'code' => 8,
            'name' => 'CLAUDIA'
        ], [
            'code' => 9,
            'name' => 'MAYERLIN'
        ], [
            'code' => 11,
            'name' => 'ELIZABETH'
        ], [
            'code' => 12,
            'name' => 'JAIME'
        ], [
            'code' => 13,
            'name' => 'MIRTHA'
        ], [
            'code' => 14,
            'name' => 'CARLOS'
        ], [
            'code' => 15,
            'name' => 'ADRIAN'
        ], [
            'code' => 16,
            'name' => 'CACO'
        ], [
            'code' => 19,
            'name' => 'EDUARDO MIRANDA'
        ], [
            'code' => 21,
            'name' => 'PAULINA'
        ], [
            'code' => 22,
            'name' => 'BERNI'
        ]];

        foreach ($sellers as $seller) {
            DB::table('sellers')->insert([
                'name'       => $seller['name'],
                'code'       => $seller['code'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
