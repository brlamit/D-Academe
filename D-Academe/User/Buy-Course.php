<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Tokens</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3/dist/web3.min.js"></script>
    <style>
        body {
            margin-top: 100px;
        }
        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            transition: background-color 0.3s ease;
            z-index: 50;
        }
        .container {
            margin-top: 120px;
        }
        #suggestions {
            position: absolute;
            top: 100%; /* Ensures the dropdown appears right below the input */
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
     <link rel="preload" as="image" href="/Free-Course-Contents/Clarity/assets/logo.svg"/>
     <link rel="preload" as="image" href="/Free-Course-Contents/Solidity/assets/sol.webp"/>
</head>
<body class="bg-gray-500 text-white flex items-center justify-center min-h-screen">
<section class="max-w-full mx-auto py-[24px] sm:py-14 overflow-x-clip left-6 right-6 ">
                <div class=" text-white py-[12px] sm:py-6 left-6 right-6">
                <h1 class="text-4xl text-center text-green-500">Top Course&#x27;s we offer.</h1>
                       
                    <!-- <div class="container mx-auto flex flex-col items-center"> -->
                         <div class="flex overflow-hidden mt-10">
                            <div class="flex gap-16 flex-none pr-16 -translate-x-1/2" style="will-change:transform;transform:translateX(-50%)">
                               <!-- mg src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/> -->
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="logo" class="h-10 w-auto"/>
                                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="clar" class="h-10 w-auto"/>
                               
                            </div>
                        </div>
                </div>
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
            </section>


            
<section id="courses" class="max-w-full mx-auto py-[24px] sm:py-14 bg-gray-800">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="courseContainer"></div>
</section>

<!-- Free course section -->
<section id="freeCourses" class="max-w-full mx-auto py-[24px] sm:py-14 bg-gray-700">
    <h2 class="text-center text-3xl font-bold text-white mb-6">Free Courses</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="freeCourseContainer"></div>
</section>
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
                            <button onclick="viewCourse('${course.name}')" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg">View</button>
                            <!-- Edit button -->
                            <button onclick="freeeditCourse('${course.id}')" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg">Edit Course</button>
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
    function viewCourse(courseName) {
        // Redirect to viewcourse.php with the courseName as a URL parameter
        window.location.href = `freeviewcourse.php?course_name=${encodeURIComponent(courseName)}`;
    }

    // Call this function when the document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        fetchCourses();        // Fetch paid courses (assuming you have this function)
        fetchFreeCourses();    // Fetch free courses
    });
</script>
 <!-- Free Courses Section -->
 <section class="py-16 flex justify-center items-center" id="free-courses">
    <div class="container mx-auto text-center flex flex-col justify-center items-center">
        <h2 class="text-5xl font-semibold text-gray-900 mb-16" data-aos="fade-up" data-aos-delay="200">Our Free Courses</h2>
        
        <!-- Free Courses Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12 justify-center items-center">
            <!-- Free Course Card 1 -->
            <div class="bg-green-200 rounded-xl shadow-md hover:shadow-lg transition-all duration-500 transform hover:scale-105 p-6 border border-gray-200" data-aos="fade-up" data-aos-delay="300">
                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="Free Course Image" class="w-full h-56 rounded-lg mb-6 transition-transform duration-500 hover:scale-105">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Clarity Basics</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Free</p>
                
                <div class="mt-6">
                    <button 
                        onclick="redirectToPage('index.php?page=Clarity_course')" 
                        class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg inline-block transition-all duration-300 ease-in-out">
                        Enroll Now
                    </button>
                </div>
            </div>

            <!-- Free Course Card 2 -->
            <div class="bg-green-200  rounded-xl shadow-md hover:shadow-lg transition-all duration-500 transform hover:scale-105 p-6 border border-gray-200" data-aos="fade-up" data-aos-delay="400">
                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="Free Course Image" class="w-full h-56 rounded-lg mb-6 transition-transform duration-500 hover:scale-105">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Basics of Solidity</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Free</p>
                
                <div class="mt-6">
                    <!-- <a href="index.php?page=Solidity-Course" class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg inline-block transition-all duration-300 ease-in-out">Enroll Now</a> -->
                    <button 
                        onclick="redirectToPage('index.php?page=Solidity-Course')" 
                        class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg inline-block transition-all duration-300 ease-in-out">
                        Enroll Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function buyCourse(courseId) {
    if (isWalletConnected()) {
        const account = localStorage.getItem('account');  // Get wallet account info

        // Fetch course details based on courseId (this can be hardcoded or dynamically fetched from your system)
        const courseName = getCourseName(courseId);
        const coursePrice = getCoursePrice(courseId);

        const enrollmentData = {
            user_account: account,
            course_id: courseId,
            course_name: courseName,
            course_price: coursePrice
        };

        // Send enrollment data to the backend (using fetch API)
        enrollCourse(enrollmentData)
            .then(response => {
                if (response.success) {
                    alert('Successfully enrolled in the course!');
                    window.location.href = 'enrolled_courses.php';  // Redirect to the enrolled courses page
                } else {
                    alert('Enrollment failed! Please try again.');
                }
            })
            .catch(error => {
                console.error('Error enrolling in course:', error);
                alert('There was an error enrolling in the course.');
            });
    } else {
        alert('Please connect your wallet to buy the course.');
    }
}

