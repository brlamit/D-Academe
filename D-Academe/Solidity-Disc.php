<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 mt-12 space-y-8">
    <div class="container mx-auto px-4 py-8 mt-24">
        <!-- Course Description -->
        <div class="bg-green-100 shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-4 text-gray-800">Course Title: Web Development Mastery</h1>
            <p class="text-gray-600 text-lg mb-4">
                Learn the essentials of web development, including HTML, CSS, JavaScript, and backend programming. 
                This course is designed for beginners and intermediate developers aiming to master the craft.
            </p>
            <ul class="list-disc list-inside text-gray-600">
                <li>Duration: 3 Months</li>
                <li>Instructor: John Doe</li>
                <li>Certification upon Completion</li>
            </ul>
        </div>

        <!-- Registration Form -->
         <div class="bg-green-700 shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Register for the Course</h2>
            <form action="submit.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-semibold">Full Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-semibold">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-gray-700 font-semibold">Age</label>
                    <input type="number" id="age" name="age" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-gray-700 font-semibold">Upload Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Additional Details -->
                <div>
                    <label for="details" class="block text-gray-700 font-semibold">Additional Details</label>
                    <textarea id="details" name="details" rows="4"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
