<?php
include('dbconnection.php');  
session_start();  

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['id'];
$user_name = $_SESSION['name'];

// Read JSON input
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["error" => "Invalid JSON received"]);
    exit();
}

$courseId = intval($data->courseId ?? 0);

if ($courseId <= 0) {
    echo json_encode(["error" => "Invalid Course ID"]);
    exit();
}

// Ensure enrollments table exists
$createTableQuery = "CREATE TABLE IF NOT EXISTS course_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    course_id INT NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    course_price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    content TEXT DEFAULT NULL,
    status ENUM('Enrolled', 'Completed') DEFAULT 'Enrolled',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($createTableQuery);

// Fetch course details
$query = "SELECT * FROM free_courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Course not found"]);
    exit();
}

$course = $result->fetch_assoc();
$courseName = $course['name'] ?? '';
$coursePrice = $course['price'] ?? 0.0;
$courseImage = $course['image'] ?? null;
$courseDescription = $course['description'] ?? null;
$courseContent = $course['course_content'] ?? null;

// Check if user is already enrolled
$checkEnrollment = "SELECT * FROM course_enrollments WHERE user_id = ? AND course_id = ?";
$checkStmt = $conn->prepare($checkEnrollment);
$checkStmt->bind_param("ii", $user_id, $courseId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["error" => "User is already enrolled in this course"]);
    exit();
}

// Enroll the user
$insertQuery = "INSERT INTO course_enrollments (user_id, user_name, course_id, course_name, course_price, image, description, content) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bind_param("isisdsss", $user_id, $user_name, $courseId, $courseName, $coursePrice, $courseImage, $courseDescription, $courseContent);

if ($insertStmt->execute()) {
    echo json_encode(["success" => "Enrollment successful"]);
} else {
    echo json_encode(["error" => "Enrollment failed", "mysql_error" => $insertStmt->error]);
}
?>
