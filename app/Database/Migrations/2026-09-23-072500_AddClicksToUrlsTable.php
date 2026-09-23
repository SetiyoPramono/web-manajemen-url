<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClicksToUrlsTable extends Migration
{
    public function up()
    {
        $fields = [
            'click_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'destination_url',
            ],
            'last_accessed_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'after'   => 'click_count',
            ],
        ];

        $this->forge->addColumn('urls', $fields);

        // Add index on created_at for performant date range filtering
        $this->db->query('ALTER TABLE `urls` ADD INDEX `idx_urls_created_at` (`created_at`)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `urls` DROP INDEX `idx_urls_created_at`');
        $this->forge->dropColumn('urls', ['click_count', 'last_accessed_at']);
    }
}
