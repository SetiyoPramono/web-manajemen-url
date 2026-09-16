<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard — ReviewBoost</title>
    <link rel="icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <meta name="description" content="Dashboard klien untuk mengelola layanan ulasan.">
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
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200/60 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9">
                        <img src="<?= base_url('logo.png') ?>" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">ReviewBoost</h1>
                        <p class="text-xs text-slate-400 -mt-0.5">Client Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 text-sm text-slate-500">
                        <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="font-medium text-slate-700"><?= esc(session()->get('username')) ?></span>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-red-500 hover:text-white bg-red-50 hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div id="alert-success" class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 animate-slide-up">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
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

        <!-- Page Header -->
        <div class="mb-6 animate-fade-in">
            <h2 class="text-xl font-bold text-slate-800">URL Anda</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola URL tujuan redirect untuk setiap slug yang diberikan kepada Anda.</p>
        </div>

        <?php if (empty($urls)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-8 text-center animate-fade-in">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <p class="text-slate-500 text-sm">Belum ada URL yang diberikan kepada Anda.</p>
                <p class="text-slate-400 text-xs mt-1">Hubungi admin untuk mendapatkan URL.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($urls as $index => $url): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden animate-slide-up" style="animation-delay: <?= $index * 0.05 ?>s;">
                        <form action="<?= base_url('client/update') ?>" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="url_id" value="<?= $url['id'] ?>">

                            <!-- Card Header -->
                            <div class="px-6 pt-5 pb-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-800">/<?= esc($url['slug_url']) ?></h3>
                                        <p class="text-xs text-slate-400 mt-0.5"><?= base_url($url['slug_url']) ?></p>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <?php if (!empty($url['destination_url'])): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-medium rounded-lg">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-medium rounded-lg">
                                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                                        Belum diatur
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Card Body -->
                            <div class="px-6 pb-5">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="destination_url_<?= $url['id'] ?>" class="block text-sm font-medium text-slate-700">URL Tujuan (Google Maps Review Link)</label>
                                    <button type="button" class="open-generator-btn text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-md transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Gunakan Generator
                                    </button>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <input
                                        type="url"
                                        name="destination_url"
                                        id="destination_url_<?= $url['id'] ?>"
                                        value="<?= esc($url['destination_url'] ?? '') ?>"
                                        placeholder="https://g.page/r/.../review"
                                        class="input-focus flex-1 px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-400 focus:bg-white transition-all duration-200"
                                    >
                                    <button
                                        type="submit"
                                        class="px-6 py-3 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-indigo-200 hover:shadow-md hover:shadow-indigo-200 transition-all duration-200 whitespace-nowrap"
                                    >
                                        <span class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Simpan Perubahan
                                        </span>
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 mt-3">
                                    <strong class="text-indigo-500">💡 Tips:</strong> Untuk langsung memunculkan pop-up rating bintang 5, gunakan link <b>"Minta Ulasan" (Ask for reviews)</b> dari dashboard Google Profil Bisnis Anda.
                                </p>
                                <p class="text-xs text-slate-400 mt-2 ml-1">Masukkan URL lengkap beserta https:// — Kosongkan jika ingin menonaktifkan redirect.</p>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('[id^="alert-"]').forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.3s, transform 0.3s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() { alert.remove(); }, 300);
            }, 5000);
        });
    </script>
    <!-- Include Floating WA Button -->
    <?= view('partials/floating_wa') ?>

    <!-- Include Generator Modal -->
    <?= view('partials/generator_modal') ?>

</body>
</html>
