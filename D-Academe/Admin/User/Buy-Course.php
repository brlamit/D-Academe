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
    </style>
    <style>
        .cart-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #1d4ed8; /* Tailwind blue-600 */
            color: white;
            padding: 8px 16px;
            border-radius: 50%;
            font-size: 1.25rem;
            transition: transform 0.3s ease-in-out;
        }
        .cart-button:hover {
            transform: scale(1.1);
            background-color: #2563eb; /* Tailwind blue-700 */
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
                <div class="flex items-center border border-gray-300 rounded-full bg-white shadow-lg focus-within:ring-2 focus-within:ring-green-600">
                    <input
                        type="text"
                        placeholder="Search Courses..."
                        id="searchInput"
                        class="w-full py-3 px-6 rounded-full text-gray-900 placeholder-gray-500 bg-white focus:outline-none"
                        data-aos="fade-up"
                        data-aos-delay="100"
                    />
                    <!-- <button
                        class="absolute right-4 top-1 transform -translate-y-2 bg-transparent text-center text-black hover:text-green-600 focus:outline-none py-4"
                        data-aos="fade-up"
                        data-aos-delay="100"
                    >
                        <i class="fas fa-search"></i>
                    </button> -->
                </div>

                <!-- Suggested Search Results -->
                <div id="suggestions" class="absolute  mt-2 bg-white mb-8 mx-auto w-full text-left shadow-lg rounded-md z-10 hidden">
                    <!-- Suggestions will be populated here -->
                </div>
            </div>
            </section>
     <!-- Popular Courses Section --> 
        <section class="py-16 text-centre bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300" id="courses">
            <h2 class="text-5xl text-center font-semibold text-gray-900 mx-auto">
                Our Popular Courses
            </h2>
      <div class="container mx-auto"> 
        <!-- Grid Layout with Responsive Design -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-9 justify-center">
            <!-- Course Card 1 -->
            <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 border border-gray-200 hover:border-green-400 relative" data-tags="solidity,basic,blockchain">
            <img src="assets/img.webp" alt="Course Image" class="w-full h-40 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
               
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Solidity Basic</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Tkn 65</p>
                <!-- Buttons Section -->
                <div class="mt-6 flex gap-4 justify-center">
                    <button onclick="openModal('modal-1')" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">View Course</button>
                    <a href="index.php?page=solidity-basic" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">Buy Course</a>
                </div>
            </div>

            <!-- Course Card 2 -->
            <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 border border-gray-200 hover:border-green-400 relative" data-tags="blockchain,basic,cryptography">
            <img src="assets/img.webp" alt="Course Image" class="w-full h-40 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
               
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Blockchain Basics</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Tkn 50</p>
                <!-- Buttons Section -->
                <div class="mt-6 flex gap-4 justify-center">
                    <button onclick="openModal('modal-2')" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">View Course</button>
                    <a href="/course/3" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">Buy Course</a>
                </div>
            </div>

            <!-- Course Card 3 -->
            <div class="course-card bg-white rounded-xl shadow-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105 p-6 border border-gray-200 hover:border-green-400 relative" data-tags="smart contracts,advanced,solidity">
          <img src="assets/img.webp" alt="Course Image" class="w-full h-40 object-cover rounded-lg mb-6 transition-transform duration-300 hover:scale-105">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-green-500 transition-colors duration-300">Advanced Smart Contracts</h3>
                <p class="text-xl font-bold text-green-600 mt-4">Tkn 80</p>
                <!-- Buttons Section -->
                <div class="mt-6 flex gap-4 justify-center">
                    <button onclick="openModal('modal-3')" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">View Course</button>
                    <a href="/course/3" class="bg-green-500 hover:bg-green-600 text-white py-3 px-8 rounded-full text-lg transition duration-300 ease-in-out">Buy Course</a>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Modals -->
    <!-- Modal 1: Course Description for "Solidity Development" -->
    <div id="modal-1" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Solidity Development</h2>
            <p class="text-lg text-gray-700">Learn how to build decentralized applications (dApps) and smart contracts on the Ethereum blockchain using Solidity. This course covers the fundamentals of Solidity programming and helps you build a solid foundation for blockchain development.</p>
            <!-- <p class="text-lg text-gray-700 mt-4">Instructor: Suyan Thapa</p> -->
            <p class="text-lg text-gray-700">Duration:2min 11sec</p>
            <p class="text-xl font-bold text-green-600 mt-4">Tkn 65</p>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal('modal-1')" class="bg-red-500 hover:bg-red-600 text-white py-2 px-6 rounded-full text-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Course Description for "Blockchain Basics" -->
    <div id="modal-2" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Blockchain Basics</h2>
            <p class="text-lg text-gray-700">This course introduces you to the foundational concepts of blockchain technology. Learn about decentralized systems, cryptography, and how blockchain is changing industries worldwide.</p>
            <p class="text-lg text-gray-700 mt-4">Instructor: Anupama Karki</p>
            <p class="text-lg text-gray-700">Duration: 10hrs 20min</p>
            <p class="text-xl font-bold text-green-600 mt-4">Tkn 50</p>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal('modal-2')" class="bg-red-500 hover:bg-red-600 text-white py-2 px-6 rounded-full text-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal 3: Course Description for "Advanced Smart Contracts" -->
    <div id="modal-3" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Advanced Smart Contracts</h2>
            <p class="text-lg text-gray-700">Dive deep into the world of smart contracts with advanced topics, such as optimizing code, integrating with decentralized oracles, and using smart contracts in real-world applications.</p>
            <p class="text-lg text-gray-700 mt-4">Instructor: Bikash Khadka</p>
            <p class="text-lg text-gray-700">Duration: 15hrs</p>
            <p class="text-xl font-bold text-green-600 mt-4">Tkn 80</p>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal('modal-3')" class="bg-red-500 hover:bg-red-600 text-white py-2 px-6 rounded-full text-lg">Close</button>
            </div>
        </div>
    </div>

    <!-- Font Awesome and Tailwind JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        // Function to open modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        // Function to close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
<script>
    // Get the search input, suggestion container, and learning paths
    const searchInput = document.getElementById('searchInput');
    const suggestionsContainer = document.getElementById('suggestions');
    const learningPaths = document.querySelectorAll('.course-card'); // Assuming each course is wrapped in a 'course-card' class

    // Function to get unique tags from learning paths
    function getTags() {
        let tags = [];
        learningPaths.forEach(path => {
            const pathTags = path.getAttribute('data-tags').split(','); // assuming tags are comma-separated
            tags = [...tags, ...pathTags];
        });
        return [...new Set(tags)].sort(); // Return unique and sorted tags
    }

    // Function to display suggestions
    function displaySuggestions(query) {
        const tags = getTags(); // Get all available tags
        const filteredTags = tags.filter(tag => tag.toLowerCase().includes(query.toLowerCase())); // Filter by user input

        // Clear any previous suggestions
        suggestionsContainer.innerHTML = '';

        if (filteredTags.length > 0) {
            // Show suggestions container
            suggestionsContainer.style.display = 'block';

            // Add filtered tags as suggestions
            filteredTags.forEach(tag => {
                const suggestionItem = document.createElement('div');
                suggestionItem.classList.add('px-4', 'py-2', 'cursor-pointer', 'text-gray-900', 'hover:bg-gray-100', 'transition', 'duration-200');
                suggestionItem.textContent = tag;

                // Add click functionality for each suggestion
                suggestionItem.addEventListener('click', function() {
                    searchInput.value = tag;
                    filterLearningPaths(tag); // Perform search on click
                    suggestionsContainer.style.display = 'none'; // Hide suggestions after selection
                });

                suggestionsContainer.appendChild(suggestionItem);
            });
        } else {
            suggestionsContainer.style.display = 'none'; // Hide if no suggestions
        }
    }

    // Function to filter learning paths based on the search query or tag
    function filterLearningPaths(query) {
        learningPaths.forEach(path => {
            const pathTags = path.getAttribute('data-tags').split(',');
            if (pathTags.some(tag => tag.toLowerCase().includes(query.toLowerCase()))) {
                path.style.display = 'block'; // Show if matching
            } else {
                path.style.display = 'none'; // Hide if not matching
            }
        });
    }

    // Add event listener to search input
    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();
        if (query) {
            displaySuggestions(query); // Show suggestions while typing
            filterLearningPaths(query); // Also filter courses while typing
        } else {
            suggestionsContainer.style.display = 'none'; // Hide suggestions when input is empty
            learningPaths.forEach(path => path.style.display = 'block'); // Show all courses
        }
    });
</script>


</body>
</html>