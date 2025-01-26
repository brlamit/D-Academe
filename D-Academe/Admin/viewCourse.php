<?php
include('header.php');
// Include your database connection
require_once 'dbconnection.php'; // Ensure this file correctly establishes a connection

// Initialize variables
$courseId = $name = $description = $tokenPrice = $image = $courseContent = ''; // Added courseContent

// Check if course details are provided in the URL
if (isset($_GET['course_id'])) {
    $courseName = $_GET['course_id'];
    $sql = "SELECT * FROM courses WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $courseName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $imageUrl = $row['image'];  // Fetch image URL
            $description = $row['description'];
            $courseContent = $row['course_content']; // Fetch course content URL or details
        } else {
            $message = "Course not found!";
        }
        $stmt->close();
    } else {
        $message = "Error fetching course details!";
    }
} else {
    $message = "No course ID provided!";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">

<!-- Course Details Layout -->
<section class="bg-black p-8 rounded-xl shadow-2xl w-full mx-auto mt-16 flex-1">
    <!-- Go Back Button -->
    <button type="button" onclick="goBack()"
        class="bg-transparent text-white py-2 px-4 text-sm rounded-lg font-semibold hover:text-teal-800 transition-all shadow-md">
        Go Back
    </button>
    
    <!-- Page Title -->
    <h1 class="text-4xl font-extrabold text-gray-400 text-center mt-8">Course Details</h1>

    <!-- Display Message if Course Not Found -->
    <?php if (isset($message)) { ?>
        <div class="text-center text-red-500 mt-4">
            <p><?php echo htmlspecialchars($message); ?></p>
        </div>
    <?php } else { ?>
        <!-- Course Details -->
        <div class="flex gap-8 mt-8">
            <!-- Left Column for Course Image, Name, and Description -->
            <div class="flex-1 max-w-xs">
                <!-- Course Image -->
                <?php if ($imageUrl): ?>
                    <div class="mt-4">
                        <h3 class="text-lg text-gray-400">Course Image</h3>
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Course Image" class="mt-2 w-32 h-12 rounded-md shadow-sm">
                    </div>
                <?php endif; ?>

                <!-- Course Name -->
                <h2 class="text-3xl font-semibold text-white mt-4"><?php echo htmlspecialchars($name); ?></h2>

                <!-- Course Description -->
                <div class="mt-4">
                    <h3 class="text-lg text-gray-400">Description</h3>
                    <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($description)); ?></p>
                </div>
            </div>

            <!-- Right Column for Course Content -->
            <div class="flex-1 flex flex-col w-full h-full">
                <h3 class="text-xl font-semibold text-white">Course Content</h3>
                <div class="mt-4">
    <?php if ($courseContent): ?>
        <button onclick="toggleCourseContent()" class="text-teal-500 hover:underline">
            Access Course Content
        </button>
        <div id="course-content" class="hidden mt-4 bg-gray-800 p-4 rounded-lg">
            <div id="content-container" class="w-full"></div>
        </div>
    <?php else: ?>
        <p class="text-gray-300">No course content available.</p>
    <?php endif; ?>
</div>

            </div>

        </div>
    <?php } ?>

</section>

<script>
   // Function to toggle the visibility of course content
function toggleCourseContent() {
    const content = document.getElementById('course-content');
    const contentContainer = document.getElementById('content-container');

    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        loadContent(contentContainer);

        // Scroll to the course content section
        content.scrollIntoView({ behavior: 'smooth' });
    } else {
        content.style.display = 'none';
    }
}

// Load content dynamically based on file type or URL
function loadContent(container) {
    const courseContentUrl = '<?php echo addslashes($courseContent); ?>'; // PHP variable into JS

    const fileExtension = courseContentUrl.split('.').pop().toLowerCase();

    if (isUrl(courseContentUrl)) {
        // If it's a URL (external content), embed it
        container.innerHTML = `<iframe src="${courseContentUrl}" class="w-full h-[600px]" frameborder="0"></iframe>`;
    } else if (fileExtension === 'pdf') {
        // For PDF files
        container.innerHTML = `<iframe src="${courseContentUrl}" class="w-full h-[600px]" frameborder="0"></iframe>`;
    } else if (fileExtension === 'md') {
        // For markdown files
        fetch(courseContentUrl)
            .then(response => response.text())
            .then(data => {
                container.innerHTML = `<pre class="text-gray-300">${data}</pre>`;
            })
            .catch(error => {
                container.innerHTML = '<p class="text-red-500">Error loading markdown file.</p>';
            });
    } else {
        // Handle unsupported files
        container.innerHTML = '<p class="text-red-500">Unsupported file or URL format.</p>';
    }
}

// Function to check if the given string is a valid URL
function isUrl(str) {
    const pattern = /^(https?:\/\/|www\.)[a-zA-Z0-9\-._~:\/?#[\]@!$&'()*+,;=]+$/;
    return pattern.test(str);
}

// Go back to the previous page
function goBack() {
    window.history.back();
}

</script>

</body>
</html>
