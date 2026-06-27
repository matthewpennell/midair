<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAtprotoUriToBlogTable extends Migration
{
    public function up()
    {
        $fields = [
            'atproto_uri' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'link',
            ],
        ];
        $this->forge->addColumn('blog', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('blog', 'atproto_uri');
    }
}
