<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    /**
     * Show login page
     */
    public function login()
    {
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
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

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
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . $user['id'] . ']',
            'password' => 'required|min_length[6]',
            'nama'     => 'required|max_length[100]',
            'nomor_wa' => 'required|max_length[20]',
            'alamat_maps' => 'required|valid_url_strict'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $nama = $this->request->getPost('nama');
        $nomorWa = $this->request->getPost('nomor_wa');
        $alamatMaps = $this->request->getPost('alamat_maps');

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
            'alamat_maps'   => $alamatMaps
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
}
