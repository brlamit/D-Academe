<?php
require_once 'dbconnection.php';

// Query to fetch all courses from the database
$sql = "SELECT id, name, image, description, token_price, course_content, date_of_upload FROM courses";
$result = $conn->query($sql);

// Initialize an empty array to hold the course data
$courses = [];

if ($result->num_rows > 0) {
    // Loop through all the results and add them to the $courses array
    while ($row = $result->fetch_assoc()) {
        // Build the full IPFS URL for course content
        $ipfs_url = "https://gateway.pinata.cloud/ipfs/" . $row['course_content']; // Pinata gateway

        // Generate tags dynamically by splitting the name and description into words
        $name_tags = explode(' ', strtolower($row['name']));
        $description_tags = explode(' ', strtolower($row['description']));
        $tags = array_unique(array_merge($name_tags, $description_tags)); // Combine and remove duplicates

        // Optionally filter out common stop words (like "the", "and", etc.)
        $stop_words = ['the', 'and', 'of', 'a', 'to', 'in', 'for', 'on', 'with', 'is', 'it', 'this'];
        $tags = array_diff($tags, $stop_words);

        // Add each course's data to the array
        $courses[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'image' => $row['image'], // Adjust if needed to include a full URL
            'description' => $row['description'],
            'token_price' => $row['token_price'],
            'course_content' => $ipfs_url, // Full IPFS URL
            'date_of_upload' => $row['date_of_upload'],
            'tags' => array_values($tags) // Add the dynamically generated tags
        ];
    }
}

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode($courses);
?>