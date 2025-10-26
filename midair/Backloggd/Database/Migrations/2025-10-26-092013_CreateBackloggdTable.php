<?php

namespace Midair\Backloggd\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBackloggdTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type'       => 'TEXT',
            ],
            'genres' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'guid' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'release_date' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'image' => [
                'type' => 'TEXT',
            ],
            'pubDate' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('pubDate');
        $this->forge->addUniqueKey('guid');
        $this->forge->createTable('backloggd');
    }

    public function down()
    {
        $this->forge->dropTable('backloggd');
    }
}
