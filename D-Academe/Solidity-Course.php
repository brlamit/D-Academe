<?php
require __DIR__ . '/Parsedown.php';

$Parsedown = new Parsedown();
$solidityFolder = __DIR__ . '/Free-Course-Contents/Solidity/';
$summaryFile = $solidityFolder . "Solidity_Course.md";

// Default page: Solidity_Course
$course = $defaultPage;
$defaultPage = 'Solidity_Course';
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
        } elseif (preg_match('/^- \[(.*?)\]\((.*?)\)$/', $trimmed, $matches)) {
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
            $sidebarItems .= "<li class='$indentClass'><a href='?page=Solidity-Course&course=$linkPath' class='block py-2 px-4 $activeClass transition-colors' id='link-$linkPath'>$numbering $linkText</a></li>";
        }
    }
    $sidebarItems .= "</ul>";
}

// Check if a specific course is requested
if (isset($_GET['page']) && $_GET['page'] === 'Solidity-Course' && isset($_GET['course'])) {
    $course = $_GET['course'];
}

$filePath = $solidityFolder . basename($course) . ".md";
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
    <title><?= htmlspecialchars($course === $defaultPage ? "Solidity_Course" : $course) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans"> 

    <!-- Main Container -->
    <div class="flex min-h-screen pt-16 mt-10">

        <!-- Sidebar -->
        <div class="w-1/4 bg-gray-800 text-white p-6 h-full overflow-y-auto">
            <?= $sidebarItems ?>
        </div>

        <!-- Content Area -->
        <div class="w-3/4 p-8 bg-white overflow-y-auto">
            <div class="max-w-5xl mx-auto">
                <!-- Course Title -->
                <h1 class="text-4xl font-bold mb-4 text-gray-800 border-b-2 border-gray-200 pb-4">
                    <?= htmlspecialchars($course === $defaultPage ? "Solidity_Course" : $course) ?>
                </h1>

                <!-- Content Section -->
                <div class="prose prose-lg prose-green max-w-none leading-relaxed">
                    <?= $htmlContent ?>
                </div>

                <!-- Navigation Links -->
                <div class="mt-8 flex justify-between">
                    <?php if ($prevCourse): ?>
                        <a href="?page=Solidity-Course&course=<?= htmlspecialchars($prevCourse) ?>" class="text-blue-500 hover:text-blue-700">&larr; Previous</a>
                    <?php else: ?>
                        <span class="text-gray-400">&larr; Previous</span>
                    <?php endif; ?>
                    
                    <?php if ($nextCourse): ?>
                        <a href="?page=Solidity-Course&course=<?= htmlspecialchars($nextCourse) ?>" class="text-blue-500 hover:text-blue-700">Next &rarr;</a>
                    <?php else: ?>
                        <span class="text-gray-400">Next &rarr;</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to highlight the active sidebar link -->
    <script>
        const currentCourse = "<?= htmlspecialchars($course) ?>";
        const activeLink = document.getElementById('link-' + currentCourse);
        if (activeLink) {
            activeLink.classList.add('text-blue-500', 'font-semibold');
        }
    </script>

</body>
</html>
