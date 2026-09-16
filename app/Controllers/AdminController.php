<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UrlModel;

class AdminController extends BaseController
{
    /**
     * Admin Dashboard - List all URLs and their associated client users
     */
    public function index()
    {
        $urlModel  = new UrlModel();
        $userModel = new UserModel();

        // Get all URLs with their associated client usernames
        $urls = $urlModel
            ->select('urls.*, users.username as client_username')
            ->join('users', 'users.id = urls.user_id', 'left')
            ->orderBy('urls.created_at', 'DESC')
            ->findAll();

        return view('admin_dashboard', [
            'urls' => $urls,
        ]);
    }

    /**
     * Create a new URL slug and auto-generate a client user
     */
    public function createUrl()
    {
        $rules = [
            'slug_url' => 'required|alpha_dash|min_length[2]|max_length[100]|is_unique[urls.slug_url]',
        ];

        $messages = [
            'slug_url' => [
                'is_unique'  => 'Slug URL sudah digunakan.',
                'alpha_dash' => 'Slug hanya boleh berisi huruf, angka, dash (-), dan underscore (_).',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slugUrl = $this->request->getPost('slug_url');

        // Generate client credentials
        $clientUsername = 'client_' . strtolower($slugUrl);
        $plainPassword  = $this->generateRandomPassword(8);
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Check if username already exists, append number if so
        $userModel = new UserModel();
        $existingUser = $userModel->where('username', $clientUsername)->first();
        if ($existingUser) {
            $clientUsername = $clientUsername . '_' . random_int(100, 999);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Create the client user
        $userModel->skipValidation(true)->insert([
            'username'      => $clientUsername,
            'password_hash' => $hashedPassword,
            'role'          => 'client',
        ]);

        $newUserId = $userModel->getInsertID();

        // Create the URL entry
        $urlModel = new UrlModel();
        $urlModel->skipValidation(true)->insert([
            'slug_url'        => $slugUrl,
            'destination_url' => null,
            'user_id'         => $newUserId,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal membuat URL. Silakan coba lagi.');
        }

        // Flash the generated credentials (shown only once)
        return redirect()->to('/admin')->with('success',
            "URL /{$slugUrl} berhasil dibuat! Kredensial klien — Username: <strong>{$clientUsername}</strong> | Password: <strong>{$plainPassword}</strong> (simpan sekarang, tidak akan ditampilkan lagi)"
        );
    }

    /**
     * Show edit form for a URL and its client user
     */
    public function editUrl($id)
    {
        $urlModel  = new UrlModel();
        $userModel = new UserModel();

        $url = $urlModel->find($id);
        if (! $url) {
            return redirect()->to('/admin')->with('error', 'URL tidak ditemukan.');
        }

        $client = $userModel->find($url['user_id']);

        return view('admin_edit_url', [
            'url'    => $url,
            'client' => $client,
        ]);
    }

    /**
     * Process update for URL and client user data
     */
    public function updateUrl($id)
    {
        $urlModel  = new UrlModel();
        $userModel = new UserModel();

        $url = $urlModel->find($id);
        if (! $url) {
            return redirect()->to('/admin')->with('error', 'URL tidak ditemukan.');
        }

        $client = $userModel->find($url['user_id']);
        if (! $client) {
            return redirect()->to('/admin')->with('error', 'Data klien tidak ditemukan.');
        }

        // Validation rules
        $rules = [
            'username'    => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$client['id']}]",
            'nama'        => 'permit_empty|max_length[255]',
            'nomor_wa'    => 'permit_empty|max_length[20]',
            'alamat_maps' => 'permit_empty|max_length[2048]',
        ];

        $messages = [
            'username' => [
                'is_unique' => 'Username sudah digunakan oleh user lain.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update user data
        $userData = [
            'username'    => $this->request->getPost('username'),
            'nama'        => $this->request->getPost('nama') ?: null,
            'nomor_wa'    => $this->request->getPost('nomor_wa') ?: null,
            'alamat_maps' => $this->request->getPost('alamat_maps') ?: null,
        ];

        // Update password only if provided
        $newPassword = $this->request->getPost('password');
        if (! empty($newPassword)) {
            if (strlen($newPassword) < 4) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 4 karakter.');
            }
            $userData['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $userModel->skipValidation(true)->update($client['id'], $userData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
        }

        $successMsg = 'Data berhasil diperbarui!';
        if (! empty($newPassword)) {
            $successMsg .= " Password baru: <strong>{$newPassword}</strong> (simpan sekarang)";
        }

        return redirect()->to('/admin')->with('success', $successMsg);
    }

    /**
     * View detail of a URL and its client user
     */
    public function viewUrl($id)
    {
        $urlModel  = new UrlModel();
        $userModel = new UserModel();

        $url = $urlModel->find($id);
        if (! $url) {
            return redirect()->to('/admin')->with('error', 'URL tidak ditemukan.');
        }

        $client = $userModel->find($url['user_id']);

        return view('admin_view_url', [
            'url'    => $url,
            'client' => $client,
        ]);
    }

    /**
     * Delete a URL and its associated client user
     */
    public function deleteUrl($id)
    {
        $urlModel = new UrlModel();
        $url = $urlModel->find($id);

        if (! $url) {
            return redirect()->to('/admin')->with('error', 'URL tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Delete the URL first (FK constraint), then the user
        $urlModel->delete($id);

        // Delete associated client user
        $userModel = new UserModel();
        $userModel->delete($url['user_id']);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/admin')->with('error', 'Gagal menghapus URL.');
        }

        return redirect()->to('/admin')->with('success', 'URL berhasil dihapus.');
    }

    /**
     * Generate a random password
     */
    private function generateRandomPassword(int $length = 8): string
    {
        $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }
    /**
     * Show settings page for CS Floating Button
     */
    public function settings()
    {
        $settingModel = new \App\Models\SettingModel();
        
        $settings = [
            'cs_wa_number'  => $settingModel->getValue('cs_wa_number', ''),
            'cs_wa_enabled' => $settingModel->getValue('cs_wa_enabled', '0'),
        ];

        return view('admin_settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Process update for settings
     */
    public function updateSettings()
    {
        $rules = [
            'cs_wa_number' => 'required|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $settingModel = new \App\Models\SettingModel();
        
        $waNumber = $this->request->getPost('cs_wa_number');
        // Clean the WA number (remove + or leading 0, replace with 62)
        $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
        if (substr($waNumber, 0, 1) === '0') {
            $waNumber = '62' . substr($waNumber, 1);
        }

        $settingModel->setValue('cs_wa_number', $waNumber);
        $settingModel->setValue('cs_wa_enabled', $this->request->getPost('cs_wa_enabled') ? '1' : '0');

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
