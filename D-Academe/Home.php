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

    <style>
        .hero-bg {
            background-image: url('assets/hero-image.webp');
            background-size: cover;
            background-position: center;
        }
        .hero-overlay {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Hero Section -->
    <section class="relative w-full h-screen hero-bg">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="relative z-10 flex flex-col justify-center items-center text-white text-center space-y-8 py-32 px-4">
            <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight max-w-3xl mx-auto animate__animated animate__fadeIn" data-aos="fade-up" data-aos-delay="200">Master Blockchain Development and Boost Your Career</h1>
            <p class="text-xl sm:text-2xl max-w-2xl mx-auto animate__animated animate__fadeIn animate__delay-1s" data-aos="fade-up" data-aos-delay="300">Join a world-class blockchain course and get hands-on experience in building decentralized apps and smart contracts.</p>
            <div class="flex gap-6">
                <a href="/register" class="bg-green-600 hover:bg-green-700 text-white py-3 px-10 rounded-full text-lg transition duration-300 ease-in-out transform hover:scale-105" data-aos="fade-up" data-aos-delay="500">Get Started</a>
                <a href="/learn-more" class="text-white border-2 border-white py-3 px-10 rounded-full text-lg transition duration-300 ease-in-out hover:bg-white hover:text-gray-900" data-aos="fade-up" data-aos-delay="600">Learn More</a>
            </div>
        </div>
    </section>

  <!-- Courses Section -->
<section class="py-16 bg-gray-100" id="courses">
    <div class="container mx-auto text-center">
        <h2 class="text-5xl font-semibold text-gray-900 mb-16" data-aos="fade-up" data-aos-delay="200">Our Popular Courses</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
            <!-- Course Card 1 -->
            <div class="bg-white rounded-xl shadow-2xl transform transition duration-500 hover:scale-105 hover:shadow-lg p-6" data-aos="fade-up" data-aos-delay="300">
                <img src="assets/img.webp" alt="Course Image" class="w-full h-56 object-cover rounded-lg mb-6">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Solidity Development</h3>
                <p class="text-lg text-gray-600 mt-2">Instructor: Suyan Thapa</p>
                <p class="text-lg text-gray-600">12hrs 30min</p>
                <p class="text-xl font-bold text-green-600 mt-4">Tkn 65</p>
                <a href="/course/1" class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg mt-6 inline-block transition duration-300 ease-in-out">View Course</a>
            </div>

            <!-- Course Card 2 -->
            <div class="bg-white rounded-xl shadow-2xl transform transition duration-500 hover:scale-105 hover:shadow-lg p-6" data-aos="fade-up" data-aos-delay="400">
                <img src="assets/img.webp" alt="Course Image" class="w-full h-56 object-cover rounded-lg mb-6">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Blockchain Basics</h3>
                <p class="text-lg text-gray-600 mt-2">Instructor: Anupama Karki</p>
                <p class="text-lg text-gray-600">10hrs 20min</p>
                <p class="text-xl font-bold text-green-600 mt-4">Tkn 50</p>
                <a href="/course/2" class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg mt-6 inline-block transition duration-300 ease-in-out">View Course</a>
            </div>

            <!-- Course Card 3 -->
            <div class="bg-white rounded-xl shadow-2xl transform transition duration-500 hover:scale-105 hover:shadow-lg p-6" data-aos="fade-up" data-aos-delay="500">
                <img src="assets/img.webp" alt="Course Image" class="w-full h-56 object-cover rounded-lg mb-6">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Advanced Smart Contracts</h3>
                <p class="text-lg text-gray-600 mt-2">Instructor: Bikash Khadka</p>
                <p class="text-lg text-gray-600">15hrs</p>
                <p class="text-xl font-bold text-green-600 mt-4">Tkn 80</p>
                <a href="/course/3" class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg mt-6 inline-block transition duration-300 ease-in-out">View Course</a>
            </div>
        </div>
    </div>
</section>
 <!-- Free Courses Section -->
 <section class="py-16 flex justify-center items-center" id="free-courses">
    <div class="container mx-auto text-center flex flex-col justify-center items-center">
        <h2 class="text-5xl font-semibold text-gray-900 mb-16" data-aos="fade-up" data-aos-delay="200">Our Free Courses</h2>
        
        <!-- Free Courses Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12 justify-center items-center">
            <!-- Free Course Card 1 -->
            <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition-all duration-500 transform hover:scale-105 p-6 border border-gray-200" data-aos="fade-up" data-aos-delay="300">
                <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="Free Course Image" class="w-full h-56 rounded-lg mb-6 transition-transform duration-500 hover:scale-105">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Clarity Basics</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Free</p>
                
                <div class="mt-6">
                    <a href="index.php?page=free-course-content" class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg inline-block transition-all duration-300 ease-in-out">Enroll Now</a>
                </div>
            </div>

            <!-- Free Course Card 2 -->
            <div class="bg-gray-100 rounded-xl shadow-md hover:shadow-lg transition-all duration-500 transform hover:scale-105 p-6 border border-gray-200" data-aos="fade-up" data-aos-delay="400">
                <img src="Free-Course-Contents/Solidity/assets/sol.webp" alt="Free Course Image" class="w-full h-56 rounded-lg mb-6 transition-transform duration-500 hover:scale-105">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Basics of Solidity</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Free</p>
                
                <div class="mt-6">
                    <a href="/free-course/2" class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-full text-lg inline-block transition-all duration-300 ease-in-out">Enroll Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-green-400 text-white" id="why-us">
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
    <section class="py-16 bg-gray-100 text-center" id="cta">
    <div class="container mx-auto">
        <h2 class="text-4xl font-bold text-gray-900 mb-8" data-aos="fade-up" data-aos-delay="200">Ready to Start Your Blockchain Journey?</h2>
        <a href="/register" class="bg-green-600 hover:bg-green-700 text-white py-4 px-10 rounded-full text-lg transition duration-300 ease-in-out" data-aos="fade-up" data-aos-delay="400">Join Now</a>
    </div>
</section>


    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-out-back',
            once: false,
        });
    </script>

</body>

</html>
