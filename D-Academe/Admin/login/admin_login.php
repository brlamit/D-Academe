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

    // Handle POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
        $email = $data->email ?? '';
        $password = $data->password ?? '';

        // Fetch user data
        $stmt = $pdo->prepare("SELECT * FROM admin_login WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['profile_picture'] = $user['profile_picture'];

            echo json_encode(['status' => 'success', 'name' => $user['name'], 'profile_picture' => $_SESSION['profile_picture']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
