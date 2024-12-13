<?php
// Database setup and table creation
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dacademe";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

$tableCreationQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contact VARCHAR(15) UNIQUE NOT NULL,
    picture VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($tableCreationQuery) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Handle form submission with AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact = htmlspecialchars(trim($_POST['contact']));

    // Handle file upload
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        echo json_encode(["status" => "error", "message" => "Failed to create upload directory."]);
        exit;
    } else {
        $uploadFile = $uploadDir . basename($_FILES["picture"]["name"]);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $uniqueFilename = $uploadDir . uniqid() . '.' . $imageFileType;

        // Check for duplicate entries
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR contact = ?");
        $stmt->bind_param("ss", $email, $contact);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "A user with this email or contact number already exists."]);
            exit;
        } else {
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowedTypes)) {
                if ($_FILES["picture"]["size"] <= 5 * 1024 * 1024) { // Max 5MB
                    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $uniqueFilename)) {
                        // Prepare and execute database insertion
                        $stmt = $conn->prepare("INSERT INTO users (name, email, contact, picture) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $name, $email, $contact, $uniqueFilename);

                        if ($stmt->execute()) {
                            echo json_encode(["status" => "success", "message" => "Record saved successfully!"]);
                        } else {
                            echo json_encode(["status" => "error", "message" => "Error saving record: " . htmlspecialchars($stmt->error)]);
                        }
                        $stmt->close();
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error uploading file."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "File size exceeds the 5MB limit."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed."]);
            }
        }
    }
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-green-100 flex items-center justify-center min-h-screen">
    <div class="flex flex-col items-center py-12 px-6 mt-32 min-h-screen content-container">
        <h2 class="text-3xl font-semibold text-center text-green-600 mb-6">Register Your Details</h2>

        <!-- Success or Error Messages -->
        <div id="message"></div>

        <form id="registerForm" method="POST" enctype="multipart/form-data" class="w-full max-w-xl bg-white p-8 rounded-lg shadow-lg">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" id="name" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Contact -->
            <div>
                <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="tel" name="contact" id="contact" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Profile Picture -->
            <div>
                <label for="picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" name="picture" id="picture" accept="image/*" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Submit -->
            <div class="flex justify-center">
                <button type="submit"
                        class="py-2 px-7 mt-5 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-500 focus:outline-none">
                    Register
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Handle form submission
            $("#registerForm").on("submit", function(e) {
                e.preventDefault();  // Prevent default form submission

                var formData = new FormData(this);

                $.ajax({
                    url: "userregister.php",  // URL to PHP script
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === "success") {
                            $("#message").html('<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">' +
                                                '<strong class="font-bold">Success!</strong>' + 
                                                '<span class="block sm:inline">' + res.message + '</span>' +
                                                '</div>');
                        } else {
                            $("#message").html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">' +
                                                '<strong class="font-bold">Error!</strong>' + 
                                                '<span class="block sm:inline">' + res.message + '</span>' +
                                                '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        $("#message").html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">' +
                                            '<strong class="font-bold">Error!</strong>' + 
                                            '<span class="block sm:inline">An error occurred while submitting the form.</span>' +
                                            '</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>