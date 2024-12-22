<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Course</title>
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
    </style>
</head>
<body class="bg-gray-500 text-white flex items-center justify-center min-h-screen">

<section class="py-16 bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300" id="enrolled-courses">
    <div class="container mx-auto text-center">
        <h2 class="text-5xl font-semibold text-gray-900 mx-auto mb-8">Enrolled Courses</h2>

        <!-- Course Cards will be dynamically generated here -->
        <div id="enrolled-courses-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
            <!-- Enrolled course cards will be inserted here -->
        </div>
    </div>
</section>

<script>
    // Retrieve the purchased courses from localStorage
    const purchasedCourses = JSON.parse(localStorage.getItem('purchasedCourses')) || {};

    // List of all available courses
    const allCourses = {
        'solidity-basic': { name: 'Solidity Basic', price: 'Tkn 65', imgSrc: 'assets/img.webp' },
        'web3-development': { name: 'Web3 Development', price: 'Tkn 85', imgSrc: 'assets/img2.webp' },
        'smart-contract-mastery': { name: 'Smart Contract Mastery', price: 'Tkn 100', imgSrc: 'assets/img3.webp' },
    };

    // Function to display enrolled courses
    function displayEnrolledCourses() {
        const enrolledList = document.getElementById('enrolled-courses-list');

        // Iterate over the purchasedCourses and create a card for each course
        for (const courseId in purchasedCourses) {
            if (purchasedCourses[courseId]) {
                const course = allCourses[courseId];

                const courseCard = document.createElement('div');
                courseCard.classList.add('bg-white', 'rounded-xl', 'shadow-xl', 'p-6', 'border', 'border-gray-200', 'hover:border-green-400', 'transition-all', 'duration-300', 'transform', 'hover:scale-105');
                
                courseCard.innerHTML = `
                    <img src="${course.imgSrc}" alt="${course.name}" class="w-full h-40 object-cover rounded-lg mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500">${course.name}</h3>
                    <p class="text-xl font-bold text-green-600 mt-4">${course.price}</p>
                    <a href="javascript:void(0);" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">
                        Access Course
                    </a>
                `;
                
                enrolledList.appendChild(courseCard);
            }
        }
    }

    // Call the function to display enrolled courses when the page loads
    displayEnrolledCourses();
</script>

</body>
</html>