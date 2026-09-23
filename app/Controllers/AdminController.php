<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UrlModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends BaseController
{
    /**
     * Admin Dashboard - List all URLs and their associated client users
     */
    public function index()
    {
        $urlModel  = new UrlModel();
        $userModel = new UserModel();

        // Get filter inputs
        $limit = $this->request->getGet('limit') ?? 25;
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Get all URLs with their associated client usernames and review stats
        $query = $urlModel
            ->select('urls.*, users.username as client_username, users.password_hash, users.nama as client_nama, users.jumlah_ulasan, users.tgl_ulasan')
            ->join('users', 'users.id = urls.user_id', 'left')
            ->orderBy('urls.created_at', 'DESC');

        if (!empty($startDate) && !empty($endDate)) {
            // Include entire end date day
            $query->where('DATE(urls.created_at) >=', $startDate)
                  ->where('DATE(urls.created_at) <=', $endDate);
        }

        $urls = $query->paginate($limit);
        $pager = $urlModel->pager;

        // Compute summary statistics for dashboard cards
        $db = \Config\Database::connect();
        $statsRow = $db->table('urls')
            ->select('COUNT(*) as total_urls, COALESCE(SUM(click_count), 0) as total_clicks')
            ->get()->getRowArray();

        $claimedCount = $db->table('urls')
            ->join('users', 'users.id = urls.user_id')
            ->where('users.password_hash !=', 'UNCLAIMED')
            ->countAllResults();

        $unclaimedCount = $db->table('urls')
            ->join('users', 'users.id = urls.user_id')
            ->where('users.password_hash', 'UNCLAIMED')
            ->countAllResults();

        $stats = [
            'total_urls'      => (int) ($statsRow['total_urls'] ?? 0),
            'total_clicks'    => (int) ($statsRow['total_clicks'] ?? 0),
            'total_claimed'   => $claimedCount,
            'total_unclaimed' => $unclaimedCount,
        ];

        return view('admin_dashboard', [
            'urls'       => $urls,
            'pager'      => $pager,
            'limit'      => $limit,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'stats'      => $stats,
        ]);
    }

    /**
     * Create a new URL slug and auto-generate a client user
     */
    public function createUrl()
    {
        $rules = [
            'slug_url' => 'permit_empty|alpha_dash|min_length[2]|max_length[100]|is_unique[urls.slug_url]',
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

        $qty = (int) $this->request->getPost('qty');
        if ($qty < 1) $qty = 1;
        
        $userModel = new UserModel();
        $urlModel = new UrlModel();
        
        $db = \Config\Database::connect();
        $db->transStart();

        if ($qty > 1) {
            for ($i = 0; $i < $qty; $i++) {
                do {
                    $randomSlug = bin2hex(random_bytes(3));
                    $exists = $urlModel->where('slug_url', $randomSlug)->first();
                } while ($exists);
                
                $userModel->skipValidation(true)->insert([
                    'username'      => $randomSlug,
                    'password_hash' => 'UNCLAIMED',
                    'role'          => 'client',
                ]);
                
                $newUserId = $userModel->getInsertID();
                
                $urlModel->skipValidation(true)->insert([
                    'slug_url'        => $randomSlug,
                    'destination_url' => null,
                    'user_id'         => $newUserId,
                ]);
            }
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to('/admin')->with('error', 'Gagal membuat URL massal.');
            }

            return redirect()->to('/admin')->with('success', "Berhasil! {$qty} URL acak telah dibuat.");
        } else {
            $slugUrl = $this->request->getPost('slug_url');

            // Generate automatic slug if empty
            if (empty($slugUrl)) {
                do {
                    $slugUrl = bin2hex(random_bytes(3)); // 6 characters
                    $exists = $urlModel->where('slug_url', $slugUrl)->first();
                } while ($exists);
            }

            $clientUsername = $slugUrl;
            $hashedPassword = 'UNCLAIMED';

            $userModel->skipValidation(true)->insert([
                'username'      => $clientUsername,
                'password_hash' => $hashedPassword,
                'role'          => 'client',
            ]);

            $newUserId = $userModel->getInsertID();

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
            'username'      => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$client['id']}]",
            'nama'          => 'permit_empty|max_length[255]',
            'nomor_wa'      => 'permit_empty|max_length[20]',
            'alamat_maps'   => 'permit_empty|max_length[2048]',
            'jumlah_ulasan' => 'permit_empty|is_natural',
        ];

        $messages = [
            'username' => [
                'is_unique' => 'Username sudah digunakan oleh user lain.',
            ],
            'jumlah_ulasan' => [
                'is_natural' => 'Jumlah ulasan harus berupa angka bulat positif atau 0.',
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

        // Process review count and update date
        $rawJumlahUlasan = $this->request->getPost('jumlah_ulasan');
        if ($rawJumlahUlasan !== null && $rawJumlahUlasan !== '') {
            $newJumlah = (int) $rawJumlahUlasan;
            $oldJumlah = isset($client['jumlah_ulasan']) && $client['jumlah_ulasan'] !== null ? (int) $client['jumlah_ulasan'] : null;

            $userData['jumlah_ulasan'] = $newJumlah;
            if ($oldJumlah === null || $newJumlah !== $oldJumlah || empty($client['tgl_ulasan'])) {
                $userData['tgl_ulasan'] = date('Y-m-d H:i:s');
            }
        } else {
            $userData['jumlah_ulasan'] = null;
            $userData['tgl_ulasan']    = null;
        }

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
     * Reset data URL and User (unclaim)
     */
    public function resetUrl($id)
    {
        $urlModel = new UrlModel();
        $url = $urlModel->find($id);

        if (! $url) {
            return redirect()->to('/admin')->with('error', 'URL tidak ditemukan.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($url['user_id']);

        $db = \Config\Database::connect();
        $db->transStart();

        // Update the URL destination to empty string
        $urlModel->update($id, ['destination_url' => '']);

        // Update User to UNCLAIMED
        $userModel->skipValidation(true)->update($user['id'], [
            'username'      => $url['slug_url'],
            'password_hash' => 'UNCLAIMED',
            'nama'          => '',
            'nomor_wa'      => '',
            'alamat_maps'   => ''
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/admin')->with('error', 'Gagal mereset data.');
        }

        return redirect()->to('/admin')->with('success', 'Data klien berhasil direset.');
    }

    /**
     * Process bulk actions (delete, reset, export)
     */
    public function bulkAction()
    {
        $action = $this->request->getPost('action');
        $ids = $this->request->getPost('ids');

        if (empty($ids) || !is_array($ids)) {
            return redirect()->to('/admin')->with('error', 'Tidak ada data yang dipilih.');
        }

        $urlModel = new UrlModel();
        $userModel = new UserModel();

        if ($action === 'delete') {
            $db = \Config\Database::connect();
            $db->transStart();
            
            foreach ($ids as $id) {
                $url = $urlModel->find($id);
                if ($url) {
                    $urlModel->delete($id);
                    $userModel->delete($url['user_id']);
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->to('/admin')->with('error', 'Gagal menghapus data secara massal.');
            }
            return redirect()->to('/admin')->with('success', count($ids) . ' data berhasil dihapus.');
            
        } elseif ($action === 'reset') {
            $db = \Config\Database::connect();
            $db->transStart();
            
            foreach ($ids as $id) {
                $url = $urlModel->find($id);
                if ($url) {
                    $user = $userModel->find($url['user_id']);
                    if ($user) {
                        $urlModel->update($id, ['destination_url' => '']);
                        $userModel->skipValidation(true)->update($user['id'], [
                            'username'      => $url['slug_url'],
                            'password_hash' => 'UNCLAIMED',
                            'nama'          => '',
                            'nomor_wa'      => '',
                            'alamat_maps'   => ''
                        ]);
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->to('/admin')->with('error', 'Gagal mereset data secara massal.');
            }
            return redirect()->to('/admin')->with('success', count($ids) . ' data berhasil direset.');
            
        } elseif ($action === 'export') {
            $urls = $urlModel->select('urls.slug_url, urls.destination_url, users.username, users.password_hash, users.nama, users.nomor_wa, users.alamat_maps')
                             ->join('users', 'users.id = urls.user_id')
                             ->whereIn('urls.id', $ids)
                             ->orderBy('urls.created_at', 'DESC')
                             ->findAll();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['Slug URL', 'URL Tujuan', 'Username Login', 'Password', 'Nama Bisnis', 'Nomor WhatsApp', 'Alamat Maps (Asli)'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $row = 2;
            foreach ($urls as $url) {
                $password = ($url['password_hash'] === 'UNCLAIMED') ? 'UNCLAIMED' : '';
                $sheet->setCellValue('A' . $row, base_url($url['slug_url']));
                $sheet->setCellValue('B' . $row, $url['destination_url']);
                $sheet->setCellValue('C' . $row, $url['username']);
                $sheet->setCellValue('D' . $row, $password);
                $sheet->setCellValue('E' . $row, $url['nama']);
                $sheet->setCellValueExplicit('F' . $row, (string)$url['nomor_wa'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('G' . $row, $url['alamat_maps']);
                $row++;
            }

            $filename = 'export_terpilih_' . date('Y-m-d_H-i-s') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        return redirect()->to('/admin');
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

        $rawNumbers = $settingModel->getValue('cs_wa_numbers', '');
        $csNumbers = [];
        if (!empty($rawNumbers)) {
            $csNumbers = json_decode($rawNumbers, true) ?: [];
        }

        // Backward compatibility if cs_wa_numbers is empty but cs_wa_number exists
        if (empty($csNumbers)) {
            $oldNumber = $settingModel->getValue('cs_wa_number', '');
            if (!empty($oldNumber)) {
                $csNumbers[] = [
                    'name'      => 'CS 1',
                    'number'    => $oldNumber,
                    'is_active' => 1,
                ];
            }
        }

        $settings = [
            'cs_wa_number'  => $settingModel->getValue('cs_wa_number', ''),
            'cs_wa_enabled' => $settingModel->getValue('cs_wa_enabled', '0'),
            'cs_wa_numbers' => $csNumbers,
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
        $settingModel = new \App\Models\SettingModel();
        $isEnabled = $this->request->getPost('cs_wa_enabled') ? '1' : '0';
        $csInput = $this->request->getPost('cs') ?: [];

        $cleanedList = [];
        $firstActiveNumber = '';

        if (is_array($csInput)) {
            foreach ($csInput as $item) {
                $rawNumber = $item['number'] ?? '';
                // Clean the WA number (keep digits only)
                $number = preg_replace('/[^0-9]/', '', $rawNumber);
                if (empty($number)) {
                    continue; // Skip empty rows
                }
                if (substr($number, 0, 1) === '0') {
                    $number = '62' . substr($number, 1);
                }

                $name = trim($item['name'] ?? '');
                if (empty($name)) {
                    $name = 'CS ' . (count($cleanedList) + 1);
                }

                $isActive = (isset($item['is_active']) && (string)$item['is_active'] === '1') ? 1 : 0;

                $cleanedList[] = [
                    'name'      => $name,
                    'number'    => $number,
                    'is_active' => $isActive,
                ];

                if ($isActive === 1 && empty($firstActiveNumber)) {
                    $firstActiveNumber = $number;
                }
            }
        }

        // Validation: If enabled, require at least one number
        if ($isEnabled === '1' && empty($cleanedList)) {
            return redirect()->back()->withInput()->with('error', 'Harap masukkan setidaknya satu nomor WhatsApp CS jika fitur diaktifkan.');
        }

        // If no active number found but list is not empty, fallback to first number
        if (empty($firstActiveNumber) && !empty($cleanedList)) {
            $firstActiveNumber = $cleanedList[0]['number'];
        }

        $settingModel->setValue('cs_wa_numbers', json_encode($cleanedList));
        $settingModel->setValue('cs_wa_number', $firstActiveNumber);
        $settingModel->setValue('cs_wa_enabled', $isEnabled);

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan Customer Support dan Rotator berhasil diperbarui.');
    }

    /**
     * Export all URLs and Clients to Excel
     */
    public function exportCsv()
    {
        $urlModel = new UrlModel();
        
        $urls = $urlModel->select('urls.slug_url, urls.destination_url, users.username, users.password_hash, users.nama, users.nomor_wa, users.alamat_maps, users.jumlah_ulasan, users.tgl_ulasan')
                         ->join('users', 'users.id = urls.user_id')
                         ->orderBy('urls.created_at', 'DESC')
                         ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write header
        $headers = ['Slug URL', 'URL Tujuan', 'Username Login', 'Password', 'Nama Bisnis', 'Nomor WhatsApp', 'Alamat Maps (Asli)', 'Jumlah Ulasan', 'Tanggal Ulasan Diisi'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Write data
        $row = 2;
        foreach ($urls as $url) {
            $password = ($url['password_hash'] === 'UNCLAIMED') ? 'UNCLAIMED' : ''; // Do not export hashed passwords
            $sheet->setCellValue('A' . $row, base_url($url['slug_url']));
            $sheet->setCellValue('B' . $row, $url['destination_url']);
            $sheet->setCellValue('C' . $row, $url['username']);
            $sheet->setCellValue('D' . $row, $password);
            $sheet->setCellValue('E' . $row, $url['nama']);
            $sheet->setCellValueExplicit('F' . $row, (string)$url['nomor_wa'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $row, $url['alamat_maps']);
            $sheet->setCellValue('H' . $row, $url['jumlah_ulasan'] !== null ? $url['jumlah_ulasan'] : '');
            $sheet->setCellValue('I' . $row, !empty($url['tgl_ulasan']) ? $url['tgl_ulasan'] : '');
            $row++;
        }

        $filename = 'export_klien_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Import Excel to insert/update URLs and Clients
     */
    public function importCsv()
    {
        $validationRule = [
            'csv_file' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[csv_file]|ext_in[csv_file,xlsx,xls]',
            ],
        ];

        if (! $this->validate($validationRule)) {
            return redirect()->back()->with('error', $this->validator->getError('csv_file'));
        }

        $file = $this->request->getFile('csv_file');

        if ($file->isValid() && ! $file->hasMoved()) {
            $filepath = $file->getTempName();
            
            try {
                $spreadsheet = IOFactory::load($filepath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal membaca file Excel.');
            }

            // Check if valid format
            if (count($rows) < 1 || !isset($rows[0][0]) || stripos($rows[0][0], 'Slug URL') === false) {
                return redirect()->back()->with('error', 'Format Excel tidak sesuai. Gunakan template hasil Ekspor Excel.');
            }

            $userModel = new UserModel();
            $urlModel = new UrlModel();
            
            $db = \Config\Database::connect();
            $db->transStart();

            $successCount = 0;
            $updateCount = 0;

            // Skip header (index 0)
            for ($i = 1; $i < count($rows); $i++) {
                $data = $rows[$i];
                // $data index: 0=>Slug, 1=>Destination, 2=>Username, 3=>Password, 4=>Nama, 5=>WA, 6=>Alamat Maps
                $slug        = trim((string)($data[0] ?? ''));
                $destination = trim((string)($data[1] ?? ''));
                $username    = trim((string)($data[2] ?? ''));
                $password    = trim((string)($data[3] ?? ''));
                $nama        = trim((string)($data[4] ?? ''));
                $nomorWa     = trim((string)($data[5] ?? ''));
                $alamatMaps  = trim((string)($data[6] ?? ''));
                $rawJumlah   = isset($data[7]) && trim((string)$data[7]) !== '' ? (int)trim((string)$data[7]) : null;
                $rawTgl      = isset($data[8]) && trim((string)$data[8]) !== '' ? trim((string)$data[8]) : null;

                if (empty($slug)) {
                    continue;
                }

                // Sanitize slug if it was imported as a full URL (e.g. from an exported file)
                if (filter_var($slug, FILTER_VALIDATE_URL) || str_contains($slug, '/')) {
                    $parsedPath = parse_url($slug, PHP_URL_PATH);
                    $slug = trim(basename(rtrim($parsedPath ?? $slug, '/')), '/');
                }

                if (empty($slug)) {
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

                    if ($rawJumlah !== null) {
                        $userUpdate['jumlah_ulasan'] = $rawJumlah;
                        $userUpdate['tgl_ulasan']    = !empty($rawTgl) ? date('Y-m-d H:i:s', strtotime($rawTgl)) : date('Y-m-d H:i:s');
                    }
                    
                    // Only update password if a new one is provided in Excel
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

                    $insertData = [
                        'username'      => $username,
                        'password_hash' => $passwordHash,
                        'role'          => 'client',
                        'nama'          => $nama,
                        'nomor_wa'      => $nomorWa,
                        'alamat_maps'   => $alamatMaps
                    ];

                    if ($rawJumlah !== null) {
                        $insertData['jumlah_ulasan'] = $rawJumlah;
                        $insertData['tgl_ulasan']    = !empty($rawTgl) ? date('Y-m-d H:i:s', strtotime($rawTgl)) : date('Y-m-d H:i:s');
                    }

                    $userId = $userModel->skipValidation(true)->insert($insertData);

                    $urlModel->skipValidation(true)->insert([
                        'slug_url'        => $slug,
                        'destination_url' => $destination,
                        'user_id'         => $userId
                    ]);
                    $successCount++;
                }
            }
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data dari Excel.');
            }

            return redirect()->back()->with('success', "Proses impor berhasil. Ditambahkan: $successCount, Diperbarui: $updateCount.");
        }

        return redirect()->back()->with('error', 'Gagal membaca file Excel.');
    }
}
