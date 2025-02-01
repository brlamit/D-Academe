<?php
$host = 'localhost'; // Replace with your database host
$db = 'dacademe';    // Replace with your database name
$user = 'root';       // Replace with your database user
$pass = '';           // Replace with your database password

header('Content-Type: application/json'); // Ensure the response is JSON

try {
     // Create a connection to MySQL server without specifying a database
     $pdo = new PDO("mysql:host=$host", $user, $pass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
     // Create the `dacademe` database if it does not exist
     $createDatabaseSQL = "CREATE DATABASE IF NOT EXISTS $db";
     $pdo->exec($createDatabaseSQL);
 
     // Connect to the newly created database
     $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the `login` table if it doesn't exist
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS admin_login (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            phone_number VARCHAR(15) UNIQUE,
            password VARCHAR(255) NOT NULL,
            profile_picture VARCHAR(255),
            license VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            reset_token VARCHAR(64) DEFAULT NULL,
            expiry INT NOT NULL
        )
    ";
    $pdo->exec($createTableSQL);

    // Only handle POST requests for signup
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get POST data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            exit;
        }

        // Check if the email or phone number already exists
        $stmt = $pdo->prepare("SELECT * FROM admin_login WHERE email = ? OR phone_number = ?");
        $stmt->execute([$email, $phone_number]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            if ($existingUser['email'] === $email) {
                echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Phone number already exists']);
            }
            exit;
        }

        // Handle profile picture upload
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = time() . '_' . $_FILES['profile_picture']['name']; // Unique file name
            $fileDestPath = 'login/uploads/profile_pictures/' . $fileName;

            // Ensure the directory exists
            if (!is_dir('login/uploads/profile_pictures/')) {
                mkdir('login/uploads/profile_pictures/', 0777, true);
            }

            // Move the uploaded file
            if (move_uploaded_file($fileTmpPath, $fileDestPath)) {
                $profile_picture = $fileDestPath;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Profile picture upload failed']);
                exit;
            }
        }

        // Handle license upload
        $license = null;
        if (isset($_FILES['license']) && $_FILES['license']['error'] === UPLOAD_ERR_OK) {
            $licenseTmpPath = $_FILES['license']['tmp_name'];
            $licenseName = time() . '_' . $_FILES['license']['name']; // Unique file name
            $licenseDestPath = 'login/uploads/licenses/' . $licenseName;

            // Ensure the directory exists
            if (!is_dir('login/uploads/licenses/')) {
                mkdir('login/uploads/licenses/', 0777, true);
            }

            // Move the uploaded file
            if (move_uploaded_file($licenseTmpPath, $licenseDestPath)) {
                $license = $licenseDestPath;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'License upload failed']);
                exit;
            }
        }

        // Insert the new user into the database
        $stmt = $pdo->prepare("
            INSERT INTO admin_login (name, email, phone_number, password, profile_picture, license)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $email, $phone_number, $password, $profile_picture, $license]);

        // Return a success response
        echo json_encode(['status' => 'success', 'message' => 'Account created successfully']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
