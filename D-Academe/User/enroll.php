<?php

include('dbconnection.php');  // Assuming this file contains your database connection code

header("Content-Type: application/json");

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"));

if ($data === null) {
    echo json_encode(["message" => "Invalid JSON"]);
    exit();
}

// Ensure table exists
$createTableQuery = "CREATE TABLE IF NOT EXISTS course_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_account VARCHAR(255) NOT NULL,
    course_id INT NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    course_price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    content TEXT DEFAULT NULL,
    status ENUM('Enrolled', 'Completed') DEFAULT 'Enrolled',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createTableQuery)) {
    die(json_encode(["success" => false, "error" => "Failed to create table"]));
}

// Check if required data is received
if (isset($data->userId) && isset($data->courseId)) {
    $userId = mysqli_real_escape_string($conn, $data->userId);
    $courseId = mysqli_real_escape_string($conn, $data->courseId);

    // Fetch course details based on courseId
    $query = "SELECT * FROM free_courses WHERE id = '$courseId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);

        // Extract course details
        $courseName = $course['name'];
        $courseDescription = $course['description'];
        $courseContent = $course['course_content'];

        // Fetch user details (if needed, assuming a users table exists)
        $userQuery = "SELECT * FROM user_login WHERE id = '$userId'";
        $userResult = mysqli_query($conn, $userQuery);

        if ($userResult && mysqli_num_rows($userResult) > 0) {
            $user = mysqli_fetch_assoc($userResult);
            $userName = $user['name'];  // Assuming there's a 'name' field for the user

            // Insert enrollment into course_enrollments table
            $insertQuery = "INSERT INTO course_enrollments (user_id, user_name, course_id, course_name, course_description, course_content) 
                            VALUES ('$userId', '$userName', '$courseId', '$courseName', '$courseDescription', '$courseContent')";

            if (mysqli_query($conn, $insertQuery)) {
                echo json_encode(["message" => "Enrollment successful"]);
            } else {
                echo json_encode(["message" => "Enrollment failed", "error" => mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["message" => "User not found"]);
        }
    } else {
        echo json_encode(["message" => "Course not found"]);
    }
} else {
    echo json_encode(["message" => "Invalid request"]);
}

?>