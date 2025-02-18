<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup - D-Academe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #203f43, #2c8364);
            background-size: cover;
            background-position: center;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <!-- Signup Form Container -->
    <div class=" p-8 rounded-xl shadow-2xl w-full max-w-4xl">
        <div class="text-center mb-8">
            <img src="../../assets/logo.png" alt="D-Academe Logo" class="w-20 mx-auto">
            <h1 class="text-4xl font-extrabold text-gray-400 mt-4">User Register</h1>
            <p class="text-gray-500 mt-2">Join us and start your journey</p>
        </div>

        <!-- Profile Picture -->
        <div class="text-center mb-8">
            <label for="profile_picture" class="block text-gray-200 mb-2 font-semibold">Profile Picture</label>
            <div class="relative w-28 h-28 mx-auto mb-4">
                <img id="profilePreview" 
                     src="https://via.placeholder.com/96" 
                     alt="Profile Preview" 
                     class="w-full h-full rounded-full border border-gray-300 object-cover shadow-sm">
                <input id="profile_picture" type="file" accept="image/*" onchange="previewProfilePicture(event)"
                       class="absolute inset-0 opacity-0 cursor-pointer">
            </div>
        </div>

        <!-- Form Structure -->
        <form id="userSignupForm" onsubmit="submitUserSignup(event)" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-200 font-semibold">Name:</label>
                    <input id="name" type="text" placeholder="Full Name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="dob" class="block text-gray-200 font-semibold">Date of Birth:</label>
                    <input id="dob" type="date" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone_number" class="block text-gray-200 font-semibold">Phone:</label>
                    <input id="phone_number" type="text" placeholder="Phone Number" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-gray-200 font-semibold">Password:</label>
                    <input id="password" type="password" placeholder="Password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" id="togglePassword" class="absolute right-4 top-10 text-gray-500">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-200 font-semibold">Email:</label>
                    <input id="email" type="email" placeholder="Email Address" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-gray-200 font-semibold">Gender:</label>
                    <select id="gender" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-gray-200 font-semibold">Address:</label>
                    <input id="address" placeholder="Your Address" type="text" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <label for="confirm_password" class="block text-gray-200 font-semibold">Confirm Password:</label>
                    <input id="confirm_password" type="password" placeholder="Confirm Password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" id="toggleConfirmPassword" class="absolute right-4 top-10 text-gray-500">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-span-1 lg:col-span-2 flex justify-center mt-6">
                <button type="submit" 
                        class="bg-teal-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-teal-700 transition-all shadow-md">
                    Sign Up
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewProfilePicture(event) {
            const preview = document.getElementById('profilePreview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        }

        function submitUserSignup(event) {
            event.preventDefault();
            alert('Form Submitted!');
        }
    </script>
</body>
</html>

    <script>
        function previewProfilePicture(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        async function submitUserSignup(event) {
            event.preventDefault();
    
            // Retrieve form values
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phoneNumber = document.getElementById('phone_number').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const dob = document.getElementById('dob').value;
            const gender = document.getElementById('gender').value;
            const address = document.getElementById('address').value.trim();
            const profilePicture = document.getElementById('profile_picture').files[0];
    
            // Validate required fields
            if (!name || !email || !phoneNumber || !dob || !gender || !address || !profilePicture) {
                alert("Please fill in all fields and upload a profile picture.");
                return;
            }

            // Validate email format
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return;
            }

            // Validate password match
            if (password !== confirmPassword) {
                alert("Password and Confirm Password do not match.");
                return;
            }
    
            // Create FormData for file upload and form submission
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone_number', phoneNumber);
            formData.append('password', password);
            formData.append('dob', dob);
            formData.append('gender', gender);
            formData.append('address', address);
            formData.append('profile_picture', profilePicture);
    
            try {
                // Submit form data to server via fetch
                const response = await fetch('User_register.php', {
                    method: 'POST',
                    body: formData,
                });
    
                const result = await response.json();
    
                // Handle response
                if (result.status === 'success') {
                    alert('Signup successful! Redirecting to login page.');
                    window.location.href = 'user_login.html';
                } else {
                    alert(result.message || 'Signup failed. Please try again.');
                }
            } catch (error) {
                console.error('Error during signup:', error);
                alert('An unexpected error occurred. Please try again later.');
            }
        }

        // Password visibility toggle for the 'Password' field
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const currentType = passwordField.type;
            passwordField.type = currentType === 'password' ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');  // Toggle to open eye
            this.querySelector('i').classList.toggle('fa-eye-slash');  // Toggle to closed eye
        });

        // Password visibility toggle for the 'Confirm Password' field
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm_password');
            const currentType = confirmPasswordField.type;
            confirmPasswordField.type = currentType === 'password' ? 'text' : 'password';
            this.querySelector('i').classList.toggle('fa-eye');  // Toggle to open eye
            this.querySelector('i').classList.toggle('fa-eye-slash');  // Toggle to closed eye
        });
    </script>
</body>
</html>