<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$db = 'dacademe';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle POST request for login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
        $email = $data->email ?? '';
        $password = $data->password ?? '';

        // Fetch user data based on the email
        $stmt = $pdo->prepare("SELECT * FROM admin_login WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Store all relevant user information in session
            
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone_number'] = $user['phone_number'];
            $_SESSION['profile_picture'] = $user['profile_picture'];
            $_SESSION['license'] = $user['license'];
            $_SESSION['created_at'] = $user['created_at'];
            $_SESSION['role'] = 'Admin'; // Example: Assuming the role is fixed

            // Return success response with the user details
            echo json_encode([
                'status' => 'success',
                'name' => $user['name'],
                'email' => $user['email'],
                'phone_number' => $user['phone_number'],
                'profile_picture' => $user['profile_picture'],
                'license' => $user['license'],
                'created_at' => $user['created_at'],
                'role' => $_SESSION['role']
            ]);
        } else {
            // Return error for invalid credentials
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }
} catch (PDOException $e) {
    // Handle database error
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
