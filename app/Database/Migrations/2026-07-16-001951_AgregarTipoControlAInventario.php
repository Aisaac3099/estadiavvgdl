<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregarTipoControlAInventario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('inventario', [
            'tipo_control' =>[
            'type' => 'ENUM',
            'constraint' => ['retornable', 'consumible'],
            'null' => true,
            'default' => null,
            'after' => 'activo'
        ]
        ]);
    }

    public function down()
    {
        //
    }
}
