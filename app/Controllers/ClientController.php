<?php

namespace App\Controllers;

use App\Models\UrlModel;

class ClientController extends BaseController
{
    /**
     * Client Dashboard - Show URLs belonging to this client
     */
    public function index()
    {
        $userId    = session()->get('user_id');
        $urlModel  = new UrlModel();
        $userModel = new \App\Models\UserModel();

        $urls   = $urlModel->getUrlsByUser($userId);
        $client = $userModel->find($userId);

        return view('client_dashboard', [
            'urls'   => $urls,
            'client' => $client,
        ]);
    }

    /**
     * Update client's review count
     */
    public function updateReviewCount()
    {
        $userId    = session()->get('user_id');
        $userModel = new \App\Models\UserModel();
        $client    = $userModel->find($userId);

        if (! $client) {
            return redirect()->to('/client')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $rules = [
            'jumlah_ulasan' => 'permit_empty|is_natural',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rawJumlahUlasan = $this->request->getPost('jumlah_ulasan');
        if ($rawJumlahUlasan !== null && $rawJumlahUlasan !== '') {
            $newJumlah = (int) $rawJumlahUlasan;
            $oldJumlah = isset($client['jumlah_ulasan']) && $client['jumlah_ulasan'] !== null ? (int) $client['jumlah_ulasan'] : null;

            $updateData = ['jumlah_ulasan' => $newJumlah];
            if ($oldJumlah === null || $newJumlah !== $oldJumlah || empty($client['tgl_ulasan'])) {
                $updateData['tgl_ulasan'] = date('Y-m-d H:i:s');
            }
        } else {
            $updateData = [
                'jumlah_ulasan' => null,
                'tgl_ulasan'    => null,
            ];
        }

        $userModel->skipValidation(true)->update($userId, $updateData);

        return redirect()->to('/client')->with('success', 'Jumlah ulasan berhasil diperbarui!');
    }

    /**
     * Update the destination URL for a client's URL
     */
    public function updateUrl()
    {
        $userId = session()->get('user_id');
        $urlId  = $this->request->getPost('url_id');

        $urlModel = new UrlModel();
        $url = $urlModel->find($urlId);

        // Verify ownership - client can only edit their own URLs
        if (! $url || (int) $url['user_id'] !== (int) $userId) {
            return redirect()->to('/client')->with('error', 'Anda tidak memiliki akses ke URL ini.');
        }

        $destinationUrl = $this->request->getPost('destination_url');

        // Validate destination URL (allow empty to clear it)
        if (! empty($destinationUrl)) {
            $rules = [
                'destination_url' => 'valid_url_strict[https,http]|max_length[2048]',
            ];

            $messages = [
                'destination_url' => [
                    'valid_url_strict' => 'URL tujuan harus berupa URL yang valid (contoh: https://example.com).',
                ],
            ];

            if (! $this->validate($rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        // Update the destination URL
        $urlModel->skipValidation(true)->update($urlId, [
            'destination_url' => empty($destinationUrl) ? null : $destinationUrl,
        ]);

        return redirect()->to('/client')->with('success', 'URL tujuan berhasil diperbarui!');
    }
}
