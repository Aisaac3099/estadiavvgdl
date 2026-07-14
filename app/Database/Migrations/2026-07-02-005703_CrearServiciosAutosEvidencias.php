<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearServiciosAutosEvidencias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'servicio_auto_id' =>[
                'type' => 'BIGINT',
                'unsigned' => true
            ],
            'archivo' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'created_at' =>[
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' =>[
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('servicio_auto_id');

        $this->forge->addForeignKey(
            'servicio_auto_id',
            'servicios_autos',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->forge->createTable('servicios_autos_evidencias');
    }

    public function down()
    {
        $this->forge->dropTable('servicios_autos_evidencias');
    }
}
