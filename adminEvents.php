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
    <title>Event Management - Greenwoods Executive Village</title>
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

        <h2 class="text-center my-3">EVENT MANAGEMENT</h2>
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-success" onclick="window.location.href='adminCreateEvent.php';">CREATE NEW EVENT</button>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php
                if ($_GET['message'] === 'deleted') {
                    echo "Event deleted successfully.";
                } elseif ($_GET['message'] === 'edited') {
                    echo "Event edited successfully.";
                } elseif ($_GET['message'] === 'created') {
                    echo "Event created successfully.";
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="list-group" id="eventList">
        <?php
            include 'mysql_connect.php';

            $sql = "SELECT event_id, event_name, event_purpose, event_venue, event_day, event_stime, event_etime, event_desc, publish_date, attachment FROM events ORDER BY event_day DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['event_id'];
                    $name = $row['event_name'];
                    $purpose = $row['event_purpose'];
                    $venue = $row['event_venue'];
                    $description = $row['event_desc'];
                    $day = new DateTime($row['event_day']);
                    $stime = new DateTime($row['event_stime']);
                    $etime = new DateTime($row['event_etime']);
                    $formattedDay = $day->format('F j, Y');
                    $formattedStime = $stime->format('g:i A');
                    $formattedEtime = $etime->format('g:i A');
                    $dateDisplay = $formattedDay . ' from ' . $formattedStime . ' to ' . $formattedEtime;

                    $attachment = $row['attachment'];
                    
                    echo '<div class="list-group-item list-group-item-action">';
                    echo '<div class="d-flex w-100 justify-content-between align-items-start">';
                    echo '<div class="flex-grow-1">';
                    echo '<h5 class="mb-1">' . htmlspecialchars($name) . '</h5>';
                    echo '<p class="mb-1"><strong>What:</strong> ' . htmlspecialchars($purpose) . '<br><strong>Where:</strong> ' . htmlspecialchars($venue) . '<br><strong>When:</strong> ' . $dateDisplay . '</p>';
                    echo '<p class="mb-1"><strong>Description:</strong> ' . htmlspecialchars($description) . '</p>';
                    if ($attachment) {
                        echo '<p><strong>Attachment:</strong> <a href="' . htmlspecialchars($attachment) . '" data-fancybox="gallery">View Attachment</a></p>';
                    }
                    echo '</div>';
                    echo '<div class="text-end" style="min-width: 200px; margin-left: 10px;">';
                    echo '<small class="d-block mb-2">' . $formattedDay . '</small>'; // Simplified date display
                    echo '<button class="btn btn-sm btn-primary me-2" onclick="window.location.href=\'adminEditEvent.php?event_id=' . $id . '\'">Edit</button>';
                    echo '<button class="btn btn-sm btn-danger" onclick="confirmDelete(\'' . htmlspecialchars($name) . '\', \'deleteEvent.php?event_id=' . $id . '\')">Delete</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="list-group-item">No events found.</div>';
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function confirmDelete(name, url) {
            Swal.fire({
                title: `Delete Event: ${name}?`,
                text: "Are you sure you want to delete this event?",
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
