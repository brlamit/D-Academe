<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto my-20 p-6 bg-white shadow-lg rounded-lg">
        <!-- Class Key Input Section -->
        <div id="keySection" class="text-center">
            <h2 class="text-2xl font-bold mb-4">Enter Class Key</h2>
            <input type="text" id="classKey" class="w-full p-3 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your class key...">
            <button id="joinButton" onclick="validateKey()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                Join Class
            </button>
            <p id="errorMessage" class="text-red-500 hidden mt-2"></p>
        </div>

        <!-- Main Content Section -->
        <div id="contentSection" class="">
            <!-- Meeting Control Panel -->
            <div class="flex justify-around items-center my-6">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-500 p-3 rounded-full mb-2">
                        <img src="https://img.icons8.com/ios-glyphs/30/ffffff/calendar.png" alt="Schedule Icon">
                    </div>
                    <button class="text-blue-500 font-semibold">Schedule</button>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-blue-500 p-3 rounded-full mb-2">
                        <img src="https://img.icons8.com/ios-glyphs/30/ffffff/plus-math.png" alt="Join Icon">
                    </div>
                    <button class="text-blue-500 font-semibold">Join</button>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-orange-500 p-3 rounded-full mb-2">
                        <img src="https://img.icons8.com/ios-glyphs/30/ffffff/video-conference.png" alt="Host Icon">
                    </div>
                    <button class="text-orange-500 font-semibold">Host</button>
                </div>
            </div>
            <!-- Personal Meeting ID -->
            <div class="text-center my-6">
                <p class="text-lg font-bold">Personal Meeting ID</p>
                <p class="text-2xl font-mono">953 712 2824</p>
                <button onclick="copyToClipboard('953 712 2824')" class="mt-2 text-sm text-blue-600 hover:underline">Copy ID</button>
            </div>

            <!-- Live Stream Section -->
            <div class="mb-6">
                <video id="liveStream" controls autoplay class="w-full rounded-lg shadow-md"></video>
            </div>
        </div>
    </div>

    <script>
        async function validateKey() {
            const classKey = document.getElementById('classKey').value.trim();
            const joinButton = document.getElementById('joinButton');
            const errorMessage = document.getElementById('errorMessage');

            if (!classKey) {
                errorMessage.innerText = "Please enter a valid class key.";
                errorMessage.classList.remove("hidden");
                return;
            }

            errorMessage.classList.add("hidden");
            joinButton.disabled = true;
            joinButton.innerText = "Validating...";

            try {
                // Fetch the stream URL from proxy.php
                const response = await fetch(`/proxy.php?classKey=${classKey}`);
                if (!response.ok) {
                    throw new Error("Invalid class key or stream not found.");
                }

                const streamData = await response.json();

                if (streamData.streamUrl) {
                    // Hide class key input and show video stream section
                    document.getElementById('keySection').classList.add('hidden');
                    document.getElementById('contentSection').classList.remove('hidden');

                    const video = document.getElementById('liveStream');
                    video.src = streamData.streamUrl;  // Use streamUrl from proxy.php
                } else {
                    throw new Error("Stream is not currently active.");
                }
            } catch (error) {
                errorMessage.innerText = error.message;
                errorMessage.classList.remove("hidden");
            } finally {
                joinButton.disabled = false;
                joinButton.innerText = "Join Class";
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }
    </script>
</body>
</html>
