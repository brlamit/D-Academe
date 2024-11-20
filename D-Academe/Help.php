<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate inputs
    if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set email parameters
        $to = "dacademeoffical@gmail.com"; 
        $subject = "Help & Support Inquiry from " . $name;
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        // Send email
        if (mail($to, $subject, $body, $headers)) {
            echo "<script>alert('Your message has been sent successfully!');</script>";
        } else {
            echo "<script>alert('There was an error sending your message. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Please fill out all fields correctly.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Additional style to ensure header doesn't overlap with content */
        .content-container {
            margin-top: 5rem; /* This ensures the content does not overlap with the header */
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900"> <!-- Use site-wide background and text colors -->
    <div class="flex flex-col items-center py-12 px-6 min-h-screen content-container">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-8">Help & Support</h2>
        <p class="text-lg text-gray-600 mb-8 text-center max-w-2xl">
            If you have any questions or need assistance, please fill out the form below or contact us at
            <a href="mailto:dacamedeoffical@gmail.com" class="text-primary-600 hover:underline ml-1">dacamedeoffical@gmail.com</a>.
        </p>

        <form method="POST" action="" class="w-full max-w-xl bg-white p-8 rounded-lg shadow-lg">
            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-lg font-medium mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-3 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Your Name" required>
            </div>
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-lg font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Your Email" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block text-gray-700 text-lg font-medium mb-2">Message</label>
                <textarea id="message" name="message" class="w-full px-4 py-3 bg-gray-50 text-gray-900 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Your Message or Inquiry" rows="5" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-green-600 text-white font-bold py-3 px-6 rounded-full hover:bg-green-700 transition-colors duration-300">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</body>
</html>