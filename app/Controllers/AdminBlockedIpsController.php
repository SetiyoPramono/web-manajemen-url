<?php

namespace App\Controllers;

use App\Models\BlockedIpModel;

class AdminBlockedIpsController extends BaseController
{
    /**
     * Display list of blocked IPs and attempts
     */
    public function index()
    {
        $blockedIpModel = new BlockedIpModel();

        // Get all records ordered by currently blocked first, then latest update
        $records = $blockedIpModel->orderBy('is_blocked', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        $now = date('Y-m-d H:i:s');
        $totalBlocked = 0;
        $totalPermanent = 0;
        $totalAttempts = count($records);

        foreach ($records as &$rec) {
            // Recalculate status dynamically in case time has elapsed
            if ((int) $rec['is_blocked'] === 1) {
                if ((int) $rec['is_permanent'] === 1) {
                    $totalBlocked++;
                    $totalPermanent++;
                    $rec['status_label'] = 'Permanen';
                    $rec['status_type']  = 'permanent';
                } elseif (! empty($rec['blocked_until']) && strtotime($rec['blocked_until']) > time()) {
                    $totalBlocked++;
                    $rec['status_label'] = 'Aktif (6 Jam)';
                    $rec['status_type']  = 'timed';
                    $secondsLeft = strtotime($rec['blocked_until']) - time();
                    $hoursLeft   = floor($secondsLeft / 3600);
                    $minutesLeft = floor(($secondsLeft % 3600) / 60);
                    $rec['remaining_time'] = "sisa {$hoursLeft}j {$minutesLeft}m";
                } else {
                    // Expired block
                    $rec['is_blocked']   = 0;
                    $rec['status_label'] = 'Kedaluwarsa';
                    $rec['status_type']  = 'expired';
                }
            } else {
                $rec['status_label'] = 'Normal (Belum Diblokir)';
                $rec['status_type']  = 'normal';
            }
        }
        unset($rec);

        return view('admin_blocked_ips', [
            'records' => $records,
            'stats'   => [
                'total_blocked'   => $totalBlocked,
                'total_permanent' => $totalPermanent,
                'total_attempts'  => $totalAttempts,
            ],
        ]);
    }

    /**
     * Manually block an IP
     */
    public function block()
    {
        $rules = [
            'ip_address'    => 'required|valid_ip',
            'reason'        => 'permit_empty|max_length[255]',
            'duration_type' => 'required|in_list[6_hours,permanent]',
        ];

        $messages = [
            'ip_address' => [
                'required' => 'Alamat IP wajib diisi.',
                'valid_ip' => 'Format alamat IP tidak valid (contoh: 192.168.1.1).',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ip           = trim($this->request->getPost('ip_address'));
        $reason       = trim($this->request->getPost('reason')) ?: 'Diblokir manual oleh Administrator';
        $durationType = $this->request->getPost('duration_type');
        $isPermanent  = ($durationType === 'permanent');

        $blockedIpModel = new BlockedIpModel();
        $blockedIpModel->blockIpManual($ip, $reason, $isPermanent, 6);

        return redirect()->to('/admin/blocked-ips')->with('success', "Alamat IP {$ip} berhasil diblokir (" . ($isPermanent ? 'Permanen' : '6 Jam') . ').');
    }

    /**
     * Unblock an IP
     */
    public function unblock($id)
    {
        $blockedIpModel = new BlockedIpModel();
        $record = $blockedIpModel->find($id);

        if (! $record) {
            return redirect()->to('/admin/blocked-ips')->with('error', 'Data IP tidak ditemukan.');
        }

        $blockedIpModel->unblockIp((int) $id);

        return redirect()->to('/admin/blocked-ips')->with('success', "Blokir untuk IP {$record['ip_address']} berhasil dibuka.");
    }

    /**
     * Toggle permanent block status
     */
    public function togglePermanent($id)
    {
        $blockedIpModel = new BlockedIpModel();
        $record = $blockedIpModel->find($id);

        if (! $record) {
            return redirect()->to('/admin/blocked-ips')->with('error', 'Data IP tidak ditemukan.');
        }

        $blockedIpModel->togglePermanent((int) $id);

        $newPermanent = ((int) ($record['is_permanent'] ?? 0) === 0);
        $statusText   = $newPermanent ? 'diubah menjadi Blokir Permanen' : 'diubah menjadi Blokir 6 Jam';

        return redirect()->to('/admin/blocked-ips')->with('success', "Status IP {$record['ip_address']} berhasil {$statusText}.");
    }

    /**
     * Delete a block / attempt record
     */
    public function delete($id)
    {
        $blockedIpModel = new BlockedIpModel();
        $record = $blockedIpModel->find($id);

        if (! $record) {
            return redirect()->to('/admin/blocked-ips')->with('error', 'Data IP tidak ditemukan.');
        }

        $blockedIpModel->delete($id);

        return redirect()->to('/admin/blocked-ips')->with('success', "Riwayat IP {$record['ip_address']} berhasil dihapus.");
    }
}
