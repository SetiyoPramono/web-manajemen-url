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
            ->select('urls.*, users.username as client_username, users.password_hash')
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

        // Generate unclaimed client credentials
        // Use slug_url as the temporary username (guaranteed unique by urls.slug_url constraint)
        $clientUsername = $slugUrl;
        $hashedPassword = 'UNCLAIMED';

        $db = \Config\Database::connect();
        $db->transStart();

        $userModel = new UserModel();

        // Create the unclaimed client user
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
            return redirect()->to('/admin')->with('error', 'Gagal membuat URL baru.');
        }

        return redirect()->to('/admin')->with('success', "Berhasil! URL /{$slugUrl} telah dibuat dan siap diklaim oleh klien.");
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
    /**
     * Export all URLs and Clients to CSV
     */
    public function exportCsv()
    {
        $urlModel = new UrlModel();
        
        $urls = $urlModel->select('urls.slug_url, urls.destination_url, users.username, users.password_hash, users.nama, users.nomor_wa, users.alamat_maps')
                         ->join('users', 'users.id = urls.user_id')
                         ->orderBy('urls.created_at', 'DESC')
                         ->findAll();

        $filename = 'export_klien_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        // Output BOM for Excel UTF-8 compatibility
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        // Write header
        fputcsv($output, ['Slug URL', 'URL Tujuan', 'Username Login', 'Password', 'Nama Bisnis', 'Nomor WhatsApp', 'Alamat Maps (Asli)']);

        // Write data
        foreach ($urls as $url) {
            $password = ($url['password_hash'] === 'UNCLAIMED') ? 'UNCLAIMED' : ''; // Do not export hashed passwords
            fputcsv($output, [
                $url['slug_url'],
                $url['destination_url'],
                $url['username'],
                $password,
                $url['nama'],
                $url['nomor_wa'],
                $url['alamat_maps']
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Import CSV to insert/update URLs and Clients
     */
    public function importCsv()
    {
        $validationRule = [
            'csv_file' => [
                'label' => 'File CSV',
                'rules' => 'uploaded[csv_file]|ext_in[csv_file,csv]',
            ],
        ];

        if (! $this->validate($validationRule)) {
            return redirect()->back()->with('error', $this->validator->getError('csv_file'));
        }

        $file = $this->request->getFile('csv_file');

        if ($file->isValid() && ! $file->hasMoved()) {
            $filepath = $file->getTempName();
            $handle = fopen($filepath, 'r');
            
            // Handle BOM
            $bom = fread($handle, 3);
            if ($bom != b"\xEF\xBB\xBF") {
                rewind($handle);
            }

            $header = fgetcsv($handle, 1000, ',');
            // Check if valid format
            if (! $header || count($header) < 7 || stripos($header[0], 'Slug URL') === false) {
                fclose($handle);
                return redirect()->back()->with('error', 'Format CSV tidak sesuai. Gunakan template hasil Ekspor CSV.');
            }

            $userModel = new UserModel();
            $urlModel = new UrlModel();
            
            $db = \Config\Database::connect();
            $db->transStart();

            $rowIdx = 2;
            $successCount = 0;
            $updateCount = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // $data index: 0=>Slug, 1=>Destination, 2=>Username, 3=>Password, 4=>Nama, 5=>WA, 6=>Alamat Maps
                $slug        = trim($data[0] ?? '');
                $destination = trim($data[1] ?? '');
                $username    = trim($data[2] ?? '');
                $password    = trim($data[3] ?? '');
                $nama        = trim($data[4] ?? '');
                $nomorWa     = trim($data[5] ?? '');
                $alamatMaps  = trim($data[6] ?? '');

                if (empty($slug)) {
                    $rowIdx++;
                    continue;
                }
                
                // If username is empty, fallback to slug
                if (empty($username)) {
                    $username = $slug;
                }

                // Convert password logic
                $passwordHash = 'UNCLAIMED';
                if ($password !== 'UNCLAIMED' && !empty($password)) {
                    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                }

                // If alamatMaps is filled, attempt to auto-convert to destination
                if (!empty($alamatMaps) && empty($destination)) {
                    $parsed = \App\Controllers\ToolsController::parseGoogleMapsUrl($alamatMaps);
                    if ($parsed) {
                        $destination = $parsed;
                    } else {
                        $destination = $alamatMaps;
                    }
                }

                // Check if slug exists
                $existingUrl = $urlModel->findBySlug($slug);
                if ($existingUrl) {
                    $existingUser = $userModel->find($existingUrl['user_id']);
                    
                    // Prepare user update data
                    $userUpdate = [
                        'username' => $username,
                        'nama' => $nama,
                        'nomor_wa' => $nomorWa,
                        'alamat_maps' => $alamatMaps,
                    ];
                    
                    // Only update password if a new one is provided in CSV
                    if (!empty($password) && $password !== 'UNCLAIMED') {
                        $userUpdate['password_hash'] = $passwordHash;
                    }

                    $userModel->skipValidation(true)->update($existingUser['id'], $userUpdate);
                    $urlModel->skipValidation(true)->update($existingUrl['id'], [
                        'destination_url' => $destination
                    ]);
                    $updateCount++;
                } else {
                    // Make sure username is unique, if not append random
                    $existingUser = $userModel->where('username', $username)->first();
                    if ($existingUser) {
                        $username = $username . rand(10, 99);
                    }

                    $userId = $userModel->skipValidation(true)->insert([
                        'username'      => $username,
                        'password_hash' => $passwordHash,
                        'role'          => 'client',
                        'nama'          => $nama,
                        'nomor_wa'      => $nomorWa,
                        'alamat_maps'   => $alamatMaps
                    ]);

                    $urlModel->skipValidation(true)->insert([
                        'slug_url'        => $slug,
                        'destination_url' => $destination,
                        'user_id'         => $userId
                    ]);
                    $successCount++;
                }
                $rowIdx++;
            }
            fclose($handle);
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data dari CSV.');
            }

            return redirect()->back()->with('success', "Proses impor berhasil. Ditambahkan: $successCount, Diperbarui: $updateCount.");
        }

        return redirect()->back()->with('error', 'Gagal membaca file CSV.');
    }
}
