<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearMovimientosInventario extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'inventario_id' => [
                'type' => 'BIGINT',
                'unsigned' => true
            ],
            'tecnico_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'tipo_movimiento' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'cantidad' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'existencia_anterior' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'existencia_resultante' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'fecha_movimiento' => [
                'type' => 'DATETIME'
            ],
            'observaciones' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true]
        ]);

        //definir la llave primaria e indices del historial
        $this->forge->addKey('id', true);
        $this->forge->addKey('inventario_id');
        $this->forge->addKey('tecnico_id');

        //relacionar los movimientos con inventarios y tecnicos
        $this->forge->addForeignKey(
            'inventario_id',
            'inventario',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'tecnico_id',
            'tecnicos',
            'id',
            'CASCADE',
            'SET NULL'
        );

        $this->forge->createTable('inventario_movimientos', true);

    }

    public function down()
    {
        //
    }
}
