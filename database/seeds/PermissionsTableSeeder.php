<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name'          => 'Ver Usuarios',
            'resource'      => 'users',
            'action'        => 'view',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Crear Usuarios',
            'resource'      => 'users',
            'action'        => 'create',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Editar Usuarios',
            'resource'      => 'users',
            'action'        => 'edit',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Eliminar Usuarios',
            'resource'      => 'users',
            'action'        => 'delete',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        
        DB::table('permissions')->insert([
            'name'          => 'Ver Roles',
            'resource'      => 'roles',
            'action'        => 'view',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Crear Roles',
            'resource'      => 'roles',
            'action'        => 'create',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Editar Roles',
            'resource'      => 'roles',
            'action'        => 'edit',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Eliminar Roles',
            'resource'      => 'roles',
            'action'        => 'delete',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name' 			=> 'Ver Productos',
            'resource'		=> 'items',
            'action'		=> 'view',
			'created_by'	=> 1,
			'updated_by'	=> 1,
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name' 			=> 'Crear Productos',
            'resource'		=> 'items',
            'action'	    => 'create',
			'created_by'	=> 1,
			'updated_by'	=> 1,
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Editar Productos',
            'resource'      => 'items',
            'action'        => 'edit',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Eliminar Productos',
            'resource'      => 'items',
            'action'        => 'delete',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name'          => 'Ver Archivos',
            'resource'      => 'files',
            'action'        => 'view',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Subir Archivos',
            'resource'      => 'files',
            'action'        => 'create',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Editar Archivos',
            'resource'      => 'files',
            'action'        => 'edit',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Eliminar Archivos',
            'resource'      => 'files',
            'action'        => 'delete',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name'          => 'Ver Contactos',
            'resource'      => 'contacts',
            'action'        => 'view',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Crear Contactos',
            'resource'      => 'contacts',
            'action'        => 'create',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Editar Contactos',
            'resource'      => 'contacts',
            'action'        => 'edit',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Eliminar Contactos',
            'resource'      => 'contacts',
            'action'        => 'delete',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name'          => 'Ver Bodegas',
            'resource'      => 'warehouses',
            'action'        => 'view',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Crear Bodegas',
            'resource'      => 'warehouses',
            'action'        => 'create',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Editar Bodegas',
            'resource'      => 'warehouses',
            'action'        => 'edit',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('permissions')->insert([
            'name'          => 'Eliminar Bodegas',
            'resource'      => 'warehouses',
            'action'        => 'delete',
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

    }
}
