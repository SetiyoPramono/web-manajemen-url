<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Layanan</title>
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
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .star-svg {
            transition: transform 0.2s, color 0.2s;
        }
        .star-svg:hover {
            transform: scale(1.1);
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center animate-fade-in">
        
        <div class="mb-6 flex justify-center">
            <div class="w-16 h-16 bg-brand-50 rounded-full flex items-center justify-center text-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Bagaimana pengalaman Anda?</h2>
        <p class="text-gray-500 mb-8 text-sm">Ketuk bintang di bawah ini untuk menilai pengalaman Anda bersama kami.</p>

        <!-- Stars Container -->
        <div class="flex justify-center space-x-2 mb-8" id="stars-container" onmouseleave="resetStars()">
            <?php for($i = 1; $i <= 5; $i++): ?>
                <button 
                    type="button" 
                    class="focus:outline-none star-btn" 
                    data-value="<?= $i ?>"
                    onmouseover="hoverStars(<?= $i ?>)"
                    onclick="submitRating(<?= $i ?>)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 star-svg" fill="currentColor" viewBox="0 0 24 24" id="star-<?= $i ?>">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </button>
            <?php endfor; ?>
        </div>

        <!-- Loading State (Hidden by default) -->
        <div id="loading-state" class="hidden">
            <div class="flex flex-col items-center justify-center">
                <svg class="animate-spin h-8 w-8 text-brand-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-500 font-medium" id="loading-text">Mengarahkan...</p>
            </div>
        </div>
    </div>

    <!-- Data from PHP -->
    <script>
        const destinationUrl = "<?= esc($destination_url, 'js') ?>";
        let waNumber = "<?= esc($nomor_wa, 'js') ?>";
        
        // Format WA Number if not empty (replace leading 0 with 62)
        if(waNumber) {
            waNumber = waNumber.replace(/[^0-9]/g, ''); // strip non-numeric
            if(waNumber.startsWith('0')) {
                waNumber = '62' + waNumber.substring(1);
            }
        }
        
        const waMessage = encodeURIComponent("Halo, saya ingin memberikan masukan/ulasan mengenai layanan Anda.");

        function hoverStars(value) {
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById(`star-${i}`);
                if (i <= value) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            }
        }

        function resetStars() {
            // Revert to all gray when not hovering
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById(`star-${i}`);
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        }

        function submitRating(rating) {
            // Disable buttons
            const btns = document.querySelectorAll('.star-btn');
            btns.forEach(btn => btn.disabled = true);

            // Fix star colors to the selected rating
            hoverStars(rating);
            // Remove mouseleave event so it doesn't reset
            document.getElementById('stars-container').onmouseleave = null;

            // Show loading
            document.getElementById('stars-container').classList.add('hidden');
            document.getElementById('loading-state').classList.remove('hidden');

            const loadingText = document.getElementById('loading-text');

            setTimeout(() => {
                if (rating <= 3) {
                    if (waNumber) {
                        loadingText.innerText = "Membuka WhatsApp...";
                        window.location.href = `https://wa.me/${waNumber}?text=${waMessage}`;
                    } else {
                        // Fallback if WA number is empty
                        loadingText.innerText = "Mengarahkan ke tujuan...";
                        window.location.href = destinationUrl;
                    }
                } else {
                    loadingText.innerText = "Mengarahkan ke tujuan...";
                    window.location.href = destinationUrl;
                }
            }, 800);
        }
    </script>
</body>
</html>
