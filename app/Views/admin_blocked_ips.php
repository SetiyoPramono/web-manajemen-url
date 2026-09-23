<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Blokir IP — ReviewBoost</title>
    <link rel="icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        .animate-slide-up { animation: slideUp 0.35s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200/60 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('admin') ?>" class="flex items-center gap-2">
                        <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center p-1.5 border border-indigo-100">
                            <img src="<?= base_url('logo.png') ?>" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 text-base leading-tight">ReviewBoost</span>
                            <span class="text-[10px] text-slate-400 block -mt-0.5">Admin Security</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav Items -->
                <div class="hidden sm:flex items-center gap-2">
                    <a href="<?= base_url('admin') ?>" class="px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                        Beranda
                    </a>
                    <a href="<?= base_url('admin/users') ?>" class="px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                        Pengguna
                    </a>
                    <a href="<?= base_url('admin/blocked-ips') ?>" class="px-3.5 py-2 text-sm font-semibold text-red-600 bg-red-50 border border-red-200 rounded-xl transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>IP Diblokir</span>
                        <?php if (($stats['total_blocked'] ?? 0) > 0): ?>
                            <span class="px-1.5 py-0.2 bg-red-500 text-white text-[10px] font-bold rounded-full">
                                <?= $stats['total_blocked'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                        Pengaturan CS
                    </a>
                    <a href="<?= base_url('logout') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-red-500 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-xl transition-all ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="sm:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden sm:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-4 space-y-1">
            <a href="<?= base_url('admin') ?>" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Beranda</a>
            <a href="<?= base_url('admin/users') ?>" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Pengguna</a>
            <a href="<?= base_url('admin/blocked-ips') ?>" class="block px-3 py-2 text-sm font-semibold text-red-600 bg-red-50 rounded-lg">IP Diblokir (<?= $stats['total_blocked'] ?? 0 ?>)</a>
            <a href="<?= base_url('admin/settings') ?>" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Pengaturan CS</a>
            <a href="<?= base_url('logout') ?>" class="block px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">Logout</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 animate-slide-up">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 animate-slide-up">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 animate-slide-up">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen Blokir IP</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola alamat IP yang diblokir otomatis (6x salah login) atau diblokir secara manual oleh admin.
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 animate-fade-in">
            <!-- Active Blocked -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">IP Terblokir Aktif</span>
                    <span class="text-2xl font-extrabold text-red-600"><?= number_format($stats['total_blocked'] ?? 0) ?></span>
                    <p class="text-xs text-slate-400 mt-0.5">Sedang tidak bisa login</p>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center border border-red-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <!-- Permanent Blocked -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Blokir Permanen</span>
                    <span class="text-2xl font-extrabold text-purple-600"><?= number_format($stats['total_permanent'] ?? 0) ?></span>
                    <p class="text-xs text-slate-400 mt-0.5">Tidak terbuka otomatis</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center border border-purple-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>

            <!-- Total Recorded -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Total Riwayat Log</span>
                    <span class="text-2xl font-extrabold text-slate-700"><?= number_format($stats['total_attempts'] ?? 0) ?></span>
                    <p class="text-xs text-slate-400 mt-0.5">Alamat IP terdeteksi</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-2xl flex items-center justify-center border border-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Manual Block IP Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 sm:p-6 mb-6 animate-slide-up">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Blokir IP Manual</h2>
                    <p class="text-xs text-slate-400">Tambahkan alamat IP tertentu untuk diblokir sementara (6 jam) atau permanen</p>
                </div>
            </div>

            <form action="<?= base_url('admin/blocked-ips/block') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <?= csrf_field() ?>

                <div class="md:col-span-4">
                    <label for="ip_address" class="block text-xs font-semibold text-slate-600 mb-1.5">Alamat IP Address</label>
                    <input type="text" name="ip_address" id="ip_address" placeholder="Contoh: 182.253.12.8" required
                        class="input-focus block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:outline-none transition-all">
                </div>

                <div class="md:col-span-4">
                    <label for="reason" class="block text-xs font-semibold text-slate-600 mb-1.5">Alasan Pemblokiran</label>
                    <input type="text" name="reason" id="reason" placeholder="Contoh: Aktivitas mencurigakan"
                        class="input-focus block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label for="duration_type" class="block text-xs font-semibold text-slate-600 mb-1.5">Durasi Blokir</label>
                    <select name="duration_type" id="duration_type" class="input-focus block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none transition-all">
                        <option value="6_hours">6 Jam</option>
                        <option value="permanent">Permanen</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-semibold text-sm rounded-xl shadow-sm transition-all duration-200">
                        Blokir IP
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden animate-slide-up">
            
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Daftar IP &amp; Riwayat Percobaan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Menampilkan <?= count($records) ?> data</p>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" id="filter-input" placeholder="Cari IP atau username..." 
                        class="input-focus w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs placeholder-slate-400 focus:bg-white focus:outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <?php if (empty($records)): ?>
                <div class="p-12 text-center">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-700">Belum ada IP yang terblokir</h4>
                    <p class="text-xs text-slate-400 mt-1">Sistem keamanan aman. IP yang gagal login 6x akan otomatis tercatat di sini.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm" id="blocked-table">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-3">Alamat IP</th>
                                <th class="px-4 py-3">Percobaan Gagal</th>
                                <th class="px-4 py-3">Status Pemblokiran</th>
                                <th class="px-4 py-3">Alasan</th>
                                <th class="px-4 py-3">Waktu Terakhir</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($records as $r): ?>
                                <tr class="hover:bg-slate-50/70 transition-colors record-row" data-search="<?= esc(strtolower($r['ip_address'] . ' ' . ($r['last_username_tried'] ?? '') . ' ' . ($r['reason'] ?? ''))) ?>">
                                    
                                    <!-- IP Address -->
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-mono font-bold text-slate-800 text-sm">
                                            <?= esc($r['ip_address']) ?>
                                        </div>
                                    </td>

                                    <!-- Failed Attempts -->
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold <?= ((int)$r['failed_attempts'] >= 6) ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600' ?>">
                                                <?= (int)$r['failed_attempts'] ?> kali
                                            </span>
                                            <?php if (!empty($r['last_username_tried'])): ?>
                                                <span class="text-xs text-slate-400" title="Username terakhir yang dicoba">
                                                    (user: <strong class="text-slate-600 font-medium"><?= esc($r['last_username_tried']) ?></strong>)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Status / Duration -->
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <?php if ($r['status_type'] === 'permanent'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                                Blokir Permanen
                                            </span>
                                        <?php elseif ($r['status_type'] === 'timed'): ?>
                                            <div>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                    Aktif (<?= esc($r['remaining_time'] ?? '6 Jam') ?>)
                                                </span>
                                                <div class="text-[11px] text-slate-400 mt-0.5">
                                                    Sampai: <?= date('d/m H:i', strtotime($r['blocked_until'])) ?>
                                                </div>
                                            </div>
                                        <?php elseif ($r['status_type'] === 'expired'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                                Kedaluwarsa (Terbuka)
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                                Normal
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Reason -->
                                    <td class="px-4 py-3.5 text-xs text-slate-600 max-w-[200px] truncate" title="<?= esc($r['reason'] ?? '-') ?>">
                                        <?= esc($r['reason'] ?? '-') ?>
                                    </td>

                                    <!-- Time -->
                                    <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500">
                                        <?= !empty($r['last_attempt_at']) ? date('d M Y, H:i', strtotime($r['last_attempt_at'])) : date('d M Y, H:i', strtotime($r['created_at'])) ?>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            
                                            <?php if ((int)$r['is_blocked'] === 1): ?>
                                                <!-- Unblock Button -->
                                                <form action="<?= base_url('admin/blocked-ips/unblock/' . $r['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Buka blokir untuk IP <?= esc($r['ip_address']) ?>?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-colors" title="Buka Blokir">
                                                        Buka Blokir
                                                    </button>
                                                </form>

                                                <!-- Toggle Permanent Button -->
                                                <form action="<?= base_url('admin/blocked-ips/toggle-permanent/' . $r['id']) ?>" method="POST" class="inline">
                                                    <?= csrf_field() ?>
                                                    <?php if ((int)$r['is_permanent'] === 1): ?>
                                                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-colors" title="Ubah menjadi blokir berbatas waktu 6 jam">
                                                            Set 6 Jam
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors" title="Kunci IP ini secara permanen">
                                                            Permanen
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                            <?php endif; ?>

                                            <!-- Delete Button -->
                                            <form action="<?= base_url('admin/blocked-ips/delete/' . $r['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Hapus riwayat IP <?= esc($r['ip_address']) ?> dari log?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="p-1 text-slate-400 hover:text-red-600 rounded-lg transition-colors" title="Hapus Log">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script>
        // Real-time table filter
        const filterInput = document.getElementById('filter-input');
        if (filterInput) {
            filterInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.record-row');
                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    if (searchData.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    </script>
</body>
</html>
