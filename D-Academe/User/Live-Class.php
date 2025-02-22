<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Live Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <br>
        <br>
        <br>
        <br>
        
        <!-- Main Card -->
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-green-600 to-blue-500 p-8 text-center">
                <h1 class="text-4xl font-bold text-white mb-4">
                    <i class="fas fa-video mr-3"></i>Live Class
                </h1>
                <p class="text-indigo-100 text-lg">Enter your class details to join the live session</p>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <!-- URL Input Section -->
                <div id="urlSection">
                    <div class="mb-8">
                        <label class="block text-gray-700 text-sm font-semibold mb-4">
                            Enter Class URL
                            <span class="text-green-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="streamUrl" 
                                placeholder="https://lvpr.tv/broadcast/your-class-id" 
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-300"
                            >
                        </div>
                    </div>

                    <button 
                        id="joinButton" 
                        onclick="joinStream()" 
                        class="w-full bg-green-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg"
                    >
                        Join Class Now
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <div id="errorMessage" class="hidden mt-4 p-3 bg-red-50 text-red-700 rounded-lg border border-red-200">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="error-text"></span>
                    </div>
                </div>

                <!-- Stream Section -->
                <div id="streamSection" class="hidden mt-8">
                    <div class="bg-gray-50 rounded-xl p-4 border-2 border-dashed border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-broadcast-tower mr-2 text-indigo-600"></i>
                                Live Now
                                <span class="ml-2 px-2 py-1 bg-red-100 text-red-600 rounded-full text-sm">LIVE</span>
                            </h2>
                            <button 
                                onclick="rejoin()" 
                                class="text-indigo-600 hover:text-indigo-700 font-medium"
                            >
                                <i class="fas fa-redo mr-2"></i>Rejoin
                            </button>
                        </div>

                        <!-- Video Player -->
                        <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg overflow-hidden">
                            <video 
                                id="liveStream" 
                                controls 
                                autoplay 
                                class="w-full h-full object-cover"
                            ></video>
                        </div>

                        <!-- Loading State -->
                        <div id="loading" class="hidden mt-4 text-center">
                            <div class="inline-block animate-spin text-3xl text-indigo-600">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <p class="mt-2 text-gray-600">Connecting to live stream...</p>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 p-4 bg-indigo-50 rounded-lg flex items-center">
                        <i class="fas fa-info-circle text-indigo-600 mr-3 text-xl"></i>
                        <p class="text-gray-600 text-sm">
                            Having trouble? Contact support at 
                            <a href="mailto:support@liveclass.com" class="text-indigo-600 hover:underline">support@liveclass.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-yellow-600 text-sm">
            <p>Powered by <span class="text-red-600 font-semibold">LiveClass D-Academe</span></p>
            <div class="mt-2 space-x-4">
                <a href="#" class="hover:text-indigo-600"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-indigo-600"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-indigo-600"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </div>

    <script>
        function joinStream() {
            const streamUrl = document.getElementById('streamUrl').value.trim();
            const errorMessage = document.getElementById('errorMessage');
            const streamSection = document.getElementById('streamSection');
            const loading = document.getElementById('loading');
            const urlSection = document.getElementById('urlSection');
            const liveStream = document.getElementById('liveStream');

            // Clear previous state
            errorMessage.classList.add('hidden');
            liveStream.src = '';

            // Show loading state
            loading.classList.remove('hidden');
            streamSection.classList.remove('hidden');
            urlSection.classList.add('hidden');

            if (!streamUrl) {
                showError('Please enter a valid stream URL');
                return;
            }

            if (!isValidUrl(streamUrl)) {
                showError('Invalid URL format. Please check and try again.');
                return;
            }

            // Simulate connection delay (remove in production)
            setTimeout(() => {
                loadStream(streamUrl);
            }, 1000);
        }

        function loadStream(url) {
            const liveStream = document.getElementById('liveStream');
            const loading = document.getElementById('loading');

            try {
                if (Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(url);
                    hls.attachMedia(liveStream);
                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        loading.classList.add('hidden');
                        liveStream.play();
                    });
                } else if (liveStream.canPlayType('application/vnd.apple.mpegurl')) {
                    liveStream.src = url;
                    loading.classList.add('hidden');
                    liveStream.play();
                } else {
                    showError('Your browser does not support this stream format.');
                }
            } catch (error) {
                showError('Failed to load stream. Please check the URL and try again.');
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.querySelector('.error-text').textContent = message;
            errorMessage.classList.remove('hidden');
            document.getElementById('streamSection').classList.add('hidden');
            document.getElementById('urlSection').classList.remove('hidden');
            document.getElementById('loading').classList.add('hidden');
        }

        function isValidUrl(url) {
            try {
                new URL(url);
                return true;
            } catch (error) {
                return false;
            }
        }

        function rejoin() {
            document.getElementById('urlSection').classList.remove('hidden');
            document.getElementById('streamSection').classList.add('hidden');
        }
    </script>
</body>
</html>