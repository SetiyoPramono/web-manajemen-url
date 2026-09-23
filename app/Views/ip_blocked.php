<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Diblokir — ReviewBoost</title>
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
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-up { animation: slideUp 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-red-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-amber-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <div class="w-full max-w-lg relative z-10 animate-fade-in py-8">

        <!-- Logo / Brand -->
        <div class="text-center mb-6 animate-slide-up">
            <div class="inline-flex items-center justify-center w-20 h-20 mb-2">
                <img src="<?= base_url('logo.png') ?>" alt="ReviewBoost Logo" class="w-full h-full object-contain relative z-10 drop-shadow-md">
            </div>
            <h1 class="text-xl font-bold text-slate-800">ReviewBoost Security</h1>
        </div>

        <!-- Main Blocked Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 sm:p-8 animate-slide-up" style="animation-delay: 0.1s;">
            
            <!-- Warning Badge & Icon -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Akses Login Diblokir</h2>
                <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">
                    Sistem keamanan kami mendeteksi aktivitas login yang gagal berulang kali dari koneksi Anda.
                </p>
            </div>

            <!-- Details Box -->
            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 sm:p-5 mb-6 space-y-3 text-xs sm:text-sm">
                
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Alamat IP Anda</span>
                    <span class="font-mono font-semibold text-slate-700 bg-white px-2.5 py-0.5 rounded-lg border border-slate-200">
                        <?= esc($ip) ?>
                    </span>
                </div>

                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Status Pemblokiran</span>
                    <?php if ((int)($blocked['is_permanent'] ?? 0) === 1): ?>
                        <span class="inline-flex items-center gap-1 font-semibold text-red-700 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full text-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Blokir Permanen
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full text-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Diblokir Sementara (6 Jam)
                        </span>
                    <?php endif; ?>
                </div>

                <div class="flex items-start justify-between pb-2.5 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium flex-shrink-0 mr-2">Alasan</span>
                    <span class="text-slate-700 text-right font-medium">
                        <?= esc($blocked['reason'] ?? '6x percobaan login gagal berturut-turut') ?>
                    </span>
                </div>

                <?php if ((int)($blocked['is_permanent'] ?? 0) !== 1 && !empty($blocked['blocked_until'])): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Terbuka Otomatis Pada</span>
                        <div class="text-right">
                            <div class="font-semibold text-slate-800">
                                <?= date('d M Y, H:i', strtotime($blocked['blocked_until'])) ?> WIB
                            </div>
                            <span class="text-[11px] text-slate-400">Blokir otomatis terbuka dalam 6 jam</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Instruction -->
            <div class="mb-6 p-4 bg-indigo-50/60 border border-indigo-100 rounded-2xl text-xs sm:text-sm text-slate-600 space-y-2">
                <div class="font-semibold text-indigo-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Langkah Membuka Blokir:</span>
                </div>
                <p class="leading-relaxed text-slate-600">
                    Jika Anda adalah pemilik akun yang sah dan lupa kata sandi atau ingin membuka blokir segera tanpa menunggu 6 jam, silakan hubungi Customer Support / Administrator melalui tombol WhatsApp di bawah ini.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="<?= esc($wa_url) ?>" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-xl hover:shadow-emerald-600/30 transition-all duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>Hubungi Admin via WhatsApp</span>
                </a>

                <a href="<?= base_url('login') ?>" class="w-full flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Coba Masuk Kembali</span>
                </a>
            </div>

        </div>

        <div class="text-center mt-6 animate-slide-up" style="animation-delay: 0.2s;">
            <p class="text-xs text-slate-400">
                &copy; <?= date('Y') ?> ReviewBoost — Perlindungan Otomatis Terhadap Percobaan Brute Force
            </p>
        </div>

    </div>

</body>
</html>
