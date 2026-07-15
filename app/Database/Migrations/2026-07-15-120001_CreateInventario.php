<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventario extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 150],
            'alias' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'marca' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'modelo' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'cantidad' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'bodega' => ['type' => 'INT', 'unsigned' => true],
            'anaquel' => ['type' => 'INT', 'unsigned' => true],
            'nivel' => ['type' => 'INT', 'unsigned' => true],
            'activo' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('inventario', true);
    }

    public function down()
    {
        // No se eliminan las tablas para proteger datos de producción.
    }
}
