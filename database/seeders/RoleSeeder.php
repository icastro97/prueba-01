<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nombre_cargo' => 'Administrador'],
            ['nombre_cargo' => 'Gerente'],
            ['nombre_cargo' => 'Supervisor'],
            ['nombre_cargo' => 'Empleado'],
        ];

        DB::table('roles')->insert($roles);
    }
}
