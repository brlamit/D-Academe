<?php
session_start();  // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('dbconnection.php');  // Assuming this file contains your database connection code

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: ./login/admin_login.html");
    exit();
}

// Get user details from session
$user_email = $_SESSION['email'];

// Handle form submission for updating profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get new values from the form
    $new_phone_number = $_POST['phone_number'];
    $new_address = $_POST['address'];
    $new_dob = $_POST['dob'];
    $new_profile_picture = $_FILES['profile_picture']['name'];

    // Initialize variables for profile picture
    $profile_picture_path = $_SESSION['profile_picture'] ?? './assets/default-avatar.png';

    // Check if a new profile picture was uploaded
    if ($new_profile_picture) {
        // Specify the upload directory and file path
        $upload_dir = 'login/uploads/profile_pictures/';
        $profile_picture_path = $upload_dir . basename($new_profile_picture);

        // Check if the file upload is successful
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            // Check the file size and type
            $max_file_size = 5000000;  // 5MB maximum file size
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['profile_picture']['type'];

            // Validate the file size and type
            if ($_FILES['profile_picture']['size'] <= $max_file_size && in_array($file_type, $allowed_types)) {
                // Move the uploaded file to the server
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture_path)) {
                    // File upload successful, proceed with updating profile details
                    echo "Profile picture uploaded successfully.";
                } else {
                    echo "Error uploading profile picture.";
                }
            } else {
                echo "File is too large or not a valid image format. Only JPG, PNG, and GIF are allowed.";
            }
        } else {
            echo "Error during file upload. Error code: " . $_FILES['profile_picture']['error'];
        }
    }

    // Update user details in the database (phone number, profile picture, address, dob)
    $update_sql = "UPDATE user_login SET phone_number = ?, profile_picture = ?, address = ?, dob = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssss', $new_phone_number, $profile_picture_path, $new_address, $new_dob, $user_email);

    if ($update_stmt->execute()) {
        // Set session success message
        $_SESSION['message'] = "Profile updated successfully!";

        // Update session with new values
        $_SESSION['phone_number'] = $new_phone_number;
        $_SESSION['profile_picture'] = $profile_picture_path;
        $_SESSION['address'] = $new_address;
        $_SESSION['dob'] = $new_dob;

        // Redirect to the profile page to show the success message
        header("Location: index.php?page=view_profile");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $update_stmt->close();
}

$conn->close();
?>
