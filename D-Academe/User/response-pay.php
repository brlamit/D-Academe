<?php
include 'dbconnection.php';
session_start();

// Get user ID from session
$user_id = $_SESSION['id'] ?? null;

// Get the pidx from the URL
$pidx = $_GET['pidx'] ?? null;
$course_id = intval($_GET['course_id'] ?? 0); // Extract course ID from URL

if ($pidx && $user_id) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
        CURLOPT_HTTPHEADER => [
            'Authorization: Key 55561f78d07a4991877e810edde844cd', // Replace with env variable
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    curl_close($curl);

    // Debugging: Print API Response
    echo "<pre>API Response: ";
    print_r($response);
    echo "</pre>";

    if ($curlError) {
        die('❌ Curl Error: ' . $curlError);
    }

    $responseArray = json_decode($response, true);

    // Debugging: Check JSON-decoded response
    echo "<pre>Decoded Response: ";
    print_r($responseArray);
    echo "</pre>";

    // Check if API response contains the required fields
    if (!isset($responseArray['status']) || !isset($responseArray['total_amount'])) {
        die('❌ No valid payment data received. Please try again.');
    }

    // Extract data
    $amount = $responseArray['total_amount'] ?? 0;
    $status = $responseArray['status'] ?? 'Failed';
    $transaction_id = $responseArray['transaction_id'] ?? '';
    $fee = $responseArray['fee'] ?? 0;
    $refunded = $responseArray['refunded'] ?? false;

    // Ensure enrollments table exists (Updated to include `transaction_id`)
    $createTableQuery = "CREATE TABLE IF NOT EXISTS paid_course_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        user_name VARCHAR(255) NOT NULL,
        course_id INT NOT NULL,
        course_name VARCHAR(255) NOT NULL,
        course_price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        content TEXT DEFAULT NULL,
        transaction_id VARCHAR(255) NOT NULL,
        status ENUM('Enrolled', 'Completed') DEFAULT 'Enrolled',
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if (!$conn->query($createTableQuery)) {
        die("❌ Table creation failed: " . $conn->error);
    }

    // Check if payment is successful
    if ($status === 'Completed' && $course_id) {
        // Fetch course details
        $query = "SELECT * FROM courses WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die(json_encode(["error" => "❌ Course not found."]));
        }

        $course = $result->fetch_assoc();
        $courseName = $course['name'] ?? '';
        $coursePrice = floatval($course['price'] ?? 0.0);
        $courseImage = $course['image'] ?? null;
        $courseDescription = $course['description'] ?? null;
        $courseContent = $course['course_content'] ?? null;

        // Fetch user name
        $userQuery = "SELECT name FROM users WHERE id = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("i", $user_id);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userData = $userResult->fetch_assoc();
        $userName = $userData['name'] ?? 'Unknown';

        // Check if user is already enrolled
        $checkEnrollment = "SELECT * FROM paid_course_enrollments WHERE user_id = ? AND course_id = ?";
        $checkStmt = $conn->prepare($checkEnrollment);
        $checkStmt->bind_param("ii", $user_id, $course_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            die(json_encode(["error" => "❌ You are already enrolled in this course."]));
        }

        // Enroll user in course (Fixed column order & ensured transaction_id is included)
        $stmt = $conn->prepare("INSERT INTO paid_course_enrollments (user_id, user_name, course_id, course_name, course_price, image, description, content, transaction_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issdsssss", $user_id, $userName, $course_id, $courseName, $coursePrice, $courseImage, $courseDescription, $courseContent, $transaction_id);
            $message = $stmt->execute() ? 
                '✅ Payment successful! You are enrolled in the course.' :
                '❌ Error enrolling in course: ' . $stmt->error;
            $stmt->close();
        } else {
            $message = '❌ Database error: ' . $conn->error;
        }
    } else {
        $message = '❌ Payment failed or invalid course ID.';
    }
} else {
    $message = '❌ Pidx or user ID missing.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Response</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title">Payment Response</h5>
            </div>
            <div class="card-body">
                <p class="card-text"><?= htmlspecialchars($message) ?></p>
                <a href="index.php?page=enrolled-course" class="btn btn-primary">Go to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
