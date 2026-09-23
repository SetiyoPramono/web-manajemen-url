<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlockedIpsTable extends Migration
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
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
            ],
            'failed_attempts' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'last_username_tried' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
            ],
            'last_attempt_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'is_blocked' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_permanent' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'blocked_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'blocked_until' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('ip_address');
        $this->forge->addKey('is_blocked');
        $this->forge->addKey('blocked_until');
        $this->forge->createTable('blocked_ips', true);
    }

    public function down()
    {
        $this->forge->dropTable('blocked_ips', true);
    }
}
