<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AutosImagenes extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'auto_id' =>[
                'type' => 'BIGINT',
                'unsigned' => true
            ],
            'imagen' => [
                'type' => 'VARCHAR',
                'constraint' => 255
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
        $this->forge->addKey('auto_id'); //indice


        $this->forge->addForeignKey(
            'auto_id',  //llave foranea
            'autos', //de la tabla 'autos'
            'id', // va a tomar esta id
            'CASCADE', // elimina todo lo relacionado con el id
            'CASCADE' // lo mismo pero en vez de eliminar, actualiza
        );

        $this->forge->createTable('autos_imagenes');
    }

    public function down()
    {
        $this->forge->dropTable('autos_imagenes');
    }
}
