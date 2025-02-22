<?php
require '../../utils/PHPMailer/src/PHPMailer.php';
require '../../utils/PHPMailer/src/SMTP.php';
require '../../utils/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'constants.php';
require 'db_connection.php'; // Include your database connection file

$message = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate form inputs
    $email = htmlspecialchars($_POST['email']);

    // Check if the email exists in the admin_login table
    $stmt = $conn->prepare("SELECT * FROM user_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        date_default_timezone_set('Asia/Kathmandu'); // Set the time zone to Nepal Time (NPT)

        // User found, generate a unique token for password reset
        $token = bin2hex(random_bytes(50)); // 50 bytes = 100 characters
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expires in 1 hour

        // Update the admin_login table with the reset token and expiry
        $stmt = $conn->prepare("UPDATE user_login SET reset_token = ?, expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Prepare the reset password link
        $reset_link = "http://localhost/D-Academe/D-Academe/user/login/reset_password-form.php?token=" . $token;

        // PHPMailer setup
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your-email@gmail.com', 'D-Academe Support');
            $mail->addAddress($email); // Send the email to the user
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "You requested a password reset. Click the link below to reset your password:\n\n" .
                          "$reset_link\n\n" .
                          "If you did not request this, please ignore this email.";

            // Send the email and check if it was successful
            if ($mail->send()) {
                $message = "A password reset link has been sent to your email!";
            } else {
                $message = "Failed to send password reset email.";
            }
        } catch (Exception $e) {
            $message = "Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "No account found with that email.";
    }

    // Redirect to the same page with a success or error message
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit();
}

// Handle messages passed through the query string
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - D-Academe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #203f43, #2c8364);
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class=" bg-opacity-70 p-8 rounded-xl shadow-lg w-full max-w-md">
        <!-- Logo -->
        <img src="../../assets/logo.png" alt="D-Academe Logo" class="w-24 mx-auto mb-6">

        <!-- Heading -->
        <h1 class="text-3xl font-bold text-white text-center mb-6">Forgot Your Password?</h1>

        <!-- Form -->
        <form action="" method="POST" class="space-y-4">
            <div>
                <input type="email" name="email" placeholder="Enter your email address"
                    class="w-full px-4 py-3 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent placeholder-gray-500"
                    required>
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    Request Password Reset
                </button>
            </div>
        </form>

        <!-- Link to Login -->
        <p class="text-gray-300 text-sm text-center mt-6">
            Remembered your password? <a href="user_login.html" class="text-teal-400 hover:underline">Login</a>
        </p>
    </div>

    <!-- Modal for displaying the message -->
    <?php if (!empty($message)): ?>
        <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-xl shadow-xl w-96">
                <h2 class="text-xl font-semibold text-center mb-4">Message</h2>
                <p class="text-center text-lg mb-4"><?php echo htmlspecialchars($message); ?></p>
                <div class="text-center">
                    <button onclick="closeModal()" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Close</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }
    </script>
</body>
</html>
