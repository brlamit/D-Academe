<?php
require_once './dbconnection.php'; // Database connection

// Pinata API credentials
$pinata_api_key = "60b9a1c65abf31d573fc";
$pinata_secret_api_key = "b01057876bf74b515db95ff30e91fd9da8a57f6989010b5ca148d83c793e82f1";

// Function to create the courses table (if it doesn't exist already)
function createCourseTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS courses (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        image TEXT NOT NULL,
        description TEXT NOT NULL,
        token_price DECIMAL(10, 2) NOT NULL,
        course_content LONGTEXT NOT NULL,
        date_of_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: " . $conn->error;
    }
}

// Function to add a new course
function addCourse($conn, $name, $image, $description, $token_price, $course_content_url) {
    $stmt = $conn->prepare("INSERT INTO courses (name, image, description, token_price, course_content) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssds", $name, $image, $description, $token_price, $course_content_url);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Database insertion error: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

// Upload file to Pinata
function uploadToPinata($filePath, $fileName, $pinata_api_key, $pinata_secret_api_key) {
    $url = "https://api.pinata.cloud/pinning/pinFileToIPFS";
    $cFile = new CURLFile($filePath, mime_content_type($filePath), $fileName);
    $headers = [
        "pinata_api_key: $pinata_api_key",
        "pinata_secret_api_key: $pinata_secret_api_key"
    ];
    $postFields = ["file" => $cFile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['IpfsHash'])) {
        return "https://gateway.pinata.cloud/ipfs/" . $result['IpfsHash'];
    } else {
        error_log("Pinata API error: " . ($result['error'] ?? 'Unknown error'));
        return false;
    }
}

createCourseTable($conn);

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $token_price = filter_var($_POST['token_price'], FILTER_VALIDATE_FLOAT);

    $imageUrl = $course_content_url = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];

        $imageUrl = uploadToPinata($imagePath, $imageName, $pinata_api_key, $pinata_secret_api_key);
        if (!$imageUrl) {
            $message = 'Image upload to Pinata failed.';
            $messageType = 'error';
        }
    } else {
        $message = 'Image upload failed.';
        $messageType = 'error';
    }

    if (isset($_FILES['course_content']) && $_FILES['course_content']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['pdf', 'md', 'pptx', 'docx', 'zip'];
        $contentPath = $_FILES['course_content']['tmp_name'];
        $contentName = $_FILES['course_content']['name'];
        $contentExtension = strtolower(pathinfo($contentName, PATHINFO_EXTENSION));

        if (!in_array($contentExtension, $allowedExtensions)) {
            $message = 'Invalid course content file type. Allowed types: ' . implode(', ', $allowedExtensions);
            $messageType = 'error';
        } else {
            $course_content_url = uploadToPinata($contentPath, $contentName, $pinata_api_key, $pinata_secret_api_key);
            if (!$course_content_url) {
                $message = 'Course content upload failed.';
                $messageType = 'error';
            }
        }
    } else {
        $message = 'Course content file is required.';
        $messageType = 'error';
    }

    if ($messageType === '') {
        if ($imageUrl && $course_content_url) {
            if (addCourse($conn, $name, $imageUrl, $description, $token_price, $course_content_url)) {
                $message = 'Course added successfully.';
                $messageType = 'success';
                echo json_encode(['message' => $message, 'course_url' => $course_content_url]);
                exit;
            } else {
                $message = 'Failed to add course.';
                $messageType = 'error';
                echo json_encode(['message' => $message]);
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input:focus, textarea:focus {
            border-color: #4b9f8f;
            box-shadow: 0 0 0 2px rgba(75, 159, 143, 0.5);
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
        /* Loader Style */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
<div class="bg-black p-8 rounded-xl shadow-2xl w-full max-w-4xl relative">
    <button type="button" onclick="goBack()"
            class="absolute top-4 left-4 bg-transparent underline text-white py-2 px-4 text-sm rounded-lg font-semibold hover:text-teal-800 transition-all shadow-md">
        Go Back
    </button>  
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-gray-400">Add New Course</h1>
    </div>

    <!-- Display Success or Error Message -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center">
        <div class="bg-white p-6 rounded-md shadow-md w-96 text-center">
            <h2 class="text-xl font-semibold" id="modalMessage"><?php echo $message; ?></h2>
            
            <!-- Buttons to either go back or stay on the same page -->
            <div class="mt-4 flex justify-center space-x-4">
                <button class="bg-teal-500 text-white py-2 px-4 rounded-md hover:bg-teal-600 transition-all" onclick="goBack()">Go Back</button>
                <button class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all" onclick="stayOnPage()">Ok</button>
            </div>
        </div>
    </div>

    <form id="courseForm" class="grid grid-cols-2 gap-8" enctype="multipart/form-data" method="POST">

        <!-- Left Column -->
        <div class="space-y-6" id="formContent">
            <div>
                <label class="block text-lg font-semibold text-gray-400">Course Name</label>
                <input type="text" name="name" required class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-400">Course Image</label>
                <input type="file" name="image" required class="w-full mt-2 p-3 rounded-md border text-white border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-400">Description</label>
                <textarea name="description" rows="6" required class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm"></textarea>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <div>
                <label class="block text-lg font-semibold text-gray-400">Token Price</label>
                <input type="number" name="token_price" step="0.01" required class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-400">Course Content (PDF/Docx/MD/PPT/Zip)</label>
                <input type="file" name="course_content" required class="w-full mt-2 p-3 rounded-md border text-white border-gray-300 shadow-sm">
            </div>
            <button type="submit" class="w-full py-3 mt-4 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-all shadow-lg">Add Course</button>
        </div>
    </form>
    
    <!-- Loader -->
    <div id="loader" class="hidden flex justify-center items-center col-span-2">
        <div class="loader"></div>
        <p class="ml-4 text-white">Uploading course...</p>
    </div>
</div>

<script>
   const form = document.getElementById('courseForm');
const loader = document.getElementById('loader');
const formContent = document.getElementById('formContent');
const modal = document.getElementById('modal');
const modalMessage = document.getElementById('modalMessage');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    // Show loader and hide form content
    loader.classList.remove('hidden');

    // Submit the form after showing loader
    const formData = new FormData(form);
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse the response as JSON
    .then(data => {
        loader.classList.add('hidden');
        modal.classList.remove('hidden');

        // Check if the response contains the course URL
        if (data.course_url) {
            // Display success message with course URL
            modalMessage.innerHTML = `${data.message} <br> Course URL: <a href="${data.course_url}" target="_blank" class="text-teal-500">View Course Content</a>`;
        } else {
            modalMessage.innerHTML = data.message;
        }
    })
    .catch(error => {
        loader.classList.add('hidden');
        modal.classList.remove('hidden');
        modalMessage.innerHTML = 'Failed to add course. Please try again.';
    });
});

// Function to go back to the previous page
function goBack() {
    window.history.back();
}

// Function to stay on the current page and close the modal
function stayOnPage() {
    modal.classList.add('hidden');
}

</script>
</body>
</html>
