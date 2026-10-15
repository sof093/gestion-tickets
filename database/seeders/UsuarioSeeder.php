<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insertar o actualizar usuario Administrador
        DB::table('usuarios')->updateOrInsert(
            ['num_nomina' => '001001'],
            [
                'nombre_completo'      => 'Administrador TI',
                'telefono'             => '7220000000',
                'correo'               => 'admin.sistemas@tracomex.com',
                'contrasena_hash'      => Hash::make('admin123'),
                'id_rol'               => 1,
                'id_patio_descripcion' => 1,
                'id_departamento'      => 1,
                'estatus'              => 'activo'
            ]
        );

        // 2. Insertar o actualizar usuario Operativo
        DB::table('usuarios')->updateOrInsert(
            ['num_nomina' => '001452'],
            [
                'nombre_completo'      => 'Usuario Operativo',
                'telefono'             => '7220000001',
                'correo'               => 'operaciones.toluca@tracomex.com',
                'contrasena_hash'      => Hash::make('user123'),
                'id_rol'               => 2,
                'id_patio_descripcion' => 1,
                'id_departamento'      => 1,
                'estatus'              => 'activo'
            ]
        );
    }
}