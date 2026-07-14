<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAliasToAutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('autos',[
        'alias'=>[
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('autos', 'alias');
    }
}
