<?php
session_start();  // Start session to access session variables
require 'db_connection.php'; // Assuming this handles your database connection

$showModal = false;  // Flag to control modal display
$message = '';       // Store session message for failure

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Set the time zone to Nepal Time (NPT) for consistent time comparison
    date_default_timezone_set('Asia/Kathmandu');

    // Check if the token is valid and hasn't expired
    $stmt = $conn->prepare("SELECT * FROM admin_login WHERE reset_token = ? AND expiry > NOW()"); 
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Ensure passwords match
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                
                // Update the user's password and clear the reset token and expiry
                $stmt = $conn->prepare("UPDATE admin_login SET password = ?, reset_token = NULL, expiry = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();

                // Trigger the modal for successful password reset
                $showModal = true;  // Show success modal
            } else {
                // Passwords do not match
                $message = "Passwords do not match.";
            }
        }
    } else {
        // Token is invalid or expired
        $message = "Invalid or expired token.";
    }
} else {
    // No token provided in URL
    $message = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - D-Academe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #203f43, #2c8364);
            background-size: cover;
            background-position: center;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class=" p-8 rounded-xl shadow-lg w-full max-w-md">
        <!-- Logo -->
        <img src="../assets/logo.png" alt="D-Academe Logo" class="w-24 mx-auto mb-6">

        <!-- Heading -->
        <h1 class="text-3xl font-bold text-gray-200 text-center mb-6">Reset Your Password</h1>

        <!-- Display error message if exists -->
        <?php
        if (!empty($message)) {
            echo '<div class="bg-red-100 text-red-700 p-4 rounded mb-6 text-center">' . $message . '</div>';
        }
        ?>

        <!-- Form -->
        <form action="" method="POST" class="space-y-6">
            <!-- New Password -->
            <div class="relative">
                <input type="password" name="new_password" id="new_password" placeholder="New Password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent placeholder-gray-500" 
                       required>
                <button type="button" id="toggleNewPassword" class="absolute right-4 top-3 text-gray-500">
                    <i class="fas fa-eye-slash"></i> <!-- Eye-slash (hidden password) -->
                </button>
            </div>

            <!-- Confirm Password -->
            <div class="relative">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent placeholder-gray-500" 
                       required>
                <button type="button" id="toggleConfirmPassword" class="absolute right-4 top-3 text-gray-500">
                    <i class="fas fa-eye-slash"></i> <!-- Eye-slash (hidden password) -->
                </button>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    Reset Password
                </button>
            </div>
        </form>

        <!-- Additional Links -->
        <p class="text-gray-200 text-sm text-center mt-4">
            Remembered your password? <a href="admin_login.html" class="text-teal-400 hover:underline">Login</a>
        </p>
    </div>

    <!-- Success Modal -->
    <?php if ($showModal) : ?>
    <div id="successModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md text-center">
            <h2 class="text-2xl font-bold mb-4">Success!</h2>
            <p class="text-gray-600 mb-6">Your password has been reset successfully.</p>
            <a href="admin_login.html" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-all">
                Go to Login
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Script -->
    <script>
        // Password visibility toggle for the 'New Password' field
        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            const passwordField = document.getElementById('new_password');
            const currentType = passwordField.type;
            passwordField.type = currentType === 'password' ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');  // Toggle to open eye
            this.querySelector('i').classList.toggle('fa-eye-slash');  // Toggle to closed eye
        });

        // Password visibility toggle for the 'Confirm Password' field
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm_password');
            const currentType = confirmPasswordField.type;
            confirmPasswordField.type = currentType === 'password' ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');  // Toggle to open eye
            this.querySelector('i').classList.toggle('fa-eye-slash');  // Toggle to closed eye
        });
    </script>
</body>
</html>
