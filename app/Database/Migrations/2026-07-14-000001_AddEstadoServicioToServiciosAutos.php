<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEstadoServicioToServiciosAutos extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('estado_servicio', 'servicios_autos')) {
            $this->forge->addColumn('servicios_autos', [
                'estado_servicio' => [
                    'type'       => "ENUM('material_comprado','en_proceso','realizado','cancelado')",
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'tipo_registro',
                ],
            ]);
        }

        if (! $this->db->fieldExists('observacion_estado', 'servicios_autos')) {
            $this->forge->addColumn('servicios_autos', [
                'observacion_estado' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'estado_servicio',
                ],
            ]);
        }
    }

    public function down()
    {
        // No se eliminan columnas para evitar una reversión destructiva y conservar información histórica.
    }
}
