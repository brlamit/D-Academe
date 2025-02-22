<?php
// Start session
session_start();

include('dbconnection.php'); // Database connection

if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    // header("Location: ./login/user_login.html");
    // exit();
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear message after displaying it once
}

// Get user details from session
$user_id = $_SESSION['id'] ?? null;
$user_email = $_SESSION['email'] ?? null;
$user_name = $_SESSION['name'] ?? null;

// Fetch enrolled courses for this user
$enrolled_courses = [];
if ($user_id) {
    $query = "SELECT * FROM course_enrollments WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $enrolled_courses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #b2f7b5, #a0e9a1, #d4f7d1, #b2f7b5, #a0e9a1, #d4f7d1);
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3), #a0e9a1, ); /* Slight dark gradient for better text visibility */
        }
        #suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            display: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .active-suggestion {
            background-color: #e2e8f0;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<section class="py-16">
    <div class="w-full max-w-lg relative mx-auto mb-8">
        <div class="flex items-center border border-gray-300 rounded-full bg-white shadow-lg focus-within:ring-2 focus-within:ring-green-600 relative">
            <input type="text" placeholder="Search Courses..." id="searchInput" class="w-full py-3 px-6 rounded-full text-gray-900 placeholder-gray-500 bg-white focus:outline-none" />
            <div id="suggestions"></div>
        </div>
    </div>
    
    <div class="container mx-auto text-center bg-green-300">
        <br>
        <h2 class="text-4xl font-semibold text-green-800 mb-8">Your Enrolled Courses</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (count($enrolled_courses) > 0): ?>
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="course-card bg-white rounded-xl shadow-lg p-1 border border-gray-300 hover:shadow-2lg transition-all duration-300 transform hover:scale-105 hover:border-green-400 relative max-w-xs" data-tags="<?= htmlspecialchars($course['course_name']); ?>">
                        <img src="<?= htmlspecialchars($course['image']); ?>" alt="Course Image" class="w-full h-48 object-cover rounded-lg mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($course['course_name']); ?></h3>
                        <p class="text-lg text-gray-600 mt-4"><?= htmlspecialchars($course['description']); ?></p>
                        <p class="text-xl font-bold text-green-600 mt-4">Tkn <?= htmlspecialchars($course['course_price']); ?></p>
                        <p class="mt-2 text-gray-500">Status: <?= htmlspecialchars($course['status']); ?></p>

                        <div class="mt-6">
                            <button onclick="window.location.href='freeviewcourse.php?course_name=<?= htmlspecialchars($course['course_name']); ?>'" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg transition-all duration-300 ease-in-out">
                                View Course
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-lg text-gray-600">You have not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
        <br>
    </div>
</section>

<script>
    function filterCourses(query) {
        const courseCards = document.querySelectorAll('.course-card');
        courseCards.forEach(card => {
            const cardTags = card.dataset.tags ? card.dataset.tags.split(',') : [];
            card.style.display = cardTags.some(tag => tag.toLowerCase().includes(query.toLowerCase())) ? '' : 'none';
        });
    }

    function displaySuggestions(query) {
        const suggestionsContainer = document.getElementById('suggestions');
        suggestionsContainer.innerHTML = '';
        let selectedIndex = -1;

        if (query !== '') {
            const tags = [...document.querySelectorAll('.course-card')].map(card => card.dataset.tags);
            const filteredTags = tags.filter(tag => tag.toLowerCase().includes(query.toLowerCase()));

            filteredTags.forEach(tag => {
                const suggestion = document.createElement('div');
                suggestion.className = 'px-4 py-2 cursor-pointer hover:bg-gray-200 text-gray-900';
                suggestion.textContent = tag;
                suggestion.addEventListener('click', () => {
                    document.getElementById('searchInput').value = tag;
                    filterCourses(tag);
                    suggestionsContainer.style.display = 'none';
                });
                suggestionsContainer.appendChild(suggestion);
            });
            suggestionsContainer.style.display = 'block';
        } else {
            suggestionsContainer.style.display = 'none';
        }
    }

    document.getElementById('searchInput').addEventListener('input', e => {
        const query = e.target.value;
        displaySuggestions(query);
        filterCourses(query);
    });

    document.addEventListener('click', e => {
        const suggestionsContainer = document.getElementById('suggestions');
        if (!suggestionsContainer.contains(e.target) && e.target.id !== 'searchInput') {
            suggestionsContainer.style.display = 'none';
        }
    });
</script>
</body>
</html>
