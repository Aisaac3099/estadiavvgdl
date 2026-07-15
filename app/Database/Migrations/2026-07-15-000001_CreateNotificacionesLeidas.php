<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificacionesLeidas extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('notificaciones_leidas')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'notificacion_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'leida_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['notificacion_id', 'usuario_id'], 'uq_notificacion_usuario');
        $this->forge->addKey('notificacion_id', false, false, 'idx_notificacion_id');
        $this->forge->addKey('usuario_id', false, false, 'idx_usuario_id');
        $this->forge->createTable('notificaciones_leidas', true);
    }

    public function down()
    {
        // Sin reversión destructiva: no se elimina notificaciones_leidas para conservar lecturas por usuario en producción.
    }
}
