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

    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    return json_decode($response, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Class Streaming</title>
    <style>
        body {
            margin-top: 100px;
            justify-content: center;
        }
        .container {
            text-align: center;
            margin: 0 auto;
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        h1 {
            margin-bottom: 20px;
        }
        input, button {
            display: block;
            width: 80%;
            margin: 10px auto;
            padding: 10px;
            font-size: 16px;
        }
        .details {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .video-container {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create and Stream Live Class</h1>
        <form method="POST" action="">
            <input type="text" name="streamName" placeholder="Enter Stream Name" required />
            <button type="submit">Create Stream</button>
        </form>

        <?php if ($error): ?>
            <div style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($streamDetails): ?>
            <div class="details">
                <h2>Stream Created Successfully!</h2>
                <p><strong>Stream Name:</strong> <?= htmlspecialchars($streamDetails['name']) ?></p>
                <p><strong>Stream ID:</strong> <?= htmlspecialchars($streamDetails['id']) ?></p>
                <p><strong>RTMP URL:</strong> rtmp://rtmp.livepeer.com/live</p>
                <p><strong>Stream Key:</strong> <?= htmlspecialchars($streamDetails['streamKey']) ?></p>
                <p><strong>Playback URL:</strong> 
                    <a href="watch.php?classKey=<?= htmlspecialchars($streamDetails['id']) ?>" target="_blank">
                        Watch Stream
                    </a>
                </p>
            </div>

            <div class="video-container">
                <h3>Your Live Stream</h3>
                <video id="streamVideo" controls autoplay>
                    <source src="https://livepeercdn.com/hls/<?= htmlspecialchars($streamDetails['playbackId']) ?>/index.m3u8" type="application/x-mpegURL">
                    Your browser does not support HLS streaming.
                </video>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
