<?php

namespace App\Models;

use CodeIgniter\Model;

class UrlModel extends Model
{
    protected $table            = 'urls';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['slug_url', 'destination_url', 'user_id', 'click_count', 'last_accessed_at'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'slug_url' => 'required|alpha_dash|min_length[2]|max_length[100]|is_unique[urls.slug_url,id,{id}]',
        'user_id'  => 'required|integer',
    ];

    protected $validationMessages = [
        'slug_url' => [
            'is_unique'  => 'Slug URL sudah digunakan.',
            'alpha_dash' => 'Slug hanya boleh berisi huruf, angka, dash, dan underscore.',
        ],
    ];

    /**
     * Get all URLs belonging to a specific user
     */
    public function getUrlsByUser(int $userId): array
    {
        return $this->where('user_id', $userId)->findAll();
    }

    /**
     * Find a URL by its slug
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->where('slug_url', $slug)->first();
    }

    /**
     * Atomically increment the click count of a URL and update last_accessed_at
     */
    public function incrementClick(int $id): bool
    {
        return (bool) $this->builder()
            ->where('id', $id)
            ->set('click_count', 'click_count + 1', false)
            ->set('last_accessed_at', date('Y-m-d H:i:s'))
            ->update();
    }
}
