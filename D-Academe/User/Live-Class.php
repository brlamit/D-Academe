<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Live Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto my-20 p-6 bg-white shadow-lg rounded-lg">
        <!-- Stream URL Input Section -->
        <div id="urlSection" class="text-center">
            <h2 class="text-2xl font-bold mb-4">Join Live Class</h2>
            <input type="text" id="streamUrl" class="w-full p-3 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter the live stream URL...">
            <button id="joinButton" onclick="joinStream()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                Join Stream
            </button>
            <p id="errorMessage" class="text-red-500 hidden mt-2"></p>
        </div>

        <!-- Live Stream Section -->
        <div id="streamSection" class="hidden mt-6">
            <h2 class="text-2xl font-bold mb-4">Live Stream</h2>
            <video id="liveStream" controls autoplay class="w-full rounded-lg shadow-md"></video>
        </div>
    </div>

    <script>
        // Function to join the live stream
        function joinStream() {
            const streamUrl = document.getElementById('streamUrl').value.trim();
            const errorMessage = document.getElementById('errorMessage');
            const streamSection = document.getElementById('streamSection');
            const liveStream = document.getElementById('liveStream');

            if (!streamUrl) {
                errorMessage.innerText = "Please enter a valid stream URL.";
                errorMessage.classList.remove("hidden");
                return;
            }

            errorMessage.classList.add("hidden");

            // Check if the URL is valid
            if (!isValidUrl(streamUrl)) {
                errorMessage.innerText = "Invalid URL. Please enter a valid stream URL.";
                errorMessage.classList.remove("hidden");
                return;
            }

            // Hide the URL input section and show the live stream
            document.getElementById('urlSection').classList.add('hidden');
            streamSection.classList.remove('hidden');

            // Set the video source to the entered URL
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(streamUrl);
                hls.attachMedia(liveStream);
                hls.on(Hls.Events.MANIFEST_PARSED, function () {
                    liveStream.play();
                });
            } else if (liveStream.canPlayType('application/vnd.apple.mpegurl')) {
                liveStream.src = streamUrl;
                liveStream.addEventListener('loadedmetadata', function () {
                    liveStream.play();
                });
            } else {
                errorMessage.innerText = "Your browser does not support this stream format.";
                errorMessage.classList.remove("hidden");
            }
        }

        // Function to validate the URL
        function isValidUrl(url) {
            try {
                new URL(url);
                return true;
            } catch (error) {
                return false;
            }
        }
    </script>
</body>
</html>