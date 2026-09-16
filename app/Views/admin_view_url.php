<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Data — ReviewBoost</title>
    <meta name="description" content="Detail data klien.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-up { animation: slideUp 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200/60 sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('admin') ?>" class="flex items-center justify-center w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors" title="Kembali">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">Detail URL</h1>
                        <p class="text-xs text-slate-400 -mt-0.5">/<?= esc($url['slug_url']) ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('admin/edit/' . $url['id']) ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-indigo-500 hover:text-white bg-indigo-50 hover:bg-indigo-500 border border-indigo-200 hover:border-indigo-500 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="hidden sm:inline">Edit</span>
                    </a>
                    <a href="<?= base_url('admin') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        <span class="hidden sm:inline">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- URL Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6 animate-fade-in">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-slate-800">Informasi URL</h2>
            </div>

            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Slug URL</span>
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url($url['slug_url']) ?>" target="_blank" class="text-sm font-semibold text-indigo-500 hover:text-indigo-600 transition-colors">
                            <?= base_url($url['slug_url']) ?>
                        </a>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">URL Tujuan</span>
                    <?php if (!empty($url['destination_url'])): ?>
                        <a href="<?= esc($url['destination_url']) ?>" target="_blank" class="text-sm text-slate-700 hover:text-indigo-500 transition-colors break-all"><?= esc($url['destination_url']) ?></a>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-medium rounded-lg">
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                            Belum diatur
                        </span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Status</span>
                    <?php if (!empty($url['destination_url'])): ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-medium rounded-lg">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                            Aktif — Redirect berjalan
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-500 text-xs font-medium rounded-lg">
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                            Nonaktif — Belum ada tujuan
                        </span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Dibuat</span>
                    <span class="text-sm text-slate-700"><?= date('d F Y, H:i', strtotime($url['created_at'])) ?> WIB</span>
                </div>
            </div>
        </div>

        <!-- Account Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6 animate-slide-up" style="animation-delay: 0.05s;">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-slate-800">Akun Login Klien</h2>
            </div>

            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Username</span>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 text-sm font-mono rounded-lg"><?= esc($client['username'] ?? '-') ?></span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Password</span>
                    <span class="text-xs text-slate-400 italic">Password terenkripsi — gunakan tombol Edit untuk mengubah</span>
                </div>
            </div>
        </div>

        <!-- Bio Data Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-slate-800">Bio Data Pelanggan</h2>
            </div>

            <div class="space-y-4">
                <!-- Nama -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Nama Lengkap</span>
                    <?php if (!empty($client['nama'])): ?>
                        <span class="text-sm text-slate-700"><?= esc($client['nama']) ?></span>
                    <?php else: ?>
                        <span class="text-sm text-slate-400 italic">Belum diisi</span>
                    <?php endif; ?>
                </div>

                <!-- Nomor WA -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0">Nomor WhatsApp</span>
                    <?php if (!empty($client['nomor_wa'])): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-700"><?= esc($client['nomor_wa']) ?></span>
                            <?php
                                $waNumber = preg_replace('/[^0-9]/', '', $client['nomor_wa']);
                                if (substr($waNumber, 0, 1) === '0') {
                                    $waNumber = '62' . substr($waNumber, 1);
                                }
                            ?>
                            <a href="https://wa.me/<?= $waNumber ?>" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-600 text-xs font-medium rounded-md hover:bg-emerald-100 transition-colors">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Chat
                            </a>
                        </div>
                    <?php else: ?>
                        <span class="text-sm text-slate-400 italic">Belum diisi</span>
                    <?php endif; ?>
                </div>

                <!-- Alamat / Maps -->
                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 py-3">
                    <span class="text-sm font-medium text-slate-500 sm:w-40 flex-shrink-0 sm:pt-0.5">Alamat / Maps</span>
                    <?php if (!empty($client['alamat_maps'])): ?>
                        <div class="flex-1">
                            <?php if (filter_var($client['alamat_maps'], FILTER_VALIDATE_URL)): ?>
                                <a href="<?= esc($client['alamat_maps']) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-indigo-500 hover:text-indigo-600 transition-colors break-all">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <?= esc($client['alamat_maps']) ?>
                                </a>
                            <?php else: ?>
                                <div class="flex items-start gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm text-slate-700"><?= esc($client['alamat_maps']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-sm text-slate-400 italic">Belum diisi</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row gap-3 animate-slide-up" style="animation-delay: 0.15s;">
            <a
                href="<?= base_url('admin/edit/' . $url['id']) ?>"
                class="flex-1 py-3 px-6 text-center bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-xl shadow-sm shadow-indigo-200 hover:shadow-md hover:shadow-indigo-200 transition-all duration-200"
            >
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Data
                </span>
            </a>
            <a
                href="<?= base_url('admin') ?>"
                class="py-3 px-6 text-center text-slate-600 font-medium bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition-all duration-200"
            >
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
