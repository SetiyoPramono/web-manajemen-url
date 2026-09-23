<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BlockedIpModel;
use App\Models\SettingModel;

class AuthController extends BaseController
{
    /**
     * Show login page
     */
    public function login()
    {
        // Check if IP is currently blocked
        $ip = $this->request->getIPAddress();
        $blockedIpModel = new BlockedIpModel();
        if ($blockedIpModel->checkBlocked($ip)) {
            return redirect()->to('/blocked');
        }

        // If already logged in, redirect to appropriate dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') === 'admin' ? '/admin' : '/client');
        }

        return view('login');
    }

    /**
     * Process login attempt
     */
    public function attemptLogin()
    {
        $ip = $this->request->getIPAddress();
        $blockedIpModel = new BlockedIpModel();

        // Check if IP is currently blocked
        if ($blockedIpModel->checkBlocked($ip)) {
            return redirect()->to('/blocked');
        }

        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            // Record failed attempt
            $attemptResult = $blockedIpModel->recordFailedAttempt($ip, $username);

            if (!empty($attemptResult['blocked'])) {
                return redirect()->to('/blocked');
            }

            $attemptCount = $attemptResult['attempts'] ?? 1;
            return redirect()->back()->withInput()->with('error', "Username atau password salah. (Percobaan ke-{$attemptCount} dari 6)");
        }

        // Successful login: clear failed attempts for this IP
        $blockedIpModel->clearFailedAttempts($ip);

        // Set session data
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        // Redirect based on role
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin')->with('success', 'Selamat datang, Admin!');
        }

        return redirect()->to('/client')->with('success', 'Selamat datang, ' . esc($user['username']) . '!');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Show card registration form
     */
    public function registerCard($slug)
    {
        $urlModel = new \App\Models\UrlModel();
        $userModel = new UserModel();

        $url = $urlModel->where('slug_url', $slug)->first();
        if (!$url) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $user = $userModel->find($url['user_id']);
        if (!$user || $user['password_hash'] !== 'UNCLAIMED') {
            // Already claimed, redirect to the URL normally
            return redirect()->to('/' . $slug);
        }

        return view('register_card', ['slug' => $slug]);
    }

    /**
     * Process card registration
     */
    public function processRegisterCard($slug)
    {
        $urlModel = new \App\Models\UrlModel();
        $userModel = new UserModel();

        $url = $urlModel->where('slug_url', $slug)->first();
        if (!$url) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $user = $userModel->find($url['user_id']);
        if (!$user || $user['password_hash'] !== 'UNCLAIMED') {
            return redirect()->to('/' . $slug);
        }

        $rules = [
            'username'      => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . $user['id'] . ']',
            'password'      => 'required|min_length[6]',
            'nama'          => 'required|max_length[100]',
            'nomor_wa'      => 'required|max_length[20]',
            'alamat_maps'   => 'required|valid_url_strict',
            'jumlah_ulasan' => 'permit_empty|is_natural',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $nama = $this->request->getPost('nama');
        $nomorWa = $this->request->getPost('nomor_wa');
        $alamatMaps = $this->request->getPost('alamat_maps');

        // Review count & timestamp
        $rawJumlahUlasan = $this->request->getPost('jumlah_ulasan');
        $jumlahUlasan = null;
        $tglUlasan = null;
        if ($rawJumlahUlasan !== null && $rawJumlahUlasan !== '') {
            $jumlahUlasan = (int) $rawJumlahUlasan;
            $tglUlasan = date('Y-m-d H:i:s');
        }

        // Automatically try to convert standard Maps URL to a Review Link
        $generatedLink = \App\Controllers\ToolsController::parseGoogleMapsUrl($alamatMaps);
        if ($generatedLink) {
            $alamatMaps = $generatedLink;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Update User
        $userModel->skipValidation(true)->update($user['id'], [
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'nama'          => $nama,
            'nomor_wa'      => $nomorWa,
            'alamat_maps'   => $alamatMaps,
            'jumlah_ulasan' => $jumlahUlasan,
            'tgl_ulasan'    => $tglUlasan,
        ]);

        // Update URL destination
        $urlModel->skipValidation(true)->update($url['id'], [
            'destination_url' => $alamatMaps
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }

        // Auto-login
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $username,
            'role'       => 'client',
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/client')->with('success', 'Selamat! Kartu Anda telah aktif dan siap digunakan.');
    }

    /**
     * Show blocked IP notice page
     */
    public function blocked()
    {
        $ip = $this->request->getIPAddress();
        $blockedIpModel = new BlockedIpModel();
        $blocked = $blockedIpModel->checkBlocked($ip);

        // If not blocked, redirect to login
        if (! $blocked) {
            return redirect()->to('/login')->with('info', 'Alamat IP Anda saat ini tidak sedang diblokir.');
        }

        // Retrieve CS WhatsApp number for contact button
        $settingModel = new SettingModel();
        $csNumbersJson = $settingModel->getValue('cs_wa_numbers');
        $targetPhone = '6281234567890';
        $csName = 'Admin';

        if (! empty($csNumbersJson)) {
            $csList = json_decode($csNumbersJson, true);
            if (is_array($csList)) {
                foreach ($csList as $cs) {
                    if (! empty($cs['number']) && ($cs['status'] ?? 'active') === 'active') {
                        $targetPhone = preg_replace('/[^0-9]/', '', $cs['number']);
                        if (! empty($cs['name'])) {
                            $csName = $cs['name'];
                        }
                        break;
                    }
                }
            }
        } else {
            $singleWa = $settingModel->getValue('cs_whatsapp_number');
            if (! empty($singleWa)) {
                $targetPhone = preg_replace('/[^0-9]/', '', $singleWa);
            }
        }

        $waMessage = "Halo {$csName}, alamat IP saya ({$ip}) terblokir di sistem ReviewBoost karena salah memasukkan password. Mohon bantuan untuk membuka blokir akun saya. Terima kasih.";
        $waUrl = 'https://wa.me/' . $targetPhone . '?text=' . rawurlencode($waMessage);

        return view('ip_blocked', [
            'ip'       => $ip,
            'blocked'  => $blocked,
            'wa_url'   => $waUrl,
            'cs_phone' => $targetPhone,
            'cs_name'  => $csName,
        ]);
    }
}
