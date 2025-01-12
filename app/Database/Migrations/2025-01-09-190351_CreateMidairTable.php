<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMidairTable extends Migration
{
    /**
     * Create a table to hold articles pulled from an RSS feed.
     * Field names are based on the RSS 2.0 spec: https://www.rssboard.org/rss-specification#hrelementsOfLtitemgt
     * For now the <category> elements are all combined to form a single <categories> field.
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'date' => [
                'type' => 'DATETIME',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'excerpt' => [
                'type'       => 'TEXT',
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published'],
                'default'    => 'published',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
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
        $this->forge->addKey('date');
        $this->forge->addKey('url');
        $this->forge->createTable('midair');
    }

    public function down()
    {
        $this->forge->dropTable('midair');
    }
}
