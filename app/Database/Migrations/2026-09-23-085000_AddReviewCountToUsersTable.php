<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReviewCountToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'jumlah_ulasan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'alamat_maps',
            ],
            'tgl_ulasan' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'after'   => 'jumlah_ulasan',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['jumlah_ulasan', 'tgl_ulasan']);
    }
}
