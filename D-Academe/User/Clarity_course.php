<?php
require __DIR__ . '/Parsedown.php';

$Parsedown = new Parsedown();
$clarityFolder = __DIR__ . '/Free-Course-Contents/Clarity/';
$summaryFile = $clarityFolder . "SUMMARY.md";

// Default page: Clarity of Mind
$defaultPage = 'title-page';
$course = $defaultPage;
$htmlContent = "<p>Select a topic from the sidebar.</p>";
$sidebarItems = "";

// Parse SUMMARY.md and generate the sidebar with numbering
$pages = []; // To hold all course pages for navigation
$currentIndex = null; // To track the current course index

if (file_exists($summaryFile)) {
    $summaryContent = file($summaryFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $sidebarItems = "<ul class='space-y-3'>";

    $sectionNumber = 0;
    $subSectionNumber = 0;

    foreach ($summaryContent as $line) {
        $trimmed = trim($line);

        if (preg_match('/^#/', $trimmed)) {
            // Section headers (e.g., "Content")
            $header = str_replace("#", "", $trimmed);
            $sidebarItems .= "<li class='font-bold text-white'>$header</li>";
        } elseif (preg_match('/^\- \[(.*?)\]\((.*?)\)$/', $trimmed, $matches)) {
            $indentLevel = substr_count($line, "  ");

            // Extract link text and path
            $linkText = $matches[1];
            $linkPath = htmlspecialchars(basename($matches[2], ".md"));

            $pages[] = $linkPath; // Add to pages array for navigation

            // Adjust numbering based on indentation level
            if ($indentLevel === 0) {
                $sectionNumber++;
                $subSectionNumber = 0;
                $numbering = $sectionNumber;
            } else {
                $subSectionNumber++;
                $numbering = "$sectionNumber.$subSectionNumber";
            }

            // Active class based on the current page
            $activeClass = (strtolower($linkPath) === strtolower($course)) ? 'text-blue-500 font-semibold' : 'hover:text-blue-300';
            $indentClass = $indentLevel > 0 ? 'pl-' . ($indentLevel * 4) : '';

            // Add the sidebar item with dynamic classes
            $sidebarItems .= "<li class='$indentClass'><a href='?page=Clarity_course&course=$linkPath' class='block py-2 px-4 $activeClass transition-colors' id='link-$linkPath'>$numbering $linkText</a></li>";
        }
    }
    $sidebarItems .= "</ul>";
}

// Check if a specific course is requested
if (isset($_GET['page']) && $_GET['page'] === 'Clarity_course' && isset($_GET['course'])) {
    $course = $_GET['course'];
}

$filePath = $clarityFolder . basename($course) . ".md";
if (file_exists($filePath)) {
    $markdownContent = file_get_contents($filePath);
    $htmlContent = $Parsedown->text($markdownContent);
} else {
    $htmlContent = "<p class='text-red-500'>Page not found.</p>";
    $course = "Error";
}

// Determine the current page index
$currentIndex = array_search($course, $pages);
$prevCourse = $currentIndex > 0 ? $pages[$currentIndex - 1] : null;
$nextCourse = $currentIndex !== null && $currentIndex < count($pages) - 1 ? $pages[$currentIndex + 1] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css"> <!-- For code block styling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <title><?= htmlspecialchars($course === $defaultPage ? "Clarity of Mind" : $course) ?></title>
    <style>
        html {
            scroll-behavior: smooth;
        }
        .content h1, .content h2, .content h3 {
            color: #2d3748; /* Matching Clarity book color */
            font-weight: 700;
            margin-top: 1.5em;
        }
        .content p {
            margin-top: 1em;
            line-height: 1.75;
            font-size: 1.125rem;
            color: #4a5568;
        }
        .content pre {
            background-color: #4a5568;
            color: #a0aec0;
            padding: 1em;
            border-radius: 0.5em;
            overflow-x: auto;
        }
        .content code {
            background-color: #edf2f7;
            padding: 0.2em 0.4em;
            border-radius: 0.25em;
            color: #2d3748;
            font-size: 0.95em;
        }
        .content a {
            color: #3182ce;
            text-decoration: underline;
        }
        .content ul, .content ol {
            margin-top: 1em;
            padding-left: 1.5em;
        }
        .content li {
            margin-top: 0.5em;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Main Container -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <div class="w-1/5 bg-white text-gray-800 p-6 pt-32 h-full overflow-y-auto">
            <img src="Free-Course-Contents/Clarity/assets/logo.svg" alt="Clarity-Logo" class="w-60 h-auto object-contain hover:scale-105 transition-transform duration-300 opacity-80 hover:opacity-100">
            <?= $sidebarItems ?>
        </div>

        <!-- Content Area -->
        <div class="w-full p-8 bg-white overflow-y-auto">
            <div class="max-w-1xl mx-auto content">
                <!-- Course Title -->
                <!-- <h1 class="text-4xl font-bold mb-4 text-gray-800 border-b-2 border-gray-200 pb-4"><?= htmlspecialchars($course === $defaultPage ? "Clarity of Mind" : $course) ?></h1> -->

                <!-- Content Section -->
                <div class="prose prose-lg prose-green pt-16 max-w-none leading-relaxed text-2xl">
                    <?= $htmlContent ?>
                </div>

                <!-- Navigation Links -->
                <div class="mt-8 flex justify-between">
                    <?php if ($prevCourse): ?>
                        <a href="?page=Clarity_course&course=<?= htmlspecialchars($prevCourse) ?>" class="text-blue-500 hover:text-blue-700">&larr; Previous</a>
                    <?php else: ?>
                        <span class="text-gray-400">&larr; Previous</span> <!-- Disabled -->
                    <?php endif; ?>
                    
                    <?php if ($nextCourse): ?>
                        <a href="?page=Clarity_course&course=<?= htmlspecialchars($nextCourse) ?>" class="text-blue-500 hover:text-blue-700">Next &rarr;</a>
                    <?php else: ?>
                        <span class="text-gray-400">Next &rarr;</span> <!-- Disabled -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to highlight the active sidebar link -->
    <script>
        // Add active class based on the current course
        const currentCourse = "<?= htmlspecialchars($course) ?>"; // Get current course dynamically
        const activeLink = document.getElementById('link-' + currentCourse);
        if (activeLink) {
            activeLink.classList.add('text-blue-500', 'font-semibold');
        }
    </script>

</body>
</html>
