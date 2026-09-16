<!-- Generator Modal Backdrop -->
<div id="generator-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] hidden items-center justify-center opacity-0 transition-opacity duration-300 font-sans">
    
    <!-- Modal Content -->
    <div id="generator-modal" class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 transform scale-95 opacity-0 transition-all duration-300">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                Generator Tautan Ulasan
            </h3>
            <button type="button" id="close-generator-btn" class="text-slate-400 hover:text-slate-600 focus:outline-none p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-sm text-slate-500 mb-4">Paste link Google Maps standar (misal: <code>maps.app.goo.gl/...</code> atau <code>google.com/maps/...</code>) dan kami akan mencoba men-generate tautan langsung ke pop-up ulasan.</p>
            
            <div class="mb-4">
                <label for="generator-input" class="block text-sm font-medium text-slate-700 mb-1.5">Link Google Maps</label>
                <div class="flex gap-2">
                    <input type="url" id="generator-input" placeholder="https://maps.app.goo.gl/..." class="input-focus flex-1 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-400 focus:bg-white transition-all">
                    <button type="button" id="generate-btn" class="px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-colors focus:outline-none flex items-center justify-center min-w-[100px]">
                        <span id="generate-text">Generate</span>
                        <svg id="generate-loader" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Error message -->
            <div id="generator-error" class="hidden mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100"></div>

            <!-- Result -->
            <div id="generator-result" class="hidden mt-6">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Hasil Tautan Ulasan</label>
                <div class="relative">
                    <input type="text" id="generator-output" readonly class="w-full pl-3 pr-24 py-2.5 bg-indigo-50 border border-indigo-200 rounded-xl text-sm text-indigo-800 focus:outline-none">
                    <button type="button" id="copy-result-btn" class="absolute right-1.5 top-1.5 bottom-1.5 px-3 bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        Copy
                    </button>
                </div>
                <p id="copy-feedback" class="text-xs text-emerald-600 mt-1.5 hidden font-medium text-right">Berhasil disalin!</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backdrop = document.getElementById('generator-modal-backdrop');
    const modal = document.getElementById('generator-modal');
    const openBtns = document.querySelectorAll('.open-generator-btn'); // Any element with this class will open the modal
    const closeBtn = document.getElementById('close-generator-btn');
    
    const input = document.getElementById('generator-input');
    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('generate-text');
    const btnLoader = document.getElementById('generate-loader');
    
    const resultBox = document.getElementById('generator-result');
    const output = document.getElementById('generator-output');
    const copyBtn = document.getElementById('copy-result-btn');
    const copyFeedback = document.getElementById('copy-feedback');
    const errorBox = document.getElementById('generator-error');

    function openModal() {
        backdrop.classList.remove('hidden');
        backdrop.classList.add('flex');
        // Small delay to allow display:flex to apply before transition
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            modal.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeModal() {
        backdrop.classList.add('opacity-0');
        modal.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            backdrop.classList.add('hidden');
            backdrop.classList.remove('flex');
            // reset state
            input.value = '';
            resultBox.classList.add('hidden');
            errorBox.classList.add('hidden');
        }, 300);
    }

    openBtns.forEach(btn => btn.addEventListener('click', openModal));
    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) closeModal();
    });

    generateBtn.addEventListener('click', async () => {
        const url = input.value.trim();
        if (!url) return;

        // UI Loading state
        btnText.classList.add('hidden');
        btnLoader.classList.remove('hidden');
        generateBtn.disabled = true;
        resultBox.classList.add('hidden');
        errorBox.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('url', url);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            const res = await fetch('<?= base_url("api/generate-review-link") ?>', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            
            if (data.status === 'success') {
                output.value = data.review_link;
                resultBox.classList.remove('hidden');
            } else {
                errorBox.textContent = data.message;
                errorBox.classList.remove('hidden');
            }
        } catch (e) {
            errorBox.textContent = 'Terjadi kesalahan jaringan.';
            errorBox.classList.remove('hidden');
        } finally {
            // Restore UI state
            btnText.classList.remove('hidden');
            btnLoader.classList.add('hidden');
            generateBtn.disabled = false;
        }
    });

    copyBtn.addEventListener('click', () => {
        output.select();
        document.execCommand('copy');
        copyFeedback.classList.remove('hidden');
        setTimeout(() => copyFeedback.classList.add('hidden'), 2000);
        
        // If there's an active destination URL input (in client dashboard), we can optionally auto-fill it
        const activeUrlInput = document.querySelector('input[name="destination_url"]');
        if(activeUrlInput && confirm("Ingin langsung menempelkan (paste) tautan ini ke kolom URL Tujuan Anda?")) {
            activeUrlInput.value = output.value;
            closeModal();
            // Scroll to the input
            activeUrlInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // add highlight effect
            activeUrlInput.classList.add('ring-4', 'ring-emerald-300');
            setTimeout(() => activeUrlInput.classList.remove('ring-4', 'ring-emerald-300'), 1500);
        }
    });
});
</script>
