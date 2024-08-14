<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/homepage.css">
    <title>Create Announcement - Admin Panel</title>
</head>

<body>
    <div class="container mt-5 mb-5">
        <form id="announcementForm" action="processCreateAnnouncement.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="header text-center mb-4">
                <a href="homepage.php">
                    <img src="img/greenwoods_logo.png" alt="logo" class="img-fluid" style="width: 150px; height: auto;">
                </a>
                <h3>Greenwoods Executive Village - Admin Panel</h3>
            </div>

            <div class="text-start mb-4">
                <button class="btn btn-danger" onclick="window.location.href='adminAnnouncements.php';">Back</button>
            </div>

            <h2 class="text-center my-3">CREATE AN ANNOUNCEMENT</h2>

            <div class="mb-3">
                <label class="form-label" for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="content">Content</label>
                <textarea id="content" class="form-control" name="content" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="publishDate">Publish Date</label>
                <input type="date" class="form-control" id="publishDate" name="publishDate" value="<?php echo date('Y-m-d'); ?>">
                <small class="form-text text-muted">Leave this field empty to use today’s date.</small>
            </div>

            <div class="mb-4">
                <label class="form-label" for="attachment">Attachment (Optional)</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-success" value="Publish Announcement">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function validateForm() {
            const title = document.getElementById('title');
            const content = document.getElementById('content');
            const publishDate = document.getElementById('publishDate');

            if (!title.value.trim()) {
                alert("Please fill out the Title field.");
                title.focus();
                return false;
            }

            if (!content.value.trim()) {
                alert("Please fill out the Content field.");
                content.focus();
                return false;
            }

            if (!publishDate.value.trim()) {
                alert("Publish Date is missing.");
                publishDate.focus();
                return false;
            }

            // If all fields are filled, allow form submission
            return true;
        }
    </script>
</body>

</html>
