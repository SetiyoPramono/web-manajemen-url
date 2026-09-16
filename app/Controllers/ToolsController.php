<?php

namespace App\Controllers;

class ToolsController extends BaseController
{
    /**
     * Generate Google Maps Review Link from a standard Maps URL
     */
    public function generateReviewLink()
    {
        // Only accept AJAX POST or normal POST
        $url = $this->request->getPost('url') ?? $this->request->getGet('url');

        if (empty($url)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'URL tidak boleh kosong.']);
        }

        // 1. If it's a short URL, expand it.
        if (preg_match('#^https?://(goo\.gl|maps\.app\.goo\.gl|g\.page)/#i', $url)) {
            $url = $this->expandUrl($url);
        }

        // 2. Try to extract CID or Place ID
        $reviewLink = null;

        // Case A: The URL already contains ludocid or placeid
        if (preg_match('/ludocid=([0-9]+)/i', $url, $matches)) {
            $reviewLink = 'https://search.google.com/local/writereview?ludocid=' . $matches[1];
        } elseif (preg_match('/placeid=([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            $reviewLink = 'https://search.google.com/local/writereview?placeid=' . $matches[1];
        }
        // Case B: The URL contains the hex structure (e.g. !1s0x...:0xCID)
        elseif (preg_match('/1s0x[0-9a-f]+:0x([0-9a-f]+)/i', $url, $matches)) {
            $hexCid = $matches[1];
            // Convert hex to decimal for ludocid
            // Using bcmath or gmp if available, but base_convert works for 64-bit PHP strings usually
            $decCid = base_convert($hexCid, 16, 10);
            $reviewLink = 'https://search.google.com/local/writereview?ludocid=' . $decCid;
        }
        // Case C: The URL contains ftid (Feature ID) e.g. ftid=0x...:0x...
        elseif (preg_match('/ftid=0x[0-9a-f]+:0x([0-9a-f]+)/i', $url, $matches)) {
            $hexCid = $matches[1];
            $decCid = base_convert($hexCid, 16, 10);
            $reviewLink = 'https://search.google.com/local/writereview?ludocid=' . $decCid;
        }

        if ($reviewLink) {
            return $this->response->setJSON([
                'status' => 'success',
                'review_link' => $reviewLink
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal mendeteksi ID Tempat dari URL. Pastikan Anda menyalin URL lengkap dari Google Maps.'
        ]);
    }

    /**
     * Follow redirects to get the final URL
     */
    private function expandUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true); // We just need headers, not body
        // Sometimes Google requires a valid user agent
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_exec($ch);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        return $finalUrl ?: $url;
    }
}
