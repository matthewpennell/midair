<?php

namespace Midair\Flashfiction\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFlashfiction extends Migration
{
    /**
     * Create a table to hold flashfictions pulled from an RSS feed.
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
            'author' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'categories' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'guid' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'pubDate' => [
                'type' => 'DATETIME',
            ],
            'content' => [
                'type' => 'TEXT',
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
        $this->forge->addUniqueKey('link');
        $this->forge->createTable('flashfiction');
    }

    public function down()
    {
        $this->forge->dropTable('flashfiction');
    }
}
