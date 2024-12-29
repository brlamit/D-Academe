<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900">

    <div class="flex flex-col items-center py-12 px-6 min-h-screen mt-24">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-8">Help & Support</h2>
        <p class="text-lg text-gray-300 mb-8 text-center max-w-2xl">
            If you have any questions or need assistance, please fill out the form below or contact us at
            <a href="mailto:dacamedeoffical@gmail.com" class="text-green-500 hover:underline ml-1">dacamedeoffical@gmail.com</a>.
        </p>

        <form id="contact-form" class="relative w-full max-w-xl bg-white p-8 rounded-lg shadow-lg">
            <!-- Loader Overlay (Inside Form) -->
            <div class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center hidden" id="loader-overlay">
                <div class="w-12 h-12 border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
            </div>
            
            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-lg font-medium mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-3 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Your Name" required>
            </div>
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-lg font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Your Email" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block text-gray-700 text-lg font-medium mb-2">Message</label>
                <textarea id="message" name="message" class="w-full px-4 py-3 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Your Message or Inquiry" rows="5" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-full hover:bg-blue-700 transition-colors duration-300">
                    Send Message
                </button>
            </div>
        </form>
    </div>

    <script>
        // Handle form submission with JavaScript
        const form = document.getElementById('contact-form');
        const loaderOverlay = document.getElementById('loader-overlay');

        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Prevent default form submission
            loaderOverlay.classList.remove('hidden'); // Show the loader

            // Collect form data
            const formData = new FormData(form);

            try {
                // Send the data to the server using fetch API
                const response = await fetch('send_email.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.text();
                loaderOverlay.classList.add('hidden'); // Hide the loader
                
                // Display a popup message with the response
                alert(result);
                
                // Clear the form fields
                form.reset();
            } catch (error) {
                loaderOverlay.classList.add('hidden'); // Hide the loader
                alert('An error occurred. Please try again.');
                console.error(error);
            }
        });
    </script>

</body>
</html>