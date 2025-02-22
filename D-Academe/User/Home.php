<?php
// start session
// session_start();


include('dbconnection.php');  // Assuming this file contains your database connection code


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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Blockchain Development</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="./dist/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .hero-bg {
    background: linear-gradient(to bottom, #b2f7b5, #a0e9a1, #d4f7d1, #b2f7b5, #a0e9a1, #d4f7d1);
    background-size: cover;
    background-position: center;
    position: relative; /* Ensures the overlay is positioned correctly */
}

.hero-overlay {
    position: absolute; /* Covers the entire hero section */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3), #a0e9a1, #d4f7d1); /* Slight dark gradient for better text visibility */
    z-index: 1; /* Ensures the overlay is above the background */
}

    </style>
</head>

<body class="bg-gray-50">
    
<!-- Hero Section -->
<!-- <section class="h-[492px] md:h-[600px] flex items-center overflow-hidden relative [mask-image:linear-gradient(to_bottom,transparent,black_10%,black_90%,transparent)]" style="background-image: url('assets/stars.0c47b3bb.png'); background-position: 456.393px -55.8484px; will-change: auto; "> -->
 
<section class="relative w-full h-screen hero-bg ">
    <div class="absolute inset-0 hero-overlay"></div>
    <div class="relative z-10 flex flex-col justify-center items-center text-white text-center space-y-8 py-32 px-4">
            <div class="flex flex-col md:flex-row items-center justify-center space-y-8 md:space-y-0 md:space-x-8">
                <!-- Left Column -->
                <div class="text-center md:text-left space-y-8 md:w-1/2">
                    <!-- Gradient Animated Heading -->
                    <h1 
                        class="text-5xl sm:text-6xl font-extrabold leading-tight max-w-3xl mx-auto bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-red-500 to-blue-500 animate-gradientX" data-aos="fade-up" data-aos-delay="200">
                        Master Blockchain Development and Boost Your Career
                    </h1>
                    <!-- Paragraph with Subtle Fade-In Animation -->
                    <p 
                        class="text-xl sm:text-2xl max-w-2xl mx-auto opacity-90 hover:opacity-100 transition duration-300 ease-in-out animate-fadeInUp animate-delay-300" data-aos="fade-up" data-aos-delay="300">
                        Join a world-class blockchain course and get hands-on experience in building decentralized apps and smart contracts.
                    </p>
                    <!-- Call-to-Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                        <a href="userregister-form.php" class="bg-green-600 hover:bg-green-700 text-white py-3 px-10 rounded-full text-lg shadow-lg shadow-green-500/50 transform hover:scale-110 transition duration-300 ease-in-out animate-bounce" data-aos="fade-up" data-aos-delay="500">
                            Get Started
                        </a>
                        <a href="/learn-more" class="text-white border-2 border-white py-3 px-10 rounded-full text-lg shadow-lg hover:shadow-pink-500/50 transition duration-300 ease-in-out hover:bg-white hover:text-gray-900" data-aos="fade-up" data-aos-delay="600">
                            Learn More
                        </a>
                    </div>
                </div>
                <!-- Right Column -->
                <div class="flex flex-col hover:scale-105 space-y-4 md:w-1/2">
                    <img src="./assets/img.webp" alt="Blockchain Course" class="w-full h-96 object-cover rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:opacity-75" data-aos="fade-up" data-aos-delay="400">
                </div>
            </div>
        
        <!-- Search Bar -->
        <div class="w-full max-w-lg relative mx-auto mb-8">
    <div class="flex items-center border border-gray-300 rounded-full bg-white shadow-lg focus-within:ring-2 focus-within:ring-green-600 relative">
        <input
            type="text"
            placeholder="Search Courses..."
            id="searchInput"
            class="w-full py-3 px-6 rounded-full text-gray-900 placeholder-gray-500 bg-green-200 focus:outline-none"
            
            oninput="showSuggestions()"
        />
        <div id="suggestions" class="absolute top-full left-0 w-full bg-white border border-gray-300 text-left rounded-lg max-h-48 overflow-y-auto shadow-lg mt-1 hidden z-10"></div>
    </div>
</div>
    </div>
