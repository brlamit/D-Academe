<?php
session_start();

// Database credentials
$host = 'localhost';
$db = 'dacademe';  // Database name
$user = 'root';
$pass = '';

// Establish database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $errors = [];

    // Validate email input
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    // Proceed if no errors
    if (empty($errors)) {
        try {
            // Check if the email exists in the login table in 'dacademe' database
            $stmt = $pdo->prepare("SELECT * FROM login WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate a password reset token
                $token = bin2hex(random_bytes(50));  // Secure token generation
                $expiry = time() + 3600; // Token expiry time (1 hour)

                // Update the login table with the reset token and expiry time
                $stmt = $pdo->prepare("UPDATE login SET reset_token = :token, expiry = :expiry WHERE email = :email");
                $stmt->execute([
                    'token' => $token,
                    'expiry' => date('Y-m-d H:i:s', $expiry), // Convert expiry to timestamp format
                    'email' => $email
                ]);

                // Send the reset link via email
                require 'vendor/autoload.php';  // Ensure PHPMailer is installed

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'baralamit881@gmail.com';  // Use your email here
                    $mail->Password = 'your-app-password';  // Use an App Password if 2FA is enabled
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;  // Gmail's TLS port

                    $mail->setFrom('baralamit881@gmail.com', 'D-Academe');
                    $mail->addAddress($email);

                    $resetLink = "http://website.com/reset_password.php?token=$token";
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = "<p>To reset your password, click the following link:</p>
                                   <a href='$resetLink'>Reset Password</a>
                                   <p>This link will expire in 1 hour.</p>";

                    if ($mail->send()) {
                        $_SESSION['message'] = 'A reset link has been sent to your email address.';
                    } else {
                        $_SESSION['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
                    }
                } catch (Exception $e) {
                    $_SESSION['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
                }

                // Redirect to a success page or the same page with a success message
                header('Location: forgot_password.html');
                exit;

            } else {
                $_SESSION['message'] = 'No account found with that email address.';
                header('Location: forgot_password.html');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = 'Database Error: ' . $e->getMessage();
            header('Location: forgot_password.html');
            exit;
        }
    } else {
        // Handle validation errors
        $_SESSION['message'] = implode(", ", $errors);
        header('Location: forgot_password.html');
        exit;
    }
} else {
    // Handle unsupported request methods
    $_SESSION['message'] = 'Invalid request method';
    header('Location: forgot_password.html');
    exit;
}
?>

