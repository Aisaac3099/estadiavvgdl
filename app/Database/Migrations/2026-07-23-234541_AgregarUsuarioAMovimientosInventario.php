<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregarUsuarioAMovimientosInventario extends Migration
{
    public function up()
    {
        
        $this->forge->addColumn('inventario_movimientos', [
            'usuario_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true,
                'after' => 'tecnico_id'
            ]
        ]);

        $this->db->query(
            'ALTER TABLE inventario_movimientos
            ADD CONSTRAINT fk_inventario_movimientos_usuario
            FOREIGN KEY (usuario_id)
            REFERENCES usuarios(id)
            ON UPDATE CASCADE
            ON DELETE SET NULL'
        );
    }

    public function down()
    {
        //
    }
}
