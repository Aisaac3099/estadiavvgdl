<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregarDescripcionAInventario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('inventario', [
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'modelo'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
