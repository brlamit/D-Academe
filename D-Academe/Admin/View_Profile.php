<?php
session_start();  // Make sure session is started

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

if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: admin_login.html");
    exit();
}

// Get user details from session
$user_email = $_SESSION['email'];

// Fetch user details from the database
$sql = "SELECT name, email, phone_number, profile_picture, license FROM admin_login WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_email);
$stmt->execute();
$stmt->bind_result($name, $email, $phone_number, $profile_picture, $license);
$stmt->fetch();
$stmt->close();

// Fallback to default images if not set
$profile_picture = $profile_picture ?? './assets/default-avatar.png';
$license = $license ?? './assets/default-license.png';
// Construct the full path for license picture
$licensePicturePath = $_SERVER['DOCUMENT_ROOT'] . '/practice/admin/login/' . $license;

// Check if the license image file exists
if (file_exists($licensePicturePath)) {
    // Use the database path directly
    $licensePictureUrl = '/practice/admin/login/' . $license;
} else {
    // Fallback to default license image if file doesn't exist
    $licensePictureUrl = './assets/default-license.png';
}

// Handle form submission for updating profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get new values from the form
    $new_phone_number = $_POST['phone_number'];
    $new_profile_picture = $_FILES['profile_picture']['name'];
    $new_license = $_FILES['license']['name'];

    // Initialize variables for profile picture and license image
    $profile_picture_path = $profile_picture;
    $license_path = $license;

    // Check if new profile picture was uploaded
    if ($new_profile_picture) {
        $profile_picture_path = 'uploads/' . basename($new_profile_picture);
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture_path)) {
            echo "Profile picture uploaded successfully.";
        } else {
            echo "Error uploading profile picture.";
        }
    }

    // Check if new license image was uploaded
    if ($new_license) {
        $license_path = 'uploads/' . basename($new_license);
        if (move_uploaded_file($_FILES['license']['tmp_name'], $license_path)) {
            echo "License uploaded successfully.";
        } else {
            echo "Error uploading license image.";
        }
    }

    // Update user details in the database (only phone number, profile picture, and license)
    $update_sql = "UPDATE admin_login SET phone_number = ?, profile_picture = ?, license = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssss', $new_phone_number, $profile_picture_path, $license_path, $user_email);

    if ($update_stmt->execute()) {
        // Update session with new values
        $_SESSION['phone_number'] = $new_phone_number;
        $_SESSION['profile_picture'] = $profile_picture_path;
        $_SESSION['license'] = $license_path;

        // Redirect to the same page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $update_stmt->close();
}

$conn->close();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>User Profile</title>
    <script>
        function toggleFileInput(inputId) {
            var fileInput = document.getElementById(inputId);
            fileInput.click();
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 mt-10 rounded-lg shadow-lg w-full  text-center">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">Profile Details</h1>

        <!-- Profile Image -->
        <div class="profile-image mb-6">
            <!-- Profile picture -->
            <img src="<?php echo htmlspecialchars($profilePictureUrl); ?>" 
                 alt="Profile" 
                 class="w-32 h-32 rounded-lg object-cover mx-auto cursor-pointer"
                 onclick="toggleFileInput('profile_picture_input')" 
                 onerror="this.onerror=null; this.src='./assets/default-avatar.png';">
            <input type="file" id="profile_picture_input" name="profile_picture" class="mt-1 p-2 border rounded w-full hidden" onchange="this.form.submit()">
        </div>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="profile-details space-y-4 text-left">
            <p class="text-gray-700">
                <strong>Name:</strong>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="mt-1 p-2 border rounded w-full" disabled>
            </p>

            <p class="text-gray-700">
                <strong>Email:</strong>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="mt-1 p-2 border rounded w-full" disabled>
            </p>

            <p class="text-gray-700">
                <strong>Phone Number:</strong>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" class="mt-1 p-2 border rounded w-full" required>
            </p>

            <p class="text-gray-700">
                <strong>License Image:</strong>
                <!-- License Image -->
                <img src="<?php echo htmlspecialchars($licensePictureUrl); ?>" 
                     alt="License" 
                     class="w-32 h-32 object-cover mx-auto cursor-pointer" 
                     onclick="toggleFileInput('license_input')" 
                     onerror="this.onerror=null; this.src='./assets/default-license.png';">
                <input type="file" id="license_input" name="license" class="mt-1 p-2 border rounded w-full hidden" onchange="this.form.submit()">
            </p>

            <p class="text-gray-700"><strong>Role:</strong> 
                <input type="text" name="role" placeholder="ADMIN" class="mt-1 p-2 border rounded w-full" disabled>  
            </p>

            <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-full">Update Profile</button>
            
            <!-- Forgot Password Button -->
            <p class="text-gray-700">
                <a href="./login/forgot_password-form.php" class="mt-4 px-6 py-2 bg-red-600 text-white rounded-full inline-block">
                    Change Password?
                </a>
            </p>
        </form>

    </div>

</body>
</html>
