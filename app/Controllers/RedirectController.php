<?php

namespace App\Controllers;

use App\Models\UrlModel;

class RedirectController extends BaseController
{
    /**
     * Handle slug-based redirection
     *
     * - If slug found and destination_url is set → redirect to destination
     * - If slug found but destination_url is empty → redirect to login with warning
     * - If slug not found → show 404
     */
    public function index($slug = null)
    {
        if (empty($slug)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $urlModel = new UrlModel();
        $url = $urlModel->findBySlug($slug);

        // Slug not found → 404
        if (! $url) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check user status
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($url['user_id']);

        if ($user && $user['password_hash'] === 'UNCLAIMED') {
            return redirect()->to('/register-card/' . $slug);
        }

        // Slug found but destination_url is empty -> redirect to login with warning
        if (empty($url['destination_url'])) {
            return redirect()->to('/login')->with('warning', 'URL tujuan belum diatur. Silakan login untuk mengatur URL tujuan.');
        }

        // Slug found and destination_url exists -> redirect
        return redirect()->to($url['destination_url']);
    }
}
