<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInfoToGoodreads extends Migration
{
    public function up()
    {
        $fields = [
            'book_description' => ['type' => 'TEXT', 'after' => 'description'],
            'publication_date' => ['type' => 'VARCHAR', 'constraint' => 100, 'after' => 'book_description'],
            'image' => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'publication_date'],
            'rating' => ['type' => 'INT', 'constraint' => 1, 'after' => 'image'],
        ];
        $this->forge->addColumn('goodreads', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('goodreads', 'book_description');
        $this->forge->dropColumn('goodreads', 'publication_date');
        $this->forge->dropColumn('goodreads', 'image');
        $this->forge->dropColumn('goodreads', 'rating');
    }
}
