<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Licencia;

class LicenciaSeeder extends Seeder
{
    public function run()
    {
        $licencias = [
            ['codigo' => 'LV', 'nombre' => 'Licencia Vacaciones (L. Personal)', 'descripcion' => 'Licencia por período vacacional'],
            ['codigo' => 'LE', 'nombre' => 'Licencia Enfermedad', 'descripcion' => 'Licencia por enfermedad o incapacidad médica'],
            ['codigo' => 'LG', 'nombre' => 'Licencia Gravidez (Embarazo)', 'descripcion' => 'Licencia por estado de gestación'],
            ['codigo' => 'LP', 'nombre' => 'Licencia Particular (Sin GOCE de Haber)', 'descripcion' => 'Licencia personal sin goce de haber'],
            ['codigo' => 'LO', 'nombre' => 'Licencia por Onomástico', 'descripcion' => 'Licencia por cumpleaños'],
            ['codigo' => 'LPT', 'nombre' => 'Licencia por Paternidad', 'descripcion' => 'Licencia por nacimiento de hijo'],
            ['codigo' => 'LMT', 'nombre' => 'Licencia por Maternidad', 'descripcion' => 'Licencia por nacimiento de hijo'],
            ['codigo' => 'LF', 'nombre' => 'Licencias por Fallecimiento', 'descripcion' => 'Licencia por fallecimiento de familiar'],
        ];

        foreach ($licencias as $licencia) {
            Licencia::create($licencia);
        }
    }
}