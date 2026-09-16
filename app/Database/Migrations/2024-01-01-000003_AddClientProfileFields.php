<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientProfileFields extends Migration
{
    public function up()
    {
        $fields = [
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'role',
            ],
            'nomor_wa' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => null,
                'after'      => 'nama',
            ],
            'alamat_maps' => [
                'type'       => 'VARCHAR',
                'constraint' => 2048,
                'null'       => true,
                'default'    => null,
                'after'      => 'nomor_wa',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['nama', 'nomor_wa', 'alamat_maps']);
    }
}