</section>
     <!-- Free course section -->
     <section id="freeCourses" class="max-w-full mx-auto py-[24px] sm:py-14 bg-gray-700">
            <h2 class="text-center text-3xl font-bold text-white mb-6">Free Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="freeCourseContainer"></div>
        </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-green-300 text-black" id="why-us">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold mb-12" data-aos="fade-up" data-aos-delay="200">Why Choose Us?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
                <!-- Benefit 1 -->
                <div class="flex flex-col items-center space-y-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-5xl mb-4">🎓</div>
                    <h3 class="text-2xl font-semibold">Certified Courses</h3>
                    <p class="text-lg">Get recognized certifications from industry leaders to advance your career.</p>
                </div>

                <!-- Benefit 2 -->
                <div class="flex flex-col items-center space-y-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-5xl mb-4">💸</div>
                    <h3 class="text-2xl font-semibold">Affordable Pricing</h3>
                    <p class="text-lg">Choose from free and affordable premium courses that fit your budget.</p>
                </div>

                <!-- Benefit 3 -->
                <div class="flex flex-col items-center space-y-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-5xl mb-4">👨‍🏫</div>
                    <h3 class="text-2xl font-semibold">Expert Instructors</h3>
                    <p class="text-lg">Learn from the best in the field with hands-on experience and guidance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-green-200 text-center" id="cta">
    <div class="container mx-auto">
        <h2 class="text-4xl font-bold text-gray-900 mb-8" data-aos="fade-up" data-aos-delay="200">Ready to Start Your Blockchain Journey?</h2>
        <a href="userregister-form.php" class="bg-green-600 hover:bg-green-700 text-white py-4 px-10 rounded-full text-lg transition duration-300 ease-in-out" data-aos="fade-up" data-aos-delay="400">Join Now</a>
    </div>
</section>


    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-out-back',
            once: false,
        });
    </script>
    <script>
        // Check if the wallet is connected by verifying an account stored in localStorage
        let isConnected = false;

        function isWalletConnected() {
            // Check if an account is stored in localStorage
            const account = localStorage.getItem('account');
            return !!account; // Returns true if account exists, otherwise false
        }

        // Function to handle redirection if the wallet is connected
        function redirectToPage(url) {
            if (isWalletConnected()) {
                // Redirect to the specified URL
                window.location.href = url;
            } else {
                // Notify user to connect their wallet
                alert('Please connect your wallet to enroll in this course.');
            }
        }
</script>

<!-- Search JavaScript -->
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
 
<script>
    async function fetchFreeCourses() {
        try {
            const response = await fetch('getfreecourses.php');
            const freeCourses = await response.json();

            const freeCourseContainer = document.querySelector('#freeCourseContainer');
            freeCourseContainer.innerHTML = ''; // Clear existing free courses

            freeCourses.forEach(course => {
                const courseCard = `
                    <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-2lg transition-all duration-300 transform hover:scale-105 p-4 border border-gray-200 hover:border-green-400 relative max-w-xs mx-auto mb-4" data-course-id="${course.id}" data-tags="${course.tags}">
                        <img src="${course.image}" alt="Course Image" class="w-full h-36 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
                        <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">${course.name}</h3>
                        <p class="text-xl font-bold text-green-600 mt-4">Tkn ${course.token_price}</p>
                        <div class="course-description mt-4">
                            <p class="text-gray-600">${course.description}</p>
                        </div>
                        <div class="mt-6 flex gap-4 justify-center">
                            <!-- View button -->
                           <!-- Enrollment button within the course card -->
                                <button  
                                    onClick="viewAndEnrollCourse({
                                         id: '${course.id}',
                                        name: '${course.name}',
                                    }, user)" 
                                    class="bg-green-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg">
                                    Enroll Now
                                </button>
                        </div>
                    </div>
                `;

                freeCourseContainer.insertAdjacentHTML('beforeend', courseCard);
            });
        } catch (error) {
            console.error('Error fetching free courses:', error);
        }
    }
    // View course function
    // function viewCourse(courseName) {
    //     // Redirect to viewcourse.php with the courseName as a URL parameter
    //     window.location.href = `freeviewcourse.php?course_name=${encodeURIComponent(courseName)}`;
    // }
    // Call this function when the document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        fetchFreeCourses();    // Fetch free courses
    });
</script>
<script>
// Assuming `user_id` and `user_name` are coming from the PHP session
const user = {
    id: <?php echo json_encode($user_id); ?>,
    name: <?php echo json_encode($user_name); ?>
};
// Function to perform two actions: view and enroll in the course
const viewAndEnrollCourse = (course, user) => {
    // 1. View the course (redirect to course view page)
    viewCourse(course.name);

    // 2. Enroll the user in the course
    enrollInCourse(course.id, user);
};

// Function to view the course
const viewCourse = (courseName) => {
    window.location.href = `freeviewcourse.php?course_name=${encodeURIComponent(courseName)}`;
};

// Function to enroll in the course
const enrollInCourse = async (courseId, user) => {
    try {
        const response = await fetch("enroll.php", {
            method: "POST",
            body: JSON.stringify({
                courseId: courseId,  // Send the correct courseId
                userId: user.id,     // Include the user ID
                userName: user.name, // Include the user Name
            }),
        });

        const data = await response.json();
        console.log("Response:", data);  // Log response for debugging

        if (response.ok && data.status === "success") {
            alert("Successfully enrolled in the course!");
        } else {
            alert(`User is already enrolled in this course`);
        }
    } catch (error) {
        console.error("Error enrolling in course:", error);
         alert("Failed to enroll in the course.");
    }
};



</script>
</body>

</html>