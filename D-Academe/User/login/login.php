<?php
$host = 'localhost'; // Replace with your database host
$db = 'dacademe';    // Replace with your database name
$user = 'root';       // Replace with your database user
$pass = '';           // Replace with your database password

header('Content-Type: application/json'); // Ensure the response is JSON

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle POST request for login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get POST data (JSON)
        $data = json_decode(file_get_contents("php://input"));

        // Extract data from JSON
        $email = $data->email;
        $password = $data->password;

        // Check if the email exists in the database
        $stmt = $pdo->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password matches, return success response
                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            } else {
                // Password doesn't match
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            }
        } else {
            // Email doesn't exist in the database
            echo json_encode(['status' => 'error', 'message' => 'Email not found']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
