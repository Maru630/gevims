<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="css/homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Announcement Management - Greenwoods Executive Village</title>
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
            <button class="btn btn-danger" onclick="window.location.href='adminDashboard.php';">Back</button>
        </div>

        <h2 class="text-center my-3">ANNOUNCEMENT MANAGEMENT</h2>
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-success" onclick="window.location.href='adminCreateAnnouncement.php';">CREATE NEW ANNOUNCEMENT</button>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php
                if ($_GET['message'] === 'deleted') {
                    echo "Announcement deleted successfully.";
                } elseif ($_GET['message'] === 'edited') {
                    echo "Announcement edited successfully.";
                } elseif ($_GET['message'] === 'created') {
                    echo "Announcement created successfully.";
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="list-group" id="announcementList">
        <?php
            include 'mysql_connect.php';

            $sql = "SELECT ann_id, ann_title, ann_content, published_date, attachment FROM announcements ORDER BY published_date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['ann_id'];
                    $title = $row['ann_title'];
                    $content = $row['ann_content'];
                    $publishedDate = new DateTime($row['published_date']);
                    $currentDate = new DateTime();
                    $interval = $publishedDate->diff($currentDate);

                    // Determine the display string
                    if ($interval->days == 0) {
                        $dateDisplay = 'Today';
                    } elseif ($interval->days == 1) {
                        $dateDisplay = 'Yesterday';
                    } else {
                        $dateDisplay = $publishedDate->format('F j, Y'); // e.g., "August 12, 2024"
                    }

                    $attachment = $row['attachment'];
                    
                    echo '<div class="list-group-item list-group-item-action">';
                    echo '<div class="d-flex w-100 justify-content-between align-items-start">';
                    echo '<div class="flex-grow-1">';
                    echo '<h5 class="mb-1">' . htmlspecialchars($title) . '</h5>';
                    echo '<p class="mb-1">' . nl2br(htmlspecialchars($content)) . '</p>';
                    if ($attachment) {
                        echo '<p><strong>Attachment:</strong> <a href="' . htmlspecialchars($attachment) . '" data-fancybox="gallery">View Attachment</a></p>';
                    }
                    echo '</div>';
                    echo '<div class="text-end" style="min-width: 200px; margin-left: 10px;">';
                    echo '<small class="d-block mb-2">' . $dateDisplay . '</small>'; // Simplified date display
                    echo '<button class="btn btn-sm btn-primary me-2" onclick="window.location.href=\'adminEditAnnouncement.php?id=' . $id . '\'">Edit</button>';
                    echo '<button class="btn btn-sm btn-danger" onclick="confirmDelete(\'' . htmlspecialchars($title) . '\', \'deleteAnnouncement.php?id=' . $id . '\')">Delete</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="list-group-item">No announcements found.</div>';
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function confirmDelete(title, url) {
            Swal.fire({
                title: `Delete Announcement: ${title}?`,
                text: "Are you sure you want to delete this announcement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to delete URL
                    window.location.href = url;
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="./js/main.js"></script>
</body>

</html>
