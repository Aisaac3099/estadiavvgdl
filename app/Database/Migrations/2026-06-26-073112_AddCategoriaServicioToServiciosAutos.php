<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoriaServicioToServiciosAutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('servicios_autos', [
            'categoria_servicio' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'tipo_registro',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('servicios_autos', 'categoria_servicio');
    }
}
