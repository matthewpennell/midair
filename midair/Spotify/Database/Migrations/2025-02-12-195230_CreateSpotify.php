<?php

namespace Midair\Spotify\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpotify extends Migration
{
    /**
     * Create a table to hold spotifys pulled from an RSS feed.
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
            'track' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'artist' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'album' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'cover' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'url' => [
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
        $this->forge->createTable('spotify');
    }

    public function down()
    {
        $this->forge->dropTable('spotify');
    }
}
