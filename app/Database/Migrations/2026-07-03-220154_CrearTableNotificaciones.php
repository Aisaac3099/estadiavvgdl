<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearTableNotificaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_incrememt' => true
            ],
            'titulo' =>[
                'type' => 'VARCHAR',
                'constraint' => 150
            ],
            'mensaje' => [
                'type' => 'TEXT',
            ],
            'modulo' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'referencia_id' =>[
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'info'
            ],
            'leida' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'update_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('notificaciones');
    }

    public function down()
    {
        $this->forge->dropTable('notificaciones');
    }
}
