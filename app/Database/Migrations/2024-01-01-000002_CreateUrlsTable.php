<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUrlsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'slug_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'destination_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
                'null'       => true,
                'default'    => null,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug_url');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('urls');
    }

    public function down()
    {
        $this->forge->dropTable('urls');
    }
}
