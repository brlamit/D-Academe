<?php
require __DIR__ . '/Parsedown.php'; // Include Parsedown for Markdown parsing
$Parsedown = new Parsedown();

// Database connection (ensure `$conn` is defined in your project)
require_once 'dbconnection.php'; // Replace with your database connection script
include 'header.php'; // Include the header file
// Default values
$defaultMessage = "<p class='text-gray-500'>Select a course topic to begin.</p>";
$htmlContent = $defaultMessage;
// Initialize variables
$courseId = $name = $description = $tokenPrice = $image = $courseContent = ''; // Added courseContent
$message = null; // Error or success message

include('dbconnection.php');  // Assuming this file contains your database connection code

// Fetch course details based on course_id
if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];
    $sql = "SELECT * FROM paid_course_enrollments WHERE  course_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['course_name']);
            $imageUrl = htmlspecialchars($row['image']); // Fetch and sanitize image URL
            $description = htmlspecialchars($row['description']);
            $courseContent = htmlspecialchars($row['content']); // Fetch URL from the database
        } else {
            $message = "Course not found!";
        }
        $stmt->close();
    } else {
        $message = "Error fetching course details!";
    }
}

// Ensure that we append /summary.md to the course content URL fetched from the database
if ($courseContent) {
    $courseContent = rtrim($courseContent, '/'); // Remove trailing slashes if any
    $courseContentURL = $courseContent . '/SUMMARY.md'; // Append /summary.md
} else {
    $message = "No course content URL found in the database.";
}

// Fetch the content of the SUMMARY.md file
$markdownContent = @file_get_contents($courseContentURL); // Suppress errors to handle them later
// Initialize numbering variables
$sectionNumber = 0;
$subSectionNumber = 0;
$skipTopics = 3; // Number of topics to skip numbering for
$topics = []; // Ensure topics array is initialized

// Handle the scenario where SUMMARY.md file is not found
if ($markdownContent === false) {
    // If the file is not found, display the courseContent URL directly
    $message = "SUMMARY.md not found. You can view the course content <a href='$courseContent' class='text-blue-500 hover:underline' target='_blank'>here</a>.";
} else {
    // Parse the markdown file and extract topics if available
    $lines = explode("\n", $markdownContent);
 // Loop through lines of Markdown
foreach ($lines as $line) {
    // Determine indentation level based on leading spaces
    $indentLevel = (strlen($line) - strlen(ltrim($line))) / 2;

    // Extract topic links from the Markdown (assuming format: `- [Topic Name](URL)`)
    if (preg_match('/\[(.+?)\]\((.+?)\)/', $line, $matches)) {
        // Adjust numbering based on indentation level and skipped topics
        if (count($topics) >= $skipTopics) { // Start numbering after skipping topics
            if ($indentLevel === 0) {
                $sectionNumber++;
                $subSectionNumber = 0;
                $numbering = $sectionNumber;
            } else {
                $subSectionNumber++;
                $numbering = "$sectionNumber.$subSectionNumber";
            }
            $numberedName = "$numbering. " . $matches[1];
        } else {
            $numberedName = $matches[1]; // No numbering for skipped topics
        }

        // Prepend courseContent URL to the topic URL
        $fullUrl = $courseContent . '/' . ltrim($matches[2], '/'); // Ensure no double slashes

        // Add topic to the array
        $topics[] = [
            'name' => $numberedName,
            'url' => $fullUrl,
        ];
    }
}

    }


// If a specific topic is selected, fetch its markdown content
if (isset($_GET['topic_url'])) {
    $topicUrl = $_GET['topic_url'];
    
    // If the topic URL is relative, prepend the courseContent URL
    if (strpos($topicUrl, 'http') !== 0) {
        $topicUrl = $courseContent . '/' . ltrim($topicUrl, '/');
    }

    $topicMarkdownContent = @file_get_contents($topicUrl);

    if ($topicMarkdownContent !== false) {
        // Parse the markdown content and convert it to HTML
        $htmlContent = $Parsedown->text($topicMarkdownContent);
    } else {
        $htmlContent = "<p class='text-red-500'>Failed to load the selected topic content.</p>";
    }
}
// Determine the index of the current topic in the $topics array
$currentTopicIndex = null;
if (isset($_GET['topic_url'])) {
    foreach ($topics as $index => $topic) {
        if ($topic['url'] === $_GET['topic_url']) {
            $currentTopicIndex = $index;
            break;
        }
    }
}

