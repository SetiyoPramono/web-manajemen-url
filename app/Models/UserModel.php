<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['username', 'password_hash', 'role', 'nama', 'nomor_wa', 'alamat_maps', 'jumlah_ulasan', 'tgl_ulasan'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'username'      => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required',
        'role'          => 'required|in_list[admin,client]',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username sudah digunakan.',
        ],
    ];
}
