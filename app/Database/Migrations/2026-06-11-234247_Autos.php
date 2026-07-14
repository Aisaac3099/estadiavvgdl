<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Autos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'placas'=> [
                'type' => 'VARCHAR',
                'constraint' => 20
            ],
            'marca' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'modelo' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'imagen' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'activo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true); //llave primaria
        $this->forge->createTable('autos');
    }

    public function down()
    {
        $this->forge->dropTable('autos');
    }
}
