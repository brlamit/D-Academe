<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solidity Basic</title>
    <style>
        body {
            background: linear-gradient(to bottom, #0f2027, #203f43, #2c8364);
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-gray-900 via-teal-800 to-green-600 min-h-screen flex flex-col">
    <section class="flex-grow flex flex-col items-center justify-center mt-32 px-4">
        <!-- Header Section -->
        <h1 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-teal-200 mb-8">
            Solidity Basic 
        </h1>

        <!-- Watch Course Button -->
        <div>
            <button 
                onclick="showVideo('bafybeicmboyhmlp2awqzfjefmqvmawfggebd3kugq4ffavijm7iade5n3q')" 
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-12 rounded-full text-lg shadow-lg transition-transform transform hover:scale-105">
                Continue Course
            </button>
        </div>

        <!-- Video Container -->
        <div id="video-container" class="hidden mt-12 w-full max-w-5xl mx-auto text-center">
            <h2 class="text-3xl font-semibold text-white mb-6">Course Video</h2>
            <div class="relative pb-[56.25%] h-0">
                <iframe 
                    id="pinata-video" 
                    class="absolute top-0 left-0 w-full h-full rounded-lg shadow-xl border-4 border-green-400" 
                    src="" 
                    frameborder="0" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>
   
    <script>
        // Function to show the video
        function showVideo(cid) {
            const videoUrl = `https://gateway.pinata.cloud/ipfs/${cid}`;
            
            // Set the iframe source
            const videoFrame = document.getElementById('pinata-video');
            videoFrame.src = videoUrl;

            // Display the video container
            const videoContainer = document.getElementById('video-container');
            videoContainer.classList.remove('hidden');
        }
    </script>

</body>
</html>
