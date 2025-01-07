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
    <meta name="viewport" content="centre , initial-scale=1.0">
    <title>Create Live Class</title>
    <style>
              body {
            margin-top: 100px;
            justify-content: center;
        }
        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            transition: background-color 0.3s ease;
            z-index: 50;
        }
        .container {
            margin-top: 120px;
            margin-left: 500px;
        }

.container {
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    padding: 30px 20px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
    border-radius: 12px;
    width: 90%; /* Adjust the width as needed */
    max-width: 400px; /* Ensures the box doesn’t stretch too wide */
}

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            margin: 10px;
            font-size: 16px;
            width: 80%;
            max-width: 300px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border-radius: 8px;
        }
        input {
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }
        button {
            background: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }
        .details {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            text-align: left;
            display: inline-block;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create a Live Class</h1>
        <form method="POST" action="">
            <input type="text" name="streamName" placeholder="Enter Stream Name" required />
            <button type="submit">Create Stream</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($streamDetails): ?>
            <div class="details">
                <h2>Stream Created Successfully!</h2>
                <p><strong>Stream Name:</strong> <?= htmlspecialchars($streamDetails['name']) ?></p>
                <p><strong>Stream ID:</strong> <?= htmlspecialchars($streamDetails['id']) ?></p>
                <p><strong>RTMP URL:</strong> rtmp://rtmp.livepeer.com/live</p>
                <p><strong>Stream Key:</strong> <?= htmlspecialchars($streamDetails['streamKey']) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
