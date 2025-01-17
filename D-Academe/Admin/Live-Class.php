<?php
define("LIVEPEER_API_KEY", "5027e304-ad2d-4b3f-b61e-623de34af076");

// Initialize variables
$streamDetails = null;
$error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $streamName = trim($_POST['streamName'] ?? 'Live Class');

    if (!empty($streamName)) {
        $streamDetails = createLivepeerStream($streamName);

        if (!$streamDetails || !isset($streamDetails['id'], $streamDetails['streamKey'])) {
            $error = "Failed to create the stream. Please check your API key and try again.";
        }
    } else {
        $error = "Stream name cannot be empty.";
    }
}

// Function to create a new Livepeer stream
function createLivepeerStream($streamName)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://livepeer.com/api/stream");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "name" => $streamName,
        "profiles" => [
            ["name" => "720p", "bitrate" => 2000000, "fps" => 30, "width" => 1280, "height" => 720],
            ["name" => "480p", "bitrate" => 1000000, "fps" => 30, "width" => 854, "height" => 480],
            ["name" => "360p", "bitrate" => 500000, "fps" => 30, "width" => 640, "height" => 360]
        ]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . LIVEPEER_API_KEY,
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);

    // Handle errors in the cURL request
    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    // Decode response and return
    return json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Live Class</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="flex bg-white shadow-lg rounded-lg w-full max-w-4xl">
            <!-- Left Section: Video Streamer -->
            <div class="w-1/3 p-4 bg-gray-200 rounded-l-lg flex flex-col items-center justify-center">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Live Stream</h2>
                <!-- Video Element for Camera Stream -->
                <div class="w-full h-64 bg-gray-400 rounded-lg mb-4 relative">
                    <video id="videoElement" class="w-full h-full object-cover" autoplay muted></video>
                    <!-- Camera Icon -->
                    <div id="cameraIcon" class="absolute top-4 left-4 text-white text-3xl cursor-pointer">
                        <i class="fas fa-video-slash"></i> <!-- Disabled video icon initially -->
                    </div>
                    <!-- Microphone Icon -->
                    <div id="micIcon" class="absolute bottom-4 left-4 text-white text-3xl cursor-pointer">
                        <i class="fas fa-microphone-slash"></i> <!-- Disabled mic icon initially -->
                    </div>
                </div>
                <!-- <p class="text-sm text-gray-600">This is a live stream view. Your camera and microphone will appear here.</p> -->
            </div>

            <!-- Right Section: Form & Details -->
            <div class="w-2/3 p-6">
                <h1 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Create a Live Class</h1>

                <!-- Form -->
                <form method="POST" action="" class="space-y-4">
                    <input type="text" name="streamName" placeholder="Enter Stream Name" 
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">
                        Create Stream
                    </button>
                </form>

                <!-- Error Message -->
                <?php if ($error): ?>
                    <div class="mt-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <!-- Stream Details -->
                <?php if ($streamDetails): ?>
                    <div class="mt-6 bg-gray-50 p-4 rounded-md border border-gray-300 text-left">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Stream Created Successfully!</h2>
                        <p><strong>Stream Name:</strong> <?= htmlspecialchars($streamDetails['name']) ?></p>
                        <p><strong>Stream ID:</strong> <?= htmlspecialchars($streamDetails['id']) ?></p>
                        <p><strong>RTMP URL:</strong> rtmp://rtmp.livepeer.com/live</p>
                        <p><strong>Stream Key:</strong> <?= htmlspecialchars($streamDetails['streamKey']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
       let currentStream = null;
let videoTrack = null;
let audioTrack = null;

// Get references to the video element and icons
const videoElement = document.getElementById('videoElement');
const cameraIcon = document.getElementById('cameraIcon');
const micIcon = document.getElementById('micIcon');

// Variables to track the camera and microphone status
let isCameraOn = false;
let isMicOn = false;
let mediaStream = null;

// Function to toggle the camera
cameraIcon.addEventListener('click', function() {
    if (isCameraOn) {
        // Turn off the camera (stop the track)
        if (videoTrack) {
            videoTrack.stop();
            videoElement.srcObject = null; // Stop displaying the video
        }
        cameraIcon.innerHTML = '<i class="fas fa-video-slash"></i>';  // Change icon
        isCameraOn = false;
    } else {
        // Turn on the camera (request a new stream)
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(function(stream) {
                mediaStream = stream;
                videoTrack = stream.getVideoTracks()[0]; // Get the video track
                audioTrack = stream.getAudioTracks()[0]; // Get the audio track

                videoElement.srcObject = stream; // Display video feed
                cameraIcon.innerHTML = '<i class="fas fa-video"></i>';  // Change icon
                isCameraOn = true;
                isMicOn = true;
                micIcon.innerHTML = '<i class="fas fa-microphone"></i>';  // Ensure mic is on
            })
            .catch(function(error) {
                console.error("Error accessing webcam and microphone: ", error);
                alert("Could not access webcam and microphone. Please ensure they are available and try again.");
            });
    }
});

// Function to toggle the microphone
micIcon.addEventListener('click', function() {
    if (isMicOn) {
        // Turn off the microphone
        if (audioTrack) {
            audioTrack.enabled = false;
        }
        micIcon.innerHTML = '<i class="fas fa-microphone-slash"></i>';  // Change icon
        isMicOn = false;
    } else {
        // Turn on the microphone
        if (audioTrack) {
            audioTrack.enabled = true;
        }
        micIcon.innerHTML = '<i class="fas fa-microphone"></i>';  // Change icon
        isMicOn = true;
    }
});

// Request access to the webcam and microphone when the page loads
navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    .then(function(stream) {
        mediaStream = stream;
        videoTrack = stream.getVideoTracks()[0]; // Get the video track
        audioTrack = stream.getAudioTracks()[0]; // Get the audio track

        videoElement.srcObject = stream; // Display video feed
        cameraIcon.innerHTML = '<i class="fas fa-video"></i>';  // Camera on by default
        micIcon.innerHTML = '<i class="fas fa-microphone"></i>';  // Mic on by default
        isCameraOn = true;
        isMicOn = true;
    })
    .catch(function(error) {
        console.error("Error accessing webcam and microphone: ", error);
        alert("Could not access webcam and microphone. Please ensure they are available and try again.");
    });
</script>
</body>
</html>