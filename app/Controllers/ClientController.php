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
        $userId   = session()->get('user_id');
        $urlModel = new UrlModel();
        $urls     = $urlModel->getUrlsByUser($userId);

        return view('client_dashboard', [
            'urls' => $urls,
        ]);
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
