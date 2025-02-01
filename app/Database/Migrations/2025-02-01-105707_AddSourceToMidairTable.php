<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSourceToMidairTable extends Migration
{
    public function up()
    {
        $fields = [
            'source' => [
                'type' => 'TEXT',
                'constraint' => '255',
                'after' => 'url',
            ],
        ];
        $this->forge->addColumn('midair', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('midair', 'source');
    }
}
