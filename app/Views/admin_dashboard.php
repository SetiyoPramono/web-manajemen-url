<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — ReviewBoost</title>
    <link rel="icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <meta name="description" content="Dashboard admin untuk mengelola klien.">
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
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        .table-row-hover:hover { background-color: #f8fafc; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200/60 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9">
                        <img src="<?= base_url('logo.png') ?>" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">ReviewBoost</h1>
                        <p class="text-xs text-slate-400 -mt-0.5">Admin Dashboard</p>
                    </div>
                </div>
                
                <!-- Right Side Actions -->
                <div class="flex items-center">
                    <!-- Desktop Buttons -->
                    <div class="hidden sm:flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 text-sm text-slate-500">
                        <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="font-medium text-slate-700"><?= esc(session()->get('username')) ?></span>
                    </div>
                    <button type="button" class="open-generator-btn inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-indigo-700 bg-slate-100 hover:bg-indigo-50 border border-transparent rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span class="hidden sm:inline">Generator URL</span>
                    </button>
                    <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 border border-transparent rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="hidden sm:inline">Pengguna</span>
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 border border-transparent rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="hidden sm:inline">Pengaturan CS</span>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-red-500 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </a>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden sm:hidden absolute top-16 left-0 w-full bg-white border-b border-slate-200 shadow-lg z-50">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <div class="px-3 py-2 text-sm text-slate-500 font-medium">
                    Masuk sebagai: <?= esc(session()->get('username')) ?>
                </div>
                <button type="button" class="open-generator-btn w-full text-left flex items-center gap-2 px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Generator URL
                </button>
                <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-2 px-3 py-2 text-base font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-50 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Pengguna
                </a>
                <a href="<?= base_url('admin/settings') ?>" class="flex items-center gap-2 px-3 py-2 text-base font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-50 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan CS
                </a>
                <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 px-3 py-2 text-base font-medium text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors mt-2 border-t border-slate-100 pt-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div id="alert-success" class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 animate-slide-up">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div id="alert-error" class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 animate-slide-up">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
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

        <!-- Create New URL Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 sm:p-6 mb-6 animate-fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Buat URL Baru</h2>
                        <p class="text-xs text-slate-400">Generate slug otomatis atau buat banyak sekaligus</p>
                    </div>
                </div>

                <!-- Quick Actions (Export / Import) placed neatly in the header -->
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('admin/export') ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Ekspor Semua
                    </a>
                    <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Impor Excel
                    </button>
                </div>
            </div>

            <form action="<?= base_url('admin/create') ?>" method="POST" class="flex flex-col sm:flex-row items-stretch gap-2.5">
                <?= csrf_field() ?>
                
                <!-- Slug Input Group -->
                <div class="flex-1 flex items-center bg-slate-50/80 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                    <span class="pl-3.5 pr-1 text-slate-400 text-xs sm:text-sm font-medium whitespace-nowrap select-none"><?= base_url() ?></span>
                    <input
                        type="text"
                        name="slug_url"
                        id="slug_url"
                        value="<?= old('slug_url') ?>"
                        placeholder="slug-kustom (kosongkan = otomatis)"
                        pattern="[a-zA-Z0-9_\-]*"
                        title="Slug hanya boleh berisi huruf, angka, dash (-), dan underscore (_)"
                        class="flex-1 py-2.5 pr-3 bg-transparent border-0 text-slate-800 placeholder-slate-400 focus:outline-none text-sm"
                    >
                </div>

                <!-- Qty Input Group -->
                <div class="flex items-center bg-slate-50/80 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 transition-all sm:w-28">
                    <span class="pl-3 text-xs text-slate-400 font-medium whitespace-nowrap">Jml:</span>
                    <input
                        type="number"
                        name="qty"
                        min="1"
                        max="500"
                        value="1"
                        placeholder="1"
                        title="Jumlah URL yang ingin dibuat massal"
                        class="w-full py-2.5 pr-3 pl-1 bg-transparent border-0 text-slate-800 font-semibold text-center focus:outline-none text-sm"
                    >
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 whitespace-nowrap"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat URL</span>
                </button>
            </form>
        </div>

        <!-- URL List -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 animate-fade-in" style="animation-delay: 0.1s;">
            <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Daftar Klien &amp; URL</h2>
                        <p class="text-xs text-slate-500 mt-0.5" id="url-count-text">Menampilkan <?= count($urls) ?> data (Halaman ini)</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3">
                    <!-- Filter Form -->
                    <form action="<?= base_url('admin') ?>" method="GET" class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-2">
                            <select name="limit" onchange="this.form.submit()" class="input-focus pl-3 pr-8 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-indigo-400 font-medium text-slate-700">
                                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 baris</option>
                                <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25 baris</option>
                                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 baris</option>
                                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100 baris</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="date" name="start_date" value="<?= esc($start_date) ?>" class="input-focus px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-600 focus:outline-none focus:bg-white focus:border-indigo-400">
                            <span class="text-slate-400 text-xs font-medium">s/d</span>
                            <input type="date" name="end_date" value="<?= esc($end_date) ?>" class="input-focus px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-600 focus:outline-none focus:bg-white focus:border-indigo-400">
                        </div>
                        <button type="submit" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-xl transition-colors" title="Terapkan Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                    </form>

                    <!-- Search Input -->
                    <div class="relative flex-1 sm:w-60">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="search-input" placeholder="Cari klien, slug..." class="input-focus block w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-400 transition-all">
                    </div>
                </div>
            </div>

            <div id="bulk-action-container">
                
                <?php if (!empty($urls)): ?>
                <!-- Bulk Actions Toolbar -->
                <div class="px-6 py-3 bg-slate-50/80 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi Massal:</span>
                        <span id="selected-count-badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-600 transition-all">
                            0 dipilih
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="submitBulk('export')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-slate-700 hover:text-indigo-600 bg-white border border-slate-300 hover:border-indigo-300 rounded-lg shadow-sm hover:shadow transition-all">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Ekspor
                        </button>
                        <button type="button" onclick="submitBulk('reset')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset
                        </button>
                        <button type="button" onclick="submitBulk('delete')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-lg shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </div>
                </div>
                <?php endif; ?>

            <?php if (empty($urls)): ?>
                <div class="px-6 pb-8 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm">Belum ada URL. Buat URL pertama Anda di atas.</p>
                </div>
            <?php else: ?>
                <!-- Mobile Select All Bar (visible on small screens) -->
                <div class="block sm:hidden px-4 py-2.5 bg-slate-100/70 border-b border-slate-200/60">
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer">
                        <input type="checkbox" id="select-all-mobile" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        <span>Pilih Semua di Halaman Ini</span>
                    </label>
                </div>

                <!-- Mobile Cards (visible on small screens) -->
                <div class="block sm:hidden p-4 space-y-3 bg-slate-50/50" id="mobile-card-container">
                    <?php foreach ($urls as $url): ?>
                        <div class="url-item bg-white rounded-xl p-4 border border-slate-200 shadow-sm" data-search="<?= esc(strtolower($url['slug_url'] . ' ' . $url['destination_url'] . ' ' . $url['client_username'])) ?>">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="ids[]" value="<?= $url['id'] ?>" class="row-checkbox w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                    <a href="<?= base_url($url['slug_url']) ?>" target="_blank" class="text-indigo-500 font-semibold text-sm hover:text-indigo-600 transition-colors">
                                        /<?= esc($url['slug_url']) ?>
                                    </a>
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="<?= base_url('admin/view/' . $url['id']) ?>" class="p-1.5 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="<?= base_url('admin/edit/' . $url['id']) ?>" class="p-1.5 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="<?= base_url('admin/reset/' . $url['id']) ?>" method="POST" onsubmit="return confirm('Yakin ingin mereset data klien ini? Data profil dan URL tujuan akan dikosongkan.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all" title="Reset Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="<?= base_url('admin/delete/' . $url['id']) ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus URL /<?= esc($url['slug_url']) ?> beserta akun kliennya?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Tujuan</span>
                                    <?php if (!empty($url['destination_url'])): ?>
                                        <a href="<?= esc($url['destination_url']) ?>" target="_blank" class="text-slate-600 hover:text-indigo-500 truncate max-w-[180px] transition-colors"><?= esc($url['destination_url']) ?></a>
                                    <?php else: ?>
                                        <span class="text-amber-500 font-medium">Belum diatur</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Status Klien</span>
                                    <?php if (isset($url['password_hash']) && $url['password_hash'] === 'UNCLAIMED'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Belum Diklaim
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-600 font-medium"><?= esc($url['client_username'] ?? '-') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Dibuat</span>
                                    <span class="text-slate-500"><?= date('d M Y', strtotime($url['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Desktop Table (hidden on small screens) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-t border-slate-100 bg-slate-50/50">
                                <th class="py-3 px-3.5 w-10 text-center"><input type="checkbox" id="select-all-desktop" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500"></th>
                                <th class="py-3 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Slug URL</th>
                                <th class="py-3 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Destination URL</th>
                                <th class="py-3 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Username Klien</th>
                                <th class="py-3 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Dibuat</th>
                                <th class="py-3 px-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm" id="desktop-table-body">
                            <?php foreach ($urls as $url): ?>
                                <tr class="url-item table-row-hover transition-colors" data-search="<?= esc(strtolower($url['slug_url'] . ' ' . $url['destination_url'] . ' ' . $url['client_username'])) ?>">
                                    <td class="py-3 px-3.5 text-center">
                                        <input type="checkbox" name="ids[]" value="<?= $url['id'] ?>" class="row-checkbox w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="<?= base_url($url['slug_url']) ?>" target="_blank" class="inline-flex items-center gap-1 text-indigo-500 font-semibold hover:text-indigo-600 transition-colors whitespace-nowrap">
                                            /<?= esc($url['slug_url']) ?>
                                            <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="py-3 px-3">
                                        <?php if (!empty($url['destination_url'])): ?>
                                            <a href="<?= esc($url['destination_url']) ?>" target="_blank" class="text-slate-600 hover:text-indigo-500 truncate block max-w-[160px] lg:max-w-xs transition-colors" title="<?= esc($url['destination_url']) ?>">
                                                <?= esc(strlen($url['destination_url']) > 32 ? substr($url['destination_url'], 0, 32) . '...' : $url['destination_url']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-600 text-xs font-medium rounded-md whitespace-nowrap">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                </svg>
                                                Belum diatur
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-3">
                                        <?php if (isset($url['password_hash']) && $url['password_hash'] === 'UNCLAIMED'): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-800 border border-red-200 whitespace-nowrap">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                                </svg>
                                                Belum Diklaim
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 text-slate-600 truncate max-w-[140px]">
                                                <div class="w-5 h-5 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <span class="truncate"><?= esc($url['client_username'] ?? '-') ?></span>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-3 text-xs text-slate-500 whitespace-nowrap">
                                        <?= date('d M Y', strtotime($url['created_at'])) ?>
                                    </td>
                                    <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="<?= base_url('admin/view/' . $url['id']) ?>" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('admin/edit/' . $url['id']) ?>" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="<?= base_url('admin/reset/' . $url['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin mereset data klien ini? Data profil dan URL tujuan akan dikosongkan.');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all" title="Reset Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="<?= base_url('admin/delete/' . $url['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus URL /<?= esc($url['slug_url']) ?> beserta akun kliennya?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus URL">
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

            </div> <!-- End bulk action container -->
            
            <!-- Hidden form for bulk actions to avoid nested forms issue -->
            <form id="hidden-bulk-form" action="<?= base_url('admin/bulk-action') ?>" method="POST" class="hidden">
                <?= csrf_field() ?>
                <input type="hidden" name="action" id="hidden-bulk-action">
                <div id="hidden-bulk-ids"></div>
            </form>
            
            <?php if (!empty($urls) && isset($pager)): ?>
            <div class="p-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-slate-500">
                    Navigasi Halaman
                </div>
                <div class="pagination-container">
                    <?= $pager->links() ?>
                </div>
            </div>
            
            <style>
                .pagination-container .pagination {
                    display: flex;
                    gap: 0.25rem;
                    list-style: none;
                    margin: 0;
                    padding: 0;
                }
                .pagination-container .pagination li a,
                .pagination-container .pagination li span {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 2rem;
                    height: 2rem;
                    border-radius: 0.5rem;
                    font-size: 0.875rem;
                    font-weight: 500;
                    background-color: white;
                    border: 1px solid #e2e8f0;
                    color: #64748b;
                    transition: all 0.2s;
                }
                .pagination-container .pagination li a:hover {
                    background-color: #f8fafc;
                    border-color: #cbd5e1;
                    color: #0f172a;
                }
                .pagination-container .pagination li.active a,
                .pagination-container .pagination li.active span {
                    background-color: #6366f1;
                    border-color: #6366f1;
                    color: white;
                }
            </style>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-dismiss alerts after 8 seconds (longer for credentials display)
        document.querySelectorAll('[id^="alert-"]').forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.3s, transform 0.3s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() { alert.remove(); }, 300);
            }, 8000);
        });

        // Auto-lowercase slug input
        document.getElementById('slug_url').addEventListener('input', function(e) {
            e.target.value = e.target.value.toLowerCase().replace(/[^a-z0-9_\-]/g, '');
        });

        // Live Search Filtering
        const searchInput = document.getElementById('search-input');
        const countText = document.getElementById('url-count-text');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                const items = document.querySelectorAll('.url-item');
                let visibleCount = 0;
                
                items.forEach(item => {
                    const searchData = item.getAttribute('data-search');
                    if (searchData.includes(term)) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Since items are duplicated (desktop vs mobile views), visibleCount will be 2x the actual count
                // Let's count only desktop rows or mobile cards based on screen size, or just divide by 2
                const actualCount = visibleCount / 2;
                
                if (countText) {
                    countText.textContent = `Menampilkan ${actualCount} data`;
                }
            });
        }
        
        // Select All Checkboxes & Counter Sync
        const selectAllDesktop = document.getElementById('select-all-desktop');
        const selectAllMobile = document.getElementById('select-all-mobile');
        const badgeCounter = document.getElementById('selected-count-badge');
        
        function updateSelectedCounter() {
            const checkedValues = new Set();
            document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                const item = cb.closest('.url-item');
                if (item && item.style.display !== 'none') {
                    checkedValues.add(cb.value);
                }
            });
            const count = checkedValues.size;
            if (badgeCounter) {
                badgeCounter.textContent = `${count} dipilih`;
                if (count > 0) {
                    badgeCounter.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-600 text-white shadow-sm transition-all';
                } else {
                    badgeCounter.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-600 transition-all';
                }
            }
        }
        
        function handleSelectAll(isChecked) {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                const item = cb.closest('.url-item');
                if (item && item.style.display !== 'none') {
                    cb.checked = isChecked;
                }
            });
            if (selectAllDesktop) selectAllDesktop.checked = isChecked;
            if (selectAllMobile) selectAllMobile.checked = isChecked;
            updateSelectedCounter();
        }
        
        if (selectAllDesktop) {
            selectAllDesktop.addEventListener('change', function(e) {
                handleSelectAll(e.target.checked);
            });
        }
        if (selectAllMobile) {
            selectAllMobile.addEventListener('change', function(e) {
                handleSelectAll(e.target.checked);
            });
        }
        
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const val = this.value;
                document.querySelectorAll(`.row-checkbox[value="${val}"]`).forEach(other => {
                    other.checked = this.checked;
                });
                updateSelectedCounter();
            });
        });
        
        // Bulk Action via Hidden Form
        function submitBulk(actionType) {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) {
                alert('Silakan pilih minimal satu data terlebih dahulu!');
                return;
            }
            
            if (actionType === 'delete') {
                const answer = prompt('PERINGATAN: Anda akan MENGHAPUS PERMANEN ' + checked.length + ' data yang dipilih.\n\nKetik "HAPUS" (dengan huruf kapital) untuk mengonfirmasi:');
                if (answer !== 'HAPUS') {
                    if (answer !== null) alert('Konfirmasi salah. Aksi dibatalkan.');
                    return;
                }
            } else if (actionType === 'reset') {
                const answer = prompt('PERINGATAN: Anda akan MERESET ' + checked.length + ' data yang dipilih (menghapus URL tujuan dan status klien).\n\nKetik "RESET" (dengan huruf kapital) untuk mengonfirmasi:');
                if (answer !== 'RESET') {
                    if (answer !== null) alert('Konfirmasi salah. Aksi dibatalkan.');
                    return;
                }
            }
            
            const hiddenForm = document.getElementById('hidden-bulk-form');
            const idsContainer = document.getElementById('hidden-bulk-ids');
            document.getElementById('hidden-bulk-action').value = actionType;
            
            idsContainer.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                idsContainer.appendChild(input);
            });
            
            hiddenForm.submit();
        }
    </script>
    
    <!-- Import Excel Modal -->
    <div id="import-modal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>

            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl animate-slide-up sm:my-8">
                <div class="flex items-start justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800" id="modal-title">Impor Data Excel</h3>
                    <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="<?= base_url('admin/import') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-5 space-y-3">
                        <p class="text-sm text-slate-500">Unggah file Excel Anda (Format harus mengikuti template hasil Ekspor Excel).</p>
                        <input type="file" name="csv_file" accept=".xlsx, .xls" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-colors">
                    </div>
                    
                    <div class="flex gap-3 justify-end mt-8">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-xl hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm shadow-indigo-200">
                            Impor Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Generator View Modal -->
    <?= view('partials/generator_modal') ?>

</body>
</html>
