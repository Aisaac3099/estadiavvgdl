<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventarioFotos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'inventario_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'foto' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('inventario_id');
        $this->forge->addForeignKey('inventario_id', 'inventario', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventario_fotos', true);
    }

    public function down()
    {
        // No se eliminan las tablas para proteger datos de producción.
    }
}
