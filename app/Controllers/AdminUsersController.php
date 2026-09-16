<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UrlModel;

class AdminUsersController extends BaseController
{
    /**
     * Show list of all users
     */
    public function index()
    {
        $userModel = new UserModel();
        $users = $userModel->orderBy('role', 'ASC')->orderBy('created_at', 'DESC')->findAll();

        return view('admin_users', [
            'users' => $users
        ]);
    }

    /**
     * Create a new user (Admin or Client without URL)
     */
    public function create()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'role'     => 'required|in_list[admin,client]',
            'nama'     => 'permit_empty|max_length[100]',
            'nomor_wa' => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        
        $userModel->insert([
            'username'      => $this->request->getPost('username'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'          => $this->request->getPost('role'),
            'nama'          => $this->request->getPost('nama'),
            'nomor_wa'      => $this->request->getPost('nomor_wa'),
            'alamat_maps'   => ''
        ]);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Delete a user permanently
     */
    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        // Prevent deleting yourself
        if ($user['id'] === session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Delete associated URLs first
        $urlModel = new UrlModel();
        $urlModel->where('user_id', $id)->delete();

        // Delete user
        $userModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/admin/users')->with('error', 'Gagal menghapus pengguna.');
        }

        return redirect()->to('/admin/users')->with('success', 'Pengguna dan data terkait berhasil dihapus.');
    }
}
