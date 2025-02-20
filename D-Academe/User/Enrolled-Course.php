<?php
// Include database connection
include("dbconnection.php");

session_start();

if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: ./login/user_login.html");
    exit();
}
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying it once
}

// Get user details from session
$user_id = $_SESSION['id'];
$user_email = $_SESSION['email'];
$user_name = $_SESSION['name'];
// Fetch enrolled courses for this user
$query = "SELECT * FROM course_enrollments WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$enrolled_courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Enrolled Courses Section -->
<section class="py-16 ">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-semibold text-gray-900 mb-8">Your Enrolled Courses</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($enrolled_courses) > 0): ?>
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-300">
                        <img src="<?= htmlspecialchars($course['image']); ?>" alt="Course Image" class="w-full h-48 object-cover rounded-lg mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($course['course_name']); ?></h3>
                        <p class="text-lg text-gray-600 mt-4"><?= htmlspecialchars($course['description']); ?></p>
                        <p class="text-xl font-bold text-green-600 mt-4">Tkn <?= htmlspecialchars($course['course_price']); ?></p>
                        <p class="mt-2 text-gray-500">Status: <?= htmlspecialchars($course['status']); ?></p>

                        <div class="mt-6">
                            <!-- View Course Button -->
                            <button 
                                onclick="window.location.href='freeviewcourse.php?course_name=<?= htmlspecialchars($course['course_name']); ?>'" 
                                class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg transition-all duration-300 ease-in-out">
                                View Course
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-lg text-gray-600">You have not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

</body>
</html>