async function enrollCourse(enrollmentData) {
    try {
        const response = await fetch('enroll_course.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(enrollmentData)
        });
        const result = await response.json();
        return result; // Assuming the response has a 'success' field
    } catch (error) {
        console.error('Error enrolling course:', error);
        return { success: false };
    }
}

// Functions to get course details based on courseId
function getCourseName(courseId) {
    const courses = [
        { id: 1, name: 'Clarity Basics', price: 10 },
        { id: 2, name: 'Solidity Basics', price: 20 }
    ];
    const course = courses.find(course => course.id === courseId);
    return course ? course.name : '';
}

function getCoursePrice(courseId) {
    const courses = [
        { id: 1, name: 'Clarity Basics', price: 10 },
        { id: 2, name: 'Solidity Basics', price: 20 }
    ];
    const course = courses.find(course => course.id === courseId);
    return course ? course.price : 0;
}


</script>

<script>
    let selectedIndex = -1; // To track the current selected suggestion
    async function fetchCourses() {
        try {
            const response = await fetch('getcourses.php');
            const courses = await response.json();

            const courseContainer = document.querySelector('#courseContainer');
            courseContainer.innerHTML = ''; // Clear existing courses

            courses.forEach(course => {
                const courseCard = `
                    <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-2lg transition-all duration-300 transform hover:scale-105 p-4 border border-gray-200 hover:border-green-400 relative max-w-xs mx-auto mb-4" data-course-id="${course.id}" data-tags="${course.tags}">
                        <img src="${course.image}" alt="Course Image" class="w-full h-36 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
                        <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">${course.name}</h3>
                        <p class="text-xl font-bold text-green-600 mt-4">Tkn ${course.token_price}</p>
                        <div class="course-description mt-4">
                            <p class="text-gray-600">${course.description}</p>
                        </div>
                        <div class="mt-6 flex gap-4 justify-center">
                           <button onclick="window.location.href='viewcourse.php?course_id=' + ${course.id}" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg">View</button>
                            <button onclick="buy('${course.id}')" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg">
                                Buy Course
                            </button>
                          </div>
                    </div>
                `;

                courseContainer.insertAdjacentHTML('beforeend', courseCard);
            });
        } catch (error) {
            console.error('Error fetching courses:', error);
        }
    }
    function redirectToPage(page) {
        window.location.href = page;
    }
    function buy(courseId) {
        // Fetch the course details based on the courseId
        const courseCard = document.querySelector(`.course-card[data-course-id="${courseId}"]`);
        const courseName = courseCard.querySelector('h3').textContent;
        const courseDescription = courseCard.querySelector('.course-description p').textContent;
        const courseTokenPrice = courseCard.querySelector('p.text-xl').textContent.split(' ')[1]; // Assuming "Tkn 10" format
        const courseTags = courseCard.getAttribute('data-tags');

        // Redirect to addcourse.php with course details as URL parameters
        window.location.href = `buy.php?course_id=${courseId}&name=${encodeURIComponent(courseName)}&description=${encodeURIComponent(courseDescription)}&token_price=${encodeURIComponent(courseTokenPrice)}&tags=${encodeURIComponent(courseTags)}`;
    }
// Call this function when a course is added
function appendCourse(course) {
    const courseContainer = document.querySelector('#courseContainer');

    const courseCard = `
        <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-4 border border-gray-200 hover:border-green-400 relative max-w-xs mx-auto mb-4" data-tags="${course.tags}">
            <img src="${course.image}" alt="Course Image" class="w-full h-36 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
            <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">${course.name}</h3>
            <p class="text-xl font-bold text-green-600 mt-4">Tkn ${course.token_price}</p>
            <div class="course-description mt-4">
                <p class="text-gray-600">${course.description}</p>
            </div>
            <div class="mt-6 flex gap-4 justify-center">
                <button onclick="viewCourse('${course.id}')" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg">View </button>
           <button onclick="buy('${course.id}')" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg">
    Buy Course
</button>
  </div>
        </div>
    `;
    
    courseContainer.insertAdjacentHTML('beforeend', courseCard);
}

// Call this function after the course is added successfully (AJAX, form submission, etc.)
function handleCourseAddition(course) {
    appendCourse(course);  // Add it dynamically to the page
}

   
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
<?php
    include 'footer.php';
?>
</body>
</html>