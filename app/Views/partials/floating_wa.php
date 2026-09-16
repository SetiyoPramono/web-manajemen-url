<?php
$settingModel = new \App\Models\SettingModel();
$isEnabled = $settingModel->getValue('cs_wa_enabled', '0');
$waNumber = $settingModel->getValue('cs_wa_number', '');

if ($isEnabled == '1' && !empty($waNumber)):
?>
    <!-- CS Floating Button -->
    <div class="fixed bottom-6 right-6 z-[100] font-sans">
        <!-- Floating Button -->
        <button id="wa-floating-btn" class="w-14 h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-emerald-300 group">
            <svg class="w-7 h-7 group-hover:hidden transition-all duration-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <svg class="w-6 h-6 hidden group-hover:block transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Chat Form Pop-up -->
        <div id="wa-chat-form" class="absolute bottom-16 right-0 mb-4 w-72 sm:w-80 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden transform scale-0 opacity-0 origin-bottom-right transition-all duration-300">
            
            <!-- Header -->
            <div class="bg-emerald-500 p-4 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold leading-tight">Customer Support</h3>
                        <p class="text-emerald-100 text-xs mt-0.5">Ada kendala? Hubungi kami</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-5">
                <div class="mb-4">
                    <label for="wa-name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Anda</label>
                    <input type="text" id="wa-name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 rounded-lg text-sm text-slate-700 transition-all outline-none" placeholder="Masukkan nama..." value="<?= esc(session()->get('username') ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label for="wa-message" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Pesan / Kendala</label>
                    <textarea id="wa-message" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 rounded-lg text-sm text-slate-700 transition-all outline-none resize-none" placeholder="Tuliskan kendala Anda..."></textarea>
                </div>
                
                <button id="wa-send-btn" class="w-full flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim Pesan
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const waBtn = document.getElementById('wa-floating-btn');
            const waForm = document.getElementById('wa-chat-form');
            const sendBtn = document.getElementById('wa-send-btn');
            
            // Toggle form visibility
            waBtn.addEventListener('click', function() {
                if (waForm.classList.contains('scale-0')) {
                    waForm.classList.remove('scale-0', 'opacity-0');
                    waForm.classList.add('scale-100', 'opacity-100');
                    document.getElementById('wa-message').focus();
                } else {
                    waForm.classList.remove('scale-100', 'opacity-100');
                    waForm.classList.add('scale-0', 'opacity-0');
                }
            });

            // Handle send button
            sendBtn.addEventListener('click', function() {
                const name = document.getElementById('wa-name').value.trim();
                const message = document.getElementById('wa-message').value.trim();
                const targetNumber = '<?= esc($waNumber) ?>';
                
                if (!name || !message) {
                    alert('Harap isi nama dan pesan Anda.');
                    return;
                }

                const text = `Halo Admin, saya *${name}*.\n\n${message}`;
                const encodedText = encodeURIComponent(text);
                const waUrl = `https://wa.me/${targetNumber}?text=${encodedText}`;
                
                window.open(waUrl, '_blank');
                
                // Hide form after sending
                waForm.classList.remove('scale-100', 'opacity-100');
                waForm.classList.add('scale-0', 'opacity-0');
            });
        });
    </script>
<?php endif; ?>
