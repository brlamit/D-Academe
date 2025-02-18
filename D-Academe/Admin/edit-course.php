<?php
// Include your database connection
require_once 'dbconnection.php'; // Ensure this file correctly establishes a connection
include_once 'Header.php';
// Initialize variables
$courseId = $name = $description = $tokenPrice = $imagePath = '';
$message = '';

// Pinata API credentials
$pinataApiKey = '60b9a1c65abf31d573fc';
$pinataApiSecret = 'b01057876bf74b515db95ff30e91fd9da8a57f6989010b5ca148d83c793e82f1';

// Start session to pass message
// session_start();

// Check if course details are provided in the URL
if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];
    $sql = "SELECT * FROM courses WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $description = $row['description'];
            $tokenPrice = $row['token_price'];
            $imagePath = $row['image']; // Assuming the image path is stored in the database
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the form
    $courseId = $_POST['course_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $tokenPrice = $_POST['token_price'];

    // Validate input fields
    if (empty($courseId) || empty($name) || empty($description) || empty($tokenPrice)) {
        $_SESSION['message'] = "All fields are required";
        $_SESSION['status'] = 'error';
        header("Location: edit-course.php?course_id=$courseId");
        exit;
    }

    // Sanitize inputs
    $name = htmlspecialchars($name);  // Prevent XSS attacks
    $description = htmlspecialchars($description); // Prevent XSS attacks
    $tokenPrice = floatval($tokenPrice);  // Ensure the token price is a number

    // Check if a new image is uploaded
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        // Image validation
        $imageTmpPath = $_FILES['course_image']['tmp_name'];
        $imageName = basename($_FILES['course_image']['name']);
        $imageSize = $_FILES['course_image']['size'];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        // Validate the file extension and size
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($imageExt, $allowedExtensions)) {
            $_SESSION['message'] = 'Invalid image format. Only JPG, JPEG, and PNG are allowed.';
            $_SESSION['status'] = 'error';
            header("Location: edit-course.php?course_id=$courseId");
            exit;
        }

        if ($imageSize > $maxFileSize) {
            $_SESSION['message'] = 'Image size is too large. Maximum allowed size is 2MB.';
            $_SESSION['status'] = 'error';
            header("Location: edit-course.php?course_id=$courseId");
            exit;
        }

        // Upload image to Pinata
        $imageUrl = uploadToPinata($imageTmpPath, $imageName, $pinataApiKey, $pinataApiSecret);

        if (!$imageUrl) {
            $_SESSION['message'] = 'Error uploading image to Pinata.';
            $_SESSION['status'] = 'error';
            header("Location: edit-course.php?course_id=$courseId");
            exit;
        }

        // Delete the old image from the server
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath); // Delete old image if exists
        }

        // Set the image URL to be saved in the database
        $imagePath = $imageUrl;
    }

   // Before executing the update, log the URL
error_log("Image URL: " . $imagePath); // Log the image URL for debugging

// Prepare the SQL query to update the course
$sql = "UPDATE courses SET name = ?, image = ?, description = ?, token_price = ? WHERE id = ?";

// Prepare the statement
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('sssdi', $name,  $imagePath, $description, $tokenPrice, $courseId); // 'ssdis' = string, string, double, string, integer

    // Execute the query
    if ($stmt->execute()) {
        // Log success for debugging
        error_log("Course updated successfully.");
        
        // Set success message in session
        $_SESSION['message'] = 'Course updated successfully';
        $_SESSION['status'] = 'success'; // Success status
    } else {
        // Log failure for debugging
        error_log("Error executing update query: " . $stmt->error);
        
        $_SESSION['message'] = 'Error updating course';
        $_SESSION['status'] = 'error'; // Error status
    }

    // Close the statement
} else {
    // Log failure to prepare the query
    error_log("Error preparing the update query: " . $conn->error);

    $_SESSION['message'] = 'Error preparing the query';
    $_SESSION['status'] = 'error'; // Error status
}

