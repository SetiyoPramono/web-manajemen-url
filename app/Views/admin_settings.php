<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan — ReviewBoost</title>
    <link rel="icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <meta name="description" content="Pengaturan aplikasi ReviewBoost.">
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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="<?= base_url('admin') ?>" class="flex items-center justify-center w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors" title="Kembali">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">Pengaturan</h1>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div id="alert-success" class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 animate-slide-up">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
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

        <form action="<?= base_url('admin/settings/update') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Floating Button WhatsApp Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6 animate-fade-in">
                <div class="flex items-center gap-2 mb-5">
                    <div class="flex items-center justify-center w-9 h-9">
                        <img src="<?= base_url('logo.png') ?>" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-lg font-semibold text-slate-800">Pengaturan Customer Support (WhatsApp)</h2>
                </div>

                <div class="space-y-4">
                    <!-- Toggle Aktif/Nonaktif -->
                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                        <div>
                            <span class="block text-sm font-medium text-slate-700">Tampilkan Floating Button CS</span>
                            <span class="block text-xs text-slate-400 mt-1">Muncul di dashboard klien untuk bantuan/kendala.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="cs_wa_enabled" value="1" class="sr-only peer" <?= ($settings['cs_wa_enabled'] == '1') ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <!-- Daftar Nomor WA CS (Rotator) -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800">Daftar Nomor WhatsApp CS (Rotator Otomatis)</label>
                                <p class="text-xs text-slate-500 mt-0.5">Pesan akan dibagi secara bergantian (Round-Robin) ke setiap nomor yang berstatus <strong>Aktif</strong>.</p>
                            </div>
                            <button
                                type="button"
                                id="btn-add-cs"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition-all duration-200 focus:outline-none"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah CS
                            </button>
                        </div>

                        <!-- CS List Container -->
                        <div id="cs-list" class="space-y-3">
                            <?php 
                            $csList = !empty($settings['cs_wa_numbers']) ? $settings['cs_wa_numbers'] : [
                                ['name' => 'CS 1', 'number' => $settings['cs_wa_number'] ?? '', 'is_active' => 1]
                            ];
                            ?>
                            <?php foreach ($csList as $idx => $cs): ?>
                                <div class="cs-row bg-slate-50/80 border border-slate-200 rounded-xl p-3.5 flex flex-col md:flex-row md:items-center gap-3 transition-all hover:border-slate-300">
                                    <!-- Index Badge -->
                                    <div class="flex items-center justify-between md:justify-start gap-2">
                                        <span class="cs-badge w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            <?= $idx + 1 ?>
                                        </span>
                                        <span class="text-xs font-medium text-slate-400 md:hidden">Customer Support #<span class="cs-num"><?= $idx + 1 ?></span></span>
                                    </div>

                                    <!-- Nama / Label CS -->
                                    <div class="flex-1 min-w-0">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Nama / Label CS</label>
                                        <input
                                            type="text"
                                            name="cs[<?= $idx ?>][name]"
                                            value="<?= esc($cs['name'] ?? '') ?>"
                                            placeholder="cth: CS Rina / Shift Pagi"
                                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-400 input-focus transition-all"
                                        >
                                    </div>

                                    <!-- Nomor WhatsApp -->
                                    <div class="flex-1 min-w-0">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Nomor WhatsApp</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                            </div>
                                            <input
                                                type="tel"
                                                name="cs[<?= $idx ?>][number]"
                                                value="<?= esc($cs['number'] ?? '') ?>"
                                                placeholder="08xxxxxxxxxx"
                                                class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-400 input-focus transition-all"
                                            >
                                        </div>
                                    </div>

                                    <!-- Status Toggle (Aktif / Cuti) -->
                                    <div class="flex items-center md:flex-col md:items-start justify-between min-w-[90px] pt-1 md:pt-0">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                                        <div class="flex items-center gap-2">
                                            <input type="hidden" name="cs[<?= $idx ?>][is_active]" value="0">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    name="cs[<?= $idx ?>][is_active]"
                                                    value="1"
                                                    class="sr-only peer cs-toggle"
                                                    <?= (!isset($cs['is_active']) || $cs['is_active'] == 1) ? 'checked' : '' ?>
                                                    onchange="updateToggleLabel(this)"
                                                >
                                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                            </label>
                                            <span class="cs-status-text text-xs font-medium <?= (!isset($cs['is_active']) || $cs['is_active'] == 1) ? 'text-emerald-600' : 'text-slate-400' ?>">
                                                <?= (!isset($cs['is_active']) || $cs['is_active'] == 1) ? 'Aktif' : 'Cuti' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Tombol Hapus Baris -->
                                    <div class="flex items-end justify-end md:self-end pt-1 md:pt-0 pb-0.5">
                                        <button
                                            type="button"
                                            class="btn-delete-cs p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus CS ini"
                                            onclick="deleteCsRow(this)"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 animate-slide-up" style="animation-delay: 0.15s;">
                <button
                    type="submit"
                    class="flex-1 py-3 px-6 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm shadow-indigo-200 hover:shadow-md hover:shadow-indigo-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pengaturan
                    </span>
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-dismiss alerts
        document.querySelectorAll('[id^="alert-"]').forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.3s, transform 0.3s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() { alert.remove(); }, 300);
            }, 5000);
        });

        // Toggle Status Label (Aktif / Cuti)
        function updateToggleLabel(checkbox) {
            const row = checkbox.closest('.cs-row');
            const statusText = row.querySelector('.cs-status-text');
            if (checkbox.checked) {
                statusText.innerText = 'Aktif';
                statusText.classList.remove('text-slate-400');
                statusText.classList.add('text-emerald-600');
            } else {
                statusText.innerText = 'Cuti';
                statusText.classList.remove('text-emerald-600');
                statusText.classList.add('text-slate-400');
            }
        }

        // Reindex rows when a row is deleted or added
        function reindexCsRows() {
            const rows = document.querySelectorAll('.cs-row');
            rows.forEach((row, idx) => {
                const badge = row.querySelector('.cs-badge');
                if (badge) badge.innerText = idx + 1;
                const num = row.querySelector('.cs-num');
                if (num) num.innerText = idx + 1;

                const nameInput = row.querySelector('input[name*="[name]"]');
                if (nameInput) nameInput.name = `cs[${idx}][name]`;

                const numberInput = row.querySelector('input[name*="[number]"]');
                if (numberInput) numberInput.name = `cs[${idx}][number]`;

                const hiddenActive = row.querySelector('input[type="hidden"][name*="[is_active]"]');
                if (hiddenActive) hiddenActive.name = `cs[${idx}][is_active]`;

                const checkActive = row.querySelector('input[type="checkbox"][name*="[is_active]"]');
                if (checkActive) checkActive.name = `cs[${idx}][is_active]`;
            });
        }

        // Delete a CS row
        function deleteCsRow(btn) {
            const rows = document.querySelectorAll('.cs-row');
            if (rows.length <= 1) {
                // Clear inputs if only 1 row remains
                const row = rows[0];
                const nameInp = row.querySelector('input[name*="[name]"]');
                const numInp = row.querySelector('input[name*="[number]"]');
                if (nameInp) nameInp.value = '';
                if (numInp) numInp.value = '';
                return;
            }
            const row = btn.closest('.cs-row');
            row.style.transition = 'opacity 0.2s, transform 0.2s';
            row.style.opacity = '0';
            row.style.transform = 'scale(0.95)';
            setTimeout(() => {
                row.remove();
                reindexCsRows();
            }, 200);
        }

        // Add new CS row
        document.getElementById('btn-add-cs').addEventListener('click', function() {
            const container = document.getElementById('cs-list');
            const rowCount = container.querySelectorAll('.cs-row').length;
            const newIndex = rowCount;
            const csNumber = rowCount + 1;

            const div = document.createElement('div');
            div.className = 'cs-row bg-slate-50/80 border border-slate-200 rounded-xl p-3.5 flex flex-col md:flex-row md:items-center gap-3 transition-all hover:border-slate-300 animate-slide-up';
            div.innerHTML = `
                <div class="flex items-center justify-between md:justify-start gap-2">
                    <span class="cs-badge w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                        ${csNumber}
                    </span>
                    <span class="text-xs font-medium text-slate-400 md:hidden">Customer Support #<span class="cs-num">${csNumber}</span></span>
                </div>

                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Nama / Label CS</label>
                    <input
                        type="text"
                        name="cs[${newIndex}][name]"
                        value="CS ${csNumber}"
                        placeholder="cth: CS Rina / Shift Pagi"
                        class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-400 input-focus transition-all"
                    >
                </div>

                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <input
                            type="tel"
                            name="cs[${newIndex}][number]"
                            placeholder="08xxxxxxxxxx"
                            class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-400 input-focus transition-all"
                        >
                    </div>
                </div>

                <div class="flex items-center md:flex-col md:items-start justify-between min-w-[90px] pt-1 md:pt-0">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="cs[${newIndex}][is_active]" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                name="cs[${newIndex}][is_active]"
                                value="1"
                                class="sr-only peer cs-toggle"
                                checked
                                onchange="updateToggleLabel(this)"
                            >
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                        <span class="cs-status-text text-xs font-medium text-emerald-600">
                            Aktif
                        </span>
                    </div>
                </div>

                <div class="flex items-end justify-end md:self-end pt-1 md:pt-0 pb-0.5">
                    <button
                        type="button"
                        class="btn-delete-cs p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                        title="Hapus CS ini"
                        onclick="deleteCsRow(this)"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(div);
            div.querySelector('input[type="tel"]').focus();
        });
    </script>
</body>
</html>
