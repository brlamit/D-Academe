<?php
require_once './dbconnection.php'; // Database connection

// Fetch all artworks from the database
// $stmt = $pdo->query("SELECT * FROM Courses ORDER BY created_at DESC");
// $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Artworks</title>
    <link rel="stylesheet" href="../Assets/displaystyle.css">
</head>
<body>
    <h1 class="form-title">Admin Panel - Manage Courses</h1>
    
    <!-- Top-right buttons -->
    <a href="admin-uploadstruc.php" class="btn-add">Add Course</a>
    <div class="artwork-container">
        <?php foreach ($course as $course): ?>
            <div class="Course-box">
                <div class="course-image">
                    <img src="<?= $course['image_url']; ?>" alt="<?= htmlspecialchars($course['name']); ?>" style="max-width: 100%;">
                </div>
                <div class="course-title"c>
                <h2><?= htmlspecialchars($course['name']); ?></h2>
        </div>
                <div class="course-details">
                    
                    <p><?= htmlspecialchars($course['description']); ?></p>
                    <p><strong>Price:</strong> NRs<?= number_format($course['price'], 2); ?></p>
                </div>


                <div class="course-actions">
                <a href="course-updatestruc.php?id=<?= htmlspecialchars($course['id']); ?>" 
   class="btn" 
   aria-label="Edit Course <?= htmlspecialchars($course['name']); ?>">
   Edit
</a>
                    <a href="admin-delete.php?id=<?= $artwork['id']; ?>" 
   onclick="return confirm('Are you sure you want to delete this Course?');" 
   class="btn delete-btn">
   Delete
</a>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>