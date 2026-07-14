<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipoRegistroToServiciosAutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('servicios_autos',[
            'tipo_registros' =>[
                'type' => 'ENUM',
                'constraint' => ['unico', 'periodico', 'por_asignar'],
                'default'    => 'unico',
                'after'      => 'tipo_servicio',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('servicios_autos', 'tipo_registro');
    }
}
