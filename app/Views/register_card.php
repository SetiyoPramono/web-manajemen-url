<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Kartu — ReviewBoost</title>
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
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <div class="w-full max-w-lg relative z-10 animate-fade-in py-8">
        <!-- Logo / Brand -->
        <div class="text-center mb-8 animate-slide-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-500 rounded-2xl shadow-lg shadow-indigo-200 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Aktivasi Kartu Anda</h1>
            <p class="text-slate-500 mt-2 text-sm">Lengkapi data berikut untuk mengaktifkan kartu dan mengatur tujuan redirect Google Maps Anda.</p>
        </div>

        <!-- Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 animate-slide-up">
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

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-8 animate-slide-up" style="animation-delay: 0.1s;">
            <form action="<?= base_url('register-card/' . esc($slug)) ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Username -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="username" class="block text-sm font-semibold text-slate-700">Username Login</label>
                        <input type="text" name="username" id="username" value="<?= old('username') ?>" required
                            class="input-focus block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:outline-none transition-all sm:text-sm"
                            placeholder="Pilih username unik">
                        <p class="text-xs text-slate-400">Minimal 3 karakter.</p>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" id="password" required minlength="6"
                            class="input-focus block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:outline-none transition-all sm:text-sm"
                            placeholder="Buat password (min. 6 karakter)">
                    </div>

                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label for="nama" class="block text-sm font-semibold text-slate-700">Nama Lengkap / Bisnis</label>
                        <input type="text" name="nama" id="nama" value="<?= old('nama') ?>" required
                            class="input-focus block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:outline-none transition-all sm:text-sm"
                            placeholder="Contoh: Toko Kopi Senja">
                    </div>

                    <!-- WA Number -->
                    <div class="space-y-1.5">
                        <label for="nomor_wa" class="block text-sm font-semibold text-slate-700">Nomor WhatsApp</label>
                        <input type="text" name="nomor_wa" id="nomor_wa" value="<?= old('nomor_wa') ?>" required
                            class="input-focus block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:outline-none transition-all sm:text-sm"
                            placeholder="08123456789">
                    </div>

                    <!-- Maps URL -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="alamat_maps" class="block text-sm font-semibold text-slate-700">URL Tujuan Google Maps</label>
                        <input type="url" name="alamat_maps" id="alamat_maps" value="<?= old('alamat_maps') ?>" required
                            class="input-focus block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:outline-none transition-all sm:text-sm"
                            placeholder="https://maps.app.goo.gl/...">
                        <p class="text-xs text-slate-400 mt-1">Kartu ini akan otomatis diarahkan ke URL yang Anda masukkan di atas.</p>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 transition-all duration-200">
                        <span>Aktifkan Kartu & Simpan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-6 animate-slide-up" style="animation-delay: 0.2s;">
            <p class="text-sm text-slate-500">
                Sudah punya akun? <a href="<?= base_url('login') ?>" class="font-medium text-indigo-500 hover:text-indigo-600 transition-colors">Login di sini</a>
            </p>
        </div>
    </div>

</body>
</html>
