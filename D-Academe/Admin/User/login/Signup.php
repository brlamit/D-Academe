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

    // Create the login table if it doesn't exist (only once)
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS user_login (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone_number VARCHAR(15) UNIQUE,
            password VARCHAR(255) NOT NULL,
            profile_picture VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            reset_token VARCHAR(64) DEFAULT NULL,
            expiry INT NOT NULL
        )
    ";
    $pdo->exec($createTableSQL);

    // Only handle POST requests for signup
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get POST data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            exit; // Stop further execution after returning the error
        }

        // Check if the email or phone number already exists
        $stmt = $pdo->prepare("SELECT * FROM login WHERE email = ? OR phone_number = ?");
        $stmt->execute([$email, $phone_number]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            if ($existingUser['email'] === $email) {
                echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Phone number already exists']);
            }
            exit; // Stop further execution after returning the error
        }

        // Handle profile picture upload
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileDestPath = 'uploads/profile_pictures/' . $fileName;

            // Ensure the directory exists
            if (!is_dir('uploads/profile_pictures/')) {
                mkdir('uploads/profile_pictures/', 0777, true); // Create directory if it doesn't exist
            }

            // Move the uploaded file to the desired location
            if (move_uploaded_file($fileTmpPath, $fileDestPath)) {
                $profile_picture = $fileDestPath; // Save the path of the file in the database
            } else {
                echo json_encode(['status' => 'error', 'message' => 'File upload failed']);
                exit; // Stop further execution after returning the error
            }
        }

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO login (username, email, phone_number, password, profile_picture) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $phone_number, $password, $profile_picture]);

        // Return a success response
        echo json_encode(['status' => 'success', 'message' => 'Account created successfully']);
        exit; // Stop further execution after returning the success response
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit; // Stop further execution after returning the error
}