// Determine the previous and next topics
$prevCourse = null;
$nextCourse = null;

if ($currentTopicIndex !== null) {
    if ($currentTopicIndex > 0) {
        $prevCourse = $topics[$currentTopicIndex - 1]['url'];
    }
    if ($currentTopicIndex < count($topics) - 1) {
        $nextCourse = $topics[$currentTopicIndex + 1]['url'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            /* color: #a0aec0; */
            text-color: #a0aec0;
            padding: 1em;
            border-radius: 0.5em;
            overflow-x: auto;
        }
        .content code {
            /* color: #a0aec0; */
            padding: 0.2em 0.4em;
            border-radius: 0.25em;
            /* color: #2d3748; */
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

<body class="bg-gray-100 font-sans mt-24">
<!-- Main Content Wrapper -->
<div class="flex min-h-screen ">
    <!-- Sidebar (Left) -->
    <div class="w-1/5 bg-white text-gray-800 h-full overflow-y-auto">
       <!-- Display the image fetched from the database -->
        <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($name) ?> Logo" class="w-60 h-auto object-contain hover:scale-105 transition-transform duration-300 opacity-80 hover:opacity-100">
     
        <!-- Course Topics -->
        <div class="bg-white shadow-md mt-8 rounded-lg p-6 left-0">
            <?php if (!empty($topics)): ?>
                <ul class="left-0 h-full pl-5 space-y-2">
                    <?php foreach ($topics as $topic): ?>
                        <li>
                            <a href="?course_id=<?= htmlspecialchars($courseId) ?>&topic_url=<?= urlencode($topic['url']) ?>" class="text-gray-900 hover:underline">
                                <?= htmlspecialchars($topic['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 text-lg">
                    
                    <a href="#" id="viewContentLink" class="text-blue-500 hover:underline" onclick="showIframe()">Continue Reading...</a>.
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content (Right) -->
    <div class="w-4/5 bg-white overflow-y-auto">
        <div class="max-w-1xl mx-auto content">   
            <!-- Content Section -->
            <div class="prose prose-lg prose-green pt-16 max-w-none mt-1 leading-relaxed text-2xl">
                <?php if (!empty($topics)): ?>
                    <?= $htmlContent ?>
                <?php else: ?>
                    <!-- Display Iframe for course content on the right side -->
                    <iframe id="courseContentIframe" src="" class="w-full h-screen mt-4 border border-gray-300 rounded-lg" style="display: none;"></iframe>
                <?php endif; ?>
            </div>

            <!-- Navigation Links -->
            <div id="navigationLinks" class="mt-8 flex justify-between">
                <?php if ($prevCourse): ?>
                    <a href="?course_id=<?= htmlspecialchars($courseId) ?>&topic_url=<?= htmlspecialchars($prevCourse) ?>" 
                    class="text-blue-500 hover:text-blue-700">&larr; Previous</a>
                <?php else: ?>
                    <span class="text-gray-400 relative left-11">&larr; Previous</span> <!-- Disabled -->
                <?php endif; ?>

                <?php if ($nextCourse): ?>
                    <a href="?course_id=<?= htmlspecialchars($courseId) ?>&topic_url=<?= htmlspecialchars($nextCourse) ?>" 
                    class="text-blue-500 hover:text-blue-700 relative right-11">Next &rarr;</a>
                <?php else: ?>
                    <span class="text-gray-400">Next &rarr;</span> <!-- Disabled -->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function showIframe() {
        var iframe = document.getElementById('courseContentIframe');
        var courseContentUrl = '<?= htmlspecialchars($courseContent) ?>';
        var navigationLinks = document.getElementById('navigationLinks');

        // Show the iframe with course content
        iframe.src = courseContentUrl;
        iframe.style.display = 'block'; // Show the iframe

        // Hide the navigation links
        navigationLinks.style.display = 'none';
    }
</script>
</body>

</body>
</html><?php include 'footer.php'; // Include the footer file ?>