<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$db = 'dacademe';
$user = 'root';
$pass = '';

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle POST request for login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Decode JSON input data
        $data = json_decode(file_get_contents("php://input"));
        $email = $data->email ?? '';
        $password = $data->password ?? '';

        // Validate email and password fields
        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
            exit;
        }

        // Fetch user data based on the email (correct table name: user_login)
        $stmt = $pdo->prepare("SELECT * FROM user_login WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id();

            // Store all relevant user information in session
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['dob'] = $user['dob'];
            $_SESSION['gender'] = $user['gender'];
            $_SESSION['address'] = $user['address'];
            $_SESSION['phone_number'] = $user['phone_number'];
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
            $_SESSION['role'] = 'Student'; // Assuming the role is fixed

            // Return success response with the user details
            echo json_encode([
                'status' => 'success',
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'dob' => $user['dob'],
                'gender' => $user['gender'],
                'address' => $user['address'],
                'phone_number' => $user['phone_number'],
                'profile_picture' => $user['profile_picture'] ?? null,
                'role' => $_SESSION['role']
            ]);
        } else {
            // Return error for invalid credentials
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    }
} catch (PDOException $e) {
    // Handle database error
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Handle general error
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred. Please try again later.']);
}
