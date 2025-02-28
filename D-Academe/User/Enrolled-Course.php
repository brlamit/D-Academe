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
    $free_courses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $sql = "SELECT * FROM paid_course_enrollments WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $results = $stmt->get_result();
    $paid_courses = $results->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $courseId = $row['course_id'];
    
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
            background: linear-gradient(to bottom, #b2f7b5, #a0e9a1, #d4f7d1);
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
           <!-- Search Bar -->
           <div class="w-full max-w-lg relative mx-auto mb-8">
            <div class="w-full max-w-lg relative mx-auto mb-8">
            <div class="flex items-center border border-gray-300 rounded-full bg-white shadow-lg focus-within:ring-2 focus-within:ring-green-600 relative">
                <input
                    type="text"
                    placeholder="Search Courses..."
                    id="searchInput"
                    class="w-full py-3 px-6 rounded-full text-gray-900 placeholder-gray-500 bg-white focus:outline-none"
                />
                <div id="suggestions"></div>
            </div>
        </div>
            </div>
    </div>
    
    <div class="container mx-auto text-center bg-green-300 p-6 rounded-lg shadow-lg mb-8">
        <h2 class="text-4xl font-semibold text-green-800 mb-8">Your Enrolled Free Courses</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($free_courses)): ?>
                <?php foreach ($free_courses as $course): ?>
                    <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-2lg transition-all duration-300 transform hover:scale-105 p-4 border border-gray-200 hover:border-green-400 relative max-w-xs mx-auto mb-4" data-tags="<?= htmlspecialchars($course['course_name']); ?>">
                        <img src="<?= htmlspecialchars($course['image']); ?>" alt="Course Image" class="w-full h-36 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
                        <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300"><?= htmlspecialchars($course['course_name']); ?></h3>
                        <p class="text-lg text-gray-600 mt-2"><?= htmlspecialchars($course['description']); ?></p>
                        <p class="text-xl font-bold text-green-600 mt-4">Tkn <?= htmlspecialchars($course['course_price']); ?></p>
                        <p class="mt-2 text-gray-500">Status: <?= htmlspecialchars($course['status']); ?></p>
                        <div class="mt-6 flex gap-4 justify-center">
                            <button onclick="window.location.href='freeviewcourse.php?course_name=<?= urlencode($course['course_name']); ?>'" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg">
                                View Course
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-lg text-gray-600">You have not enrolled in any free courses yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mx-auto text-center bg-green-300 p-6 rounded-lg shadow-lg">
        <h2 class="text-4xl font-semibold text-blue-800 mb-8">Your Enrolled Paid Courses</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($paid_courses)): ?>
                <?php foreach ($paid_courses as $course): ?>
                    <div class="course-card bg-white rounded-xl shadow-xl p-4 border border-gray-200 hover:border-blue-400 relative max-w-xs mx-auto mb-4" data-tags="<?= htmlspecialchars($course['course_name']); ?>">
                        <img src="<?= htmlspecialchars($course['image']); ?>" alt="Course Image" class="w-full h-36 object-cover rounded-lg mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($course['course_name']); ?></h3>
                        <p class="text-lg text-gray-600"><?= htmlspecialchars($course['description']); ?></p>
                        <p class="text-xl font-bold text-green-600 mt-4">Tkn <?= htmlspecialchars($course['course_price']); ?></p>
                        <p class="mt-2 text-gray-500">Status: <?= htmlspecialchars($course['status']); ?></p>
                        <div class="mt-6 flex gap-4 justify-center"></div>
                        <button onclick="window.location.href='viewCourse.php?course_id=<?= urlencode($course['course_id']); ?>'" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg mt-4">View</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-lg text-gray-600">You have not enrolled in any paid courses yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
     function getTags() {
        const courseCards = document.querySelectorAll('.course-card');
        const tags = new Set();
        courseCards.forEach(card => {
            const cardTags = card.getAttribute('data-tags').split(',');
            cardTags.forEach(tag => tags.add(tag.trim()));
        });
        return [...tags].sort();
    }

    function displaySuggestions(query) {
        const tags = getTags();
        const filteredTags = tags.filter(tag => tag.toLowerCase().includes(query.toLowerCase()));

        const suggestionsContainer = document.getElementById('suggestions');
        suggestionsContainer.innerHTML = '';
        selectedIndex = -1; // Reset selection index

        if (filteredTags.length > 0 && query !== '') {
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

    function navigateSuggestions(event) {
        const suggestionsContainer = document.getElementById('suggestions');
        const suggestions = suggestionsContainer.querySelectorAll('div');

        if (!suggestions.length) return;

        if (event.key === 'ArrowDown') {
            if (selectedIndex < suggestions.length - 1) {
                selectedIndex++;
                updateSelectedSuggestion(suggestions);
            }
        } else if (event.key === 'ArrowUp') {
            if (selectedIndex > 0) {
                selectedIndex--;
                updateSelectedSuggestion(suggestions);
            }
        } else if (event.key === 'Enter') {
            if (selectedIndex >= 0) {
                const selectedSuggestion = suggestions[selectedIndex];
                document.getElementById('searchInput').value = selectedSuggestion.textContent;
                filterCourses(selectedSuggestion.textContent);
                suggestionsContainer.style.display = 'none';
            }
        }
    }

    function updateSelectedSuggestion(suggestions) {
        suggestions.forEach(suggestion => suggestion.classList.remove('active-suggestion'));
        if (selectedIndex >= 0) {
            suggestions[selectedIndex].classList.add('active-suggestion');
            suggestions[selectedIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    function filterCourses(query) {
        const courseCards = document.querySelectorAll('.course-card');
        if (query === '') {
            // Show all courses if the search input is empty
            courseCards.forEach(card => {
                card.style.display = '';
            });
        } else {
            courseCards.forEach(card => {
                const cardTags = card.getAttribute('data-tags').split(',');
                card.style.display = cardTags.some(tag => tag.toLowerCase().includes(query.toLowerCase())) ? '' : 'none';
            });
        }
    }

    document.getElementById('searchInput').addEventListener('input', e => {
        const query = e.target.value;
        displaySuggestions(query);
        filterCourses(query);
    });

    document.getElementById('searchInput').addEventListener('keydown', navigateSuggestions);

    document.addEventListener('DOMContentLoaded', fetchCourses);

    document.addEventListener('click', e => {
        const suggestionsContainer = document.getElementById('suggestions');
        if (!suggestionsContainer.contains(e.target) && e.target.id !== 'searchInput') {
            suggestionsContainer.style.display = 'none';
        }
    });
</script>


</body>
</html>
