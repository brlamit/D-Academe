<?php
session_start();  // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost'; // Change this to your database host
$db = 'dacademe'; // Your database name
$user = 'root'; // Your database user
$pass = ''; // Your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the admin is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ./login/admin_login.html");
    exit();
}

// Get admin email from session
$admin_email = $_SESSION['email'];

// Function to handle file uploads
function handleFileUpload($file, $uploadDir, $default = '')
{
    if ($file['name']) {
        $fileName = basename($file['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if ($file['size'] <= 2 * 1024 * 1024) { // Limit size to 2MB
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    return $filePath;
                } else {
                    echo "Error uploading file: $fileName<br>";
                }
            } else {
                echo "File size exceeds the limit (2MB): $fileName<br>";
            }
        } else {
            echo "Invalid file type: $fileName<br>";
        }
    }
    return $default;
}

// Handle form submission for updating profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get new values from the form
    $new_phone_number = htmlspecialchars($_POST['phone_number']);
    $profile_picture_path = handleFileUpload($_FILES['profile_picture'], 'login/uploads/profile_pictures/', $_SESSION['profile_picture'] ?? '');
    $license_path = handleFileUpload($_FILES['license'], 'login/uploads/licenses/', $_SESSION['license'] ?? '');

    // Update admin details in the database
    $update_sql = "UPDATE admin_login SET phone_number = ?, profile_picture = ?, license = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssss', $new_phone_number, $profile_picture_path, $license_path, $admin_email);

    if ($update_stmt->execute()) {
        // Update session with new values
        $_SESSION['phone_number'] = $new_phone_number;
        $_SESSION['profile_picture'] = $profile_picture_path;
        $_SESSION['license'] = $license_path;

        // Redirect to the profile page
        header("Location: index.php?page=view_profile");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $update_stmt->close();
}
$conn->close();
?>
