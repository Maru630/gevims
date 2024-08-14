<?php
// Include the database connection file
include 'mysql_connect.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $ann_id = $_GET['id'];

    // Fetch the announcement data from the database
    $sql = "SELECT * FROM announcements WHERE ann_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ann_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the announcement data
        $announcement = $result->fetch_assoc();
    } else {
        echo "No announcement found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No announcement ID specified.";
    exit;
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="css/homepage.css">
    <title>Edit Announcement - Admin Panel</title>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="header text-center mb-4">
            <a href="homepage.php">
                <img src="img/greenwoods_logo.png" alt="logo" class="img-fluid" style="width: 150px; height: auto;">
            </a>
            <h3>Greenwoods Executive Village - Admin Panel</h3>
        </div>

        <div class="text-start mb-4">
            <button class="btn btn-danger" onclick="window.location.href='adminAnnouncements.php';">Back</button>
        </div>

        <h2 class="text-center my-3">EDIT ANNOUNCEMENT</h2>

        <form id="announcementForm" action="processEditAnnouncement.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ann_id" value="<?php echo $announcement['ann_id']; ?>">

            <div class="mb-3">
                <label class="form-label" for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($announcement['ann_title']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="content">Content</label>
                <textarea id="content" class="form-control" name="content" rows="5" required><?php echo htmlspecialchars($announcement['ann_content']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="publishDate">Publish Date</label>
                <input type="date" class="form-control" id="publishDate" name="publishDate" value="<?php echo $announcement['published_date']; ?>">
            </div>

            <div class="mb-4">
                <label class="form-label" for="attachment">Attachment (Optional)</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
                <?php if ($announcement['attachment']): ?>
                    <p>Current attachment: <a href="<?php echo $announcement['attachment']; ?>" data-fancybox="gallery">View</a></p>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-success" value="Update Announcement">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
</body>

</html>