// Close the database connection
$conn->close();
}

// Function to upload image to Pinata
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
?>


<!-- HTML (Form Layout and Modal) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body {
            background: linear-gradient(to bottom, #203f43, #2c8364);
            background-size: cover;
            background-position: center;
            font-family: 'Inter', sans-serif;
        }
        input:focus, textarea:focus {
            border-color: #4b9f8f;
            box-shadow: 0 0 0 2px rgba(75, 159, 143, 0.5);
        }
        /* Modal and Loader Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .modal-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-button:hover {
            background-color: #45a049;
        }
        /* Loader */
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
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-900">

<!-- Form Layout -->
<div class=" p-8 rounded-xl shadow-2xl w-full max-w-4xl mt-32 relative">
    <button type="button" onclick="goBack()" class="absolute top-4 left-4 bg-transparent underline text-white py-2 px-4 text-sm rounded-lg font-semibold hover:text-teal-800 transition-all shadow-md">
        Go Back
    </button>
    
    <h1 class="text-4xl font-extrabold text-gray-400 text-center">Edit Course</h1>

    <!-- Form -->
    <form id="courseForm" action="" class="grid grid-cols-2 gap-8" method="POST" enctype="multipart/form-data" onsubmit="showLoader()">
        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($courseId); ?>">

        <div class="space-y-6">
            <div>
                <label class="block text-lg font-semibold text-gray-400">Current Course Image</label>
                <?php if ($imagePath): ?>
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Current Image" class="w-85 h-auto mt-0 " id="currentImage">
                    <p class="text-sm text-gray-400 mt-2">You can upload a new image to replace the current one.</p>
                <?php else: ?>
                    <p class="text-gray-500 mt-2">No image uploaded yet.</p>
                <?php endif; ?>

                <!-- File input for new image -->
                <input type="file" name="course_image" class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm text-white" onchange="previewImage(event)">
                <p class="text-sm text-gray-400 mt-2">Max file size: 2MB, Supported formats: JPG, PNG</p>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <label class="block text-lg font-semibold text-gray-400">Course Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm focus:ring-teal-500">
            </div>
            
            <div>
                <label class="block text-lg font-semibold text-gray-400">Description</label>
                <textarea name="description" rows="6" class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div>
                <label class="block text-lg font-semibold text-gray-400">Token Price</label>
                <input type="number" name="token_price" value="<?php echo htmlspecialchars($tokenPrice); ?>" step="0.01" class="w-full mt-2 p-3 rounded-md border border-gray-300 shadow-sm focus:ring-teal-500">
            </div> 
         
            <button type="submit" class="w-full py-3 mt-4 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-all shadow-lg">Update Course</button>
        </div>
    </form>

    <!-- Loader (Hidden by default) -->
    <div id="loader" class="hidden flex justify-center items-center col-span-2">
        <div class="loader"></div>
        <p class="ml-4 text-white">Uploading course...</p>
    </div>
</div>

<!-- Success Modal (Initially hidden) -->
<div id="successModal" onclick="goBack()" class="modal flex hidden">
    <div class="modal-content">
        <h2 class="text-2xl font-bold text-green-500">Course updated successfully!</h2>
        <button onclick="goBack()" class="modal-button mt-4">OK</button>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }

    function showLoader() {
        document.getElementById('loader').classList.remove('hidden');
    }

    // Show modal if the success message exists
    window.onload = function() {
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'success') { ?>
            document.getElementById('successModal').style.display = 'flex';
            <?php unset($_SESSION['status']); unset($_SESSION['message']); ?>
        <?php } ?>
    }

    // Function to preview the new image before submitting the form
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        const imgElement = document.getElementById('currentImage'); // Get the image element
        
        reader.onload = function(e) {
            imgElement.src = e.target.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
<?php include_once 'Footer.php'; ?>
</body>
</html>
