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
            return $this->response->setJSON(['status' => 'error', 'message' => 'URL tidak boleh kosong.', 'csrf' => csrf_hash()]);
        }

        $reviewLink = self::parseGoogleMapsUrl($url);

        if ($reviewLink) {
            return $this->response->setJSON([
                'status' => 'success',
                'review_link' => $reviewLink,
                'csrf' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Gagal mendeteksi ID Tempat dari URL. Pastikan Anda menyalin URL lengkap dari Google Maps.',
            'csrf' => csrf_hash()
        ]);
    }

    /**
     * Parse any Google Maps URL and return a Review Link if possible, otherwise null
     */
    public static function parseGoogleMapsUrl($url)
    {
        if (preg_match('#^https?://(goo\.gl|maps\.app\.goo\.gl|g\.page)/#i', $url)) {
            $url = self::expandUrl($url);
        }

        if (preg_match('/ludocid=([0-9]+)/i', $url, $matches)) {
            return 'https://search.google.com/local/writereview?ludocid=' . $matches[1];
        } elseif (preg_match('/placeid=([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return 'https://search.google.com/local/writereview?placeid=' . $matches[1];
        } elseif (preg_match('/1s0x([0-9a-f]+):0x([0-9a-f]+)/i', $url, $matches)) {
            return 'https://search.google.com/local/writereview?placeid=' . self::hexToPlaceId($matches[1], $matches[2]);
        } elseif (preg_match('/ftid=0x([0-9a-f]+):0x([0-9a-f]+)/i', $url, $matches)) {
            return 'https://search.google.com/local/writereview?placeid=' . self::hexToPlaceId($matches[1], $matches[2]);
        }
        
        return null;
    }

    /**
     * Follow redirects to get the final URL
     */
    public static function expandUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true); // We just need headers, not body
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // 3 seconds connect timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);        // 5 seconds max transfer timeout
        // Sometimes Google requires a valid user agent
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_exec($ch);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        return $finalUrl ?: $url;
    }

    /**
     * Convert Google Maps Feature ID hexes (1s... : 0x...) into a valid Google Place ID (ChIJ...)
     */
    public static function hexToPlaceId($hex1, $hex2)
    {
        // Ensure both are 16 chars (8 bytes) padded with 0 if necessary
        $hex1 = str_pad($hex1, 16, '0', STR_PAD_LEFT);
        $hex2 = str_pad($hex2, 16, '0', STR_PAD_LEFT);

        // Convert hex to binary strings
        $b1 = hex2bin($hex1);
        $b2 = hex2bin($hex2);

        // Reverse bytes for little-endian encoding
        $b1 = strrev($b1);
        $b2 = strrev($b2);

        // Construct the protobuf message
        // 0x0a = tag 1, type string (length 18 bytes = 0x12)
        // 0x09 = tag 1, type 64-bit fixed
        // 0x11 = tag 2, type 64-bit fixed
        $msg = "\x0a\x12\x09" . $b1 . "\x11" . $b2;

        // Base64 encode and make it URL safe
        $base64 = base64_encode($msg);
        $urlSafe = str_replace(['+', '/'], ['-', '_'], $base64);
        
        // Remove padding '='
        return rtrim($urlSafe, '=');
    }

    /**
     * Get the next active CS WhatsApp number in round-robin order
     */
    public function rotateCs()
    {
        $settingModel = new \App\Models\SettingModel();

        $isEnabled = $settingModel->getValue('cs_wa_enabled', '0');
        if ($isEnabled !== '1') {
            return $this->response->setJSON([
                'status'  => 'disabled',
                'message' => 'Customer Support saat ini dinonaktifkan.',
            ]);
        }

        $rawNumbers = $settingModel->getValue('cs_wa_numbers', '');
        $allNumbers = [];
        if (!empty($rawNumbers)) {
            $allNumbers = json_decode($rawNumbers, true) ?: [];
        }

        // Filter only active numbers with a valid number
        $activeNumbers = array_values(array_filter($allNumbers, function ($item) {
            return !empty($item['number']) && (!isset($item['is_active']) || (int)$item['is_active'] === 1);
        }));

        // Fallback to legacy cs_wa_number if no active items found in cs_wa_numbers
        if (empty($activeNumbers)) {
            $fallbackNumber = $settingModel->getValue('cs_wa_number', '');
            if (!empty($fallbackNumber)) {
                $activeNumbers[] = [
                    'name'      => 'Customer Support',
                    'number'    => $fallbackNumber,
                    'is_active' => 1,
                ];
            }
        }

        if (empty($activeNumbers)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak ada nomor Customer Support yang aktif saat ini.',
            ]);
        }

        // Get current rotation index
        $currentIndex = (int) $settingModel->getValue('cs_wa_current_index', 0);
        $totalActive = count($activeNumbers);

        // Pick current CS
        $selectedCs = $activeNumbers[$currentIndex % $totalActive];

        // Increment rotation index for next call
        $nextIndex = ($currentIndex + 1) % $totalActive;
        $settingModel->setValue('cs_wa_current_index', (string) $nextIndex);

        return $this->response->setJSON([
            'status' => 'success',
            'name'   => $selectedCs['name'],
            'number' => $selectedCs['number'],
        ]);
    }
}
