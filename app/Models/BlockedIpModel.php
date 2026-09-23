<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockedIpModel extends Model
{
    protected $table            = 'blocked_ips';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ip_address',
        'failed_attempts',
        'last_username_tried',
        'last_attempt_at',
        'is_blocked',
        'is_permanent',
        'blocked_at',
        'blocked_until',
        'reason',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Check if an IP is currently blocked.
     * Auto-unblocks if the 6-hour period has elapsed.
     */
    public function checkBlocked(string $ip): array|false
    {
        $record = $this->where('ip_address', $ip)->first();

        if (! $record || (int) $record['is_blocked'] !== 1) {
            return false;
        }

        // If permanent block, still blocked
        if ((int) $record['is_permanent'] === 1) {
            return $record;
        }

        // If time-based block
        if (! empty($record['blocked_until'])) {
            if (strtotime($record['blocked_until']) > time()) {
                return $record;
            }

            // Time has expired: auto-unblock
            $this->update($record['id'], [
                'is_blocked'      => 0,
                'failed_attempts' => 0,
                'blocked_until'   => null,
            ]);

            return false;
        }

        return false;
    }

    /**
     * Record a failed login attempt.
     * Blocks for 6 hours if failed attempts reach 6.
     */
    public function recordFailedAttempt(string $ip, ?string $username = null): array
    {
        $record = $this->where('ip_address', $ip)->first();
        $now = date('Y-m-d H:i:s');

        if ($record) {
            $newAttempts = (int) $record['failed_attempts'] + 1;

            if ($newAttempts >= 6) {
                $blockedUntil = date('Y-m-d H:i:s', strtotime('+6 hours'));
                $this->update($record['id'], [
                    'failed_attempts'     => $newAttempts,
                    'last_username_tried' => $username,
                    'last_attempt_at'     => $now,
                    'is_blocked'          => 1,
                    'is_permanent'        => 0,
                    'blocked_at'          => $now,
                    'blocked_until'       => $blockedUntil,
                    'reason'              => '6x percobaan login gagal berturut-turut',
                ]);

                return [
                    'blocked'       => true,
                    'attempts'      => $newAttempts,
                    'blocked_until' => $blockedUntil,
                    'is_permanent'  => false,
                ];
            }

            $this->update($record['id'], [
                'failed_attempts'     => $newAttempts,
                'last_username_tried' => $username,
                'last_attempt_at'     => $now,
            ]);

            return [
                'blocked'   => false,
                'attempts'  => $newAttempts,
                'remaining' => 6 - $newAttempts,
            ];
        }

        // First attempt for this IP
        $this->insert([
            'ip_address'          => $ip,
            'failed_attempts'     => 1,
            'last_username_tried' => $username,
            'last_attempt_at'     => $now,
            'is_blocked'          => 0,
            'is_permanent'        => 0,
        ]);

        return [
            'blocked'   => false,
            'attempts'  => 1,
            'remaining' => 5,
        ];
    }

    /**
     * Clear failed attempts when a user successfully logs in
     */
    public function clearFailedAttempts(string $ip): void
    {
        $record = $this->where('ip_address', $ip)->first();
        if ($record && (int) $record['is_blocked'] === 0) {
            $this->update($record['id'], [
                'failed_attempts'     => 0,
                'last_username_tried' => null,
            ]);
        }
    }

    /**
     * Block an IP manually from Admin Dashboard
     */
    public function blockIpManual(string $ip, string $reason = 'Diblokir manual oleh Administrator', bool $isPermanent = false, int $hours = 6): bool
    {
        $record = $this->where('ip_address', $ip)->first();
        $now = date('Y-m-d H:i:s');
        $blockedUntil = $isPermanent ? null : date('Y-m-d H:i:s', strtotime("+{$hours} hours"));

        $data = [
            'ip_address'   => $ip,
            'is_blocked'   => 1,
            'is_permanent' => $isPermanent ? 1 : 0,
            'blocked_at'   => $now,
            'blocked_until'=> $blockedUntil,
            'reason'       => $reason,
        ];

        if ($record) {
            return (bool) $this->update($record['id'], $data);
        }

        $data['failed_attempts'] = 6;
        return (bool) $this->insert($data);
    }

    /**
     * Unblock an IP
     */
    public function unblockIp(int $id): bool
    {
        return (bool) $this->update($id, [
            'is_blocked'      => 0,
            'failed_attempts' => 0,
            'is_permanent'    => 0,
            'blocked_until'   => null,
        ]);
    }

    /**
     * Toggle permanent block status
     */
    public function togglePermanent(int $id): bool
    {
        $record = $this->find($id);
        if (! $record) {
            return false;
        }

        $isPermanent = (int) ($record['is_permanent'] ?? 0) === 1;

        if ($isPermanent) {
            // Change to 6 hours from now
            return (bool) $this->update($id, [
                'is_permanent'  => 0,
                'is_blocked'    => 1,
                'blocked_until' => date('Y-m-d H:i:s', strtotime('+6 hours')),
            ]);
        }

        // Change to permanent
        return (bool) $this->update($id, [
            'is_permanent'  => 1,
            'is_blocked'    => 1,
            'blocked_until' => null,
        ]);
    }

    /**
     * Count currently active blocked IPs
     */
    public function countActiveBlocked(): int
    {
        $now = date('Y-m-d H:i:s');
        return $this->where('is_blocked', 1)
            ->groupStart()
                ->where('is_permanent', 1)
                ->orWhere('blocked_until >', $now)
            ->groupEnd()
            ->countAllResults();
    }
}
