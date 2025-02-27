<?php 
$courses = [ 
    [ 
        'title' => 'Blockchain Fundamentals', 
        'description' => 'Learn the basics of blockchain technology, its applications, and how it is transforming industries worldwide.', 
        'image' => 'blockchain.png' 
    ], 
    [ 
        'title' => 'Solidity Programming', 
        'description' => 'Dive into smart contract development using Solidity, the primary programming language for Ethereum.', 
        'image' => './assest/logo.svg' 
    ], 
    [ 
        'title' => 'PHP for Web Development', 
        'description' => 'Master PHP programming to create dynamic, database-driven websites and web applications.', 
        'image' => 'php.png' 
    ], 
    [ 
        'title' => 'Web Development', 
        'description' => 'Explore front-end and back-end web development, including HTML, CSS, JavaScript, and modern frameworks.', 
        'image' => 'web-development.png' 
    ]
]; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn More - Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .animate-fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-teal-100">
<br>
<br>
    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8 mt=24">
       
        <!-- Header Section -->
        <section class="relative w-full mt=16">
        <br>
        
            <div class="bg-teal-700 text-white p-6 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-semibold mb-4">
                    <b>"Unlock the future with Blockchain—revolutionizing industries, ensuring transparency, and powering the next generation of innovation."</b>
                </h2>
                <p class="text-lg">
                    Blockchain technology is reshaping industries, driving transparency, security, and innovation.<br>
                    From decentralized finance (DeFi) to supply chain solutions, it’s creating limitless possibilities.<br>
                    Master blockchain development to unlock a rewarding career in this transformative field.
                </p>
            </div>
        </section>

        <!-- Why Choose Blockchain Section -->
        <section class="mt-8">
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg text-gray-800 mx-auto max-w-3xl">
                <h3 class="text-xl font-semibold mb-4 text-center">Why Choose Blockchain?</h3>
                <p>
                    Companies are seeking experts to build dApps, smart contracts, and blockchain-based solutions.<br>
                    Enjoy high-paying roles, remote flexibility, and the chance to work on cutting-edge technologies.
                </p>
            </div>
        </section>

        <!-- Roadmap Section -->
        <section class="mt-8">
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg text-gray-800 mx-auto max-w-3xl">
                <h3 class="text-xl font-semibold mb-4 text-center">Roadmap to Become a Blockchain Developer</h3>
                <ul class="list-disc pl-6">
                    <li><strong>Basics:</strong> Understand blockchain, its components, and popular platforms like Ethereum.</li>
                    <li><strong>Programming:</strong> Learn Solidity, JavaScript, or Python for development.</li>
                    <li><strong>Cryptography:</strong> Grasp hashing, digital signatures, and encryption.</li>
                    <li><strong>Smart Contracts:</strong> Develop self-executing contracts with Solidity.</li>
                    <li><strong>Projects:</strong> Build real-world dApps or exchanges to showcase skills.</li>
                </ul>
                <p class="mt-4">
                    Stay updated with trends, participate in hackathons, and connect with the community to grow your expertise.<br>
                    With dedication, you’ll stand out as a blockchain professional.
                </p>
            </div>
        </section>

        <!-- Career Opportunities Section -->
        <section class="mt-8">
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg text-gray-800 mx-auto max-w-3xl">
                <h3 class="text-xl font-semibold mb-4 text-center">Career Opportunities</h3>
                <ul class="list-disc pl-6">
                    <li>Blockchain Developer: Create decentralized apps and smart contracts.</li>
                    <li>Smart Contract Engineer: Specialize in coding and auditing smart contracts.</li>
                    <li>Blockchain Architect: Design secure, innovative blockchain systems.</li>
                </ul>
                <p class="mt-4">
                    Start your blockchain journey today and shape the future of technology!
                </p>
            </div>
        </section>

        <!-- Additional Services -->
        <section class="mt-8">
            <div class="bg-red-100 p-6 rounded-lg shadow-lg text-gray-800 mx-auto max-w-3xl">
                <h3 class="text-xl font-semibold text-center"><b>The other courses we provide are listed below.</b></h3>
            </div>
        </section>

        <!-- Courses Section -->
        <section class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($courses as $index => $course): ?>
                <div 
                    class="bg-green-200 rounded-lg shadow-lg p-6 flex flex-col justify-between animate-fade-up" 
                    style="transition-delay: <?= $index * 200 ?>ms;">
                    <img src="<?= htmlspecialchars($course['image']) ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="w-full h-40 object-cover rounded-t-lg mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($course['title']) ?></h2>
                    <p class="text-gray-700 mb-4"><?= htmlspecialchars($course['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <!-- JavaScript for Scroll Animation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.animate-fade-up');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    }
                });
            });
            elements.forEach(element => observer.observe(element));
        });
    </script>
</body>
</html>
