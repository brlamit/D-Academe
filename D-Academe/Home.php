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
            background: linear-gradient(to bottom, #0f2027, #203f43, #2c8364);
            /* background-image: url('assets/hero2.webp'); */
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
<!-- <section class="h-[492px] md:h-[600px] flex items-center overflow-hidden relative [mask-image:linear-gradient(to_bottom,transparent,black_10%,black_90%,transparent)]" style="background-image: url('assets/stars.0c47b3bb.png'); background-position: 456.393px -55.8484px; will-change: auto; "> -->
 
<section class="relative w-full h-screen hero-bg ">
    <div class="absolute inset-0 hero-overlay"></div>
    <div class="relative z-10 flex flex-col justify-center items-center text-white text-center space-y-8 py-32 px-4">
        <!-- Gradient Animated Heading -->
        <h1 
            class="text-5xl sm:text-6xl font-extrabold leading-tight max-w-3xl mx-auto bg-clip-text text-transparent bg-gradient-to-r from-green-400 via-red-500 to-blue-500  animate-gradientX" data-aos="fade-up" data-aos-delay="200">
            Master Blockchain Development and Boost Your Career
        </h1>
        <!-- Paragraph with Subtle Fade-In Animation -->
        <p 
            class="text-xl sm:text-2xl max-w-2xl mx-auto opacity-90 hover:opacity-100 transition duration-300 ease-in-outanimate-fadeInUp animate-delay-300" data-aos="fade-up" data-aos-delay="300">
            Join a world-class blockchain course and get hands-on experience in building decentralized apps and smart contracts.
        </p>
        <!-- Call-to-Action Buttons -->
        <div class="flex gap-6">
            <a href="?page=userregister" class="bg-green-600 hover:bg-green-700 text-white py-3 px-10 rounded-full text-lg  shadow-lg shadow-green-500/50 transform hover:scale-110 transition duration-300 ease-in-out animate-bounce" data-aos="fade-up" data-aos-delay="500">
                Get Started
            </a>
            <a  href="/learn-more" class="text-white border-2 border-white py-3 px-10 rounded-full text-lg shadow-lg hover:shadow-pink-500/50 transition duration-300 ease-in-out hover:bg-white hover:text-gray-900" data-aos="fade-up" data-aos-delay="600">
                Learn More
            </a>
        </div>
        <!-- Search Bar -->
<div class="w-full max-w-lg relative">
    <div class="flex items-center">
        <input
            type="text"
            placeholder="Search Courses..."
            class="w-full py-3 px-6 rounded-full text-gray-900 placeholder-gray-500 bg-white shadow-lg focus:outline-none focus:ring-2 focus:ring-green-600"
            data-aos="fade-up"
            data-aos-delay="100"
        />
        <button
            class="absolute right-8 top-2/2 transform -translate-y-1/2 bg-transparent text-red-400 py-1 px-8 rounded-full text-xl"
            data-aos="fade-up"
            data-aos-delay="100"
        >Search
            <!-- Search Icon -->
            <i class="fas fa-search text-xl"></i>
        </button>
    </div>
</div>
    </div>
</section>
  <!-- Courses Section -->

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

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-green-300 text-white" id="why-us">
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

</body>

</html>