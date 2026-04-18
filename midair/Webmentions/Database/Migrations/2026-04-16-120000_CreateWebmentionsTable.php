<?php

namespace Midair\Webmentions\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWebmentionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
            ],
            'target' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
            ],
            'author_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'author_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'author_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
                'null'       => true,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'mention_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'mention',
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
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('target');
        $this->forge->createTable('webmentions');
    }

    public function down()
    {
        $this->forge->dropTable('webmentions');
    }
}
