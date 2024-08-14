<?php
// Include the database connection file
include 'mysql_connect.php';

// Check if the 'event_id' parameter is present in the URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch the event data from the database
    $sql = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the event data
        $event = $result->fetch_assoc();
    } else {
        echo "No event found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No event ID specified.";
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
    <link rel="stylesheet" href="css/homepage.css">
    <title>Edit Event - Admin Panel</title>
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
            <button class="btn btn-danger" onclick="window.location.href='adminEvents.php';">Back</button>
        </div>

        <h2 class="text-center my-3">EDIT EVENT</h2>

        <form id="eventForm" action="processEditEvent.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

            <div class="mb-3">
                <label class="form-label" for="eventName">Event Name:</label>
                <input type="text" class="form-control" id="eventName" name="eventName" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label class="form-label" for="what">What:</label>
                    <input type="text" class="form-control" id="what" name="what" value="<?php echo htmlspecialchars($event['event_purpose']); ?>" required>
                </div>

                <div class="col-2">
                    <label class="form-label" for="where">Where:</label>
                    <input type="text" class="form-control" id="where" name="where" value="<?php echo htmlspecialchars($event['event_venue']); ?>" required>
                </div>

                <div class="col-2">
                    <label class="form-label" for="when">When:</label>
                    <input type="date" class="form-control" id="when" name="when" value="<?php echo $event['event_day']; ?>" required>
                </div>

                <div class="col-3">
                    <label class="form-label" for="timeStart">Start Time:</label>
                    <div class="d-flex align-items-center">
                        <?php
                        $startTime = new DateTime($event['event_stime']);
                        $startHour = $startTime->format('g');
                        $startMinute = $startTime->format('i');
                        $startPeriod = $startTime->format('A');
                        ?>
                        <input type="number" class="form-control me-1" id="startHour" name="startHour" min="1" max="12" placeholder="HH" value="<?php echo $startHour; ?>" required>
                        <span>:</span>
                        <input type="number" class="form-control ms-1" id="startMinute" name="startMinute" min="0" max="59" placeholder="MM" value="<?php echo $startMinute; ?>" required>
                        <select class="form-select ms-2" id="startPeriod" name="startPeriod" required>
                            <option value="AM" <?php if ($startPeriod == 'AM') echo 'selected'; ?>>AM</option>
                            <option value="PM" <?php if ($startPeriod == 'PM') echo 'selected'; ?>>PM</option>
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <label class="form-label" for="timeEnd">End Time:</label>
                    <div class="d-flex align-items-center">
                        <?php
                        $endTime = new DateTime($event['event_etime']);
                        $endHour = $endTime->format('g');
                        $endMinute = $endTime->format('i');
                        $endPeriod = $endTime->format('A');
                        ?>
                        <input type="number" class="form-control me-1" id="endHour" name="endHour" min="1" max="12" placeholder="HH" value="<?php echo $endHour; ?>" required>
                        <span>:</span>
                        <input type="number" class="form-control ms-1" id="endMinute" name="endMinute" min="0" max="59" placeholder="MM" value="<?php echo $endMinute; ?>" required>
                        <select class="form-select ms-2" id="endPeriod" name="endPeriod" required>
                            <option value="AM" <?php if ($endPeriod == 'AM') echo 'selected'; ?>>AM</option>
                            <option value="PM" <?php if ($endPeriod == 'PM') echo 'selected'; ?>>PM</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Description:</label>
                <textarea id="description" class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($event['event_desc']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="publishDate">Publish Date</label>
                <input type="date" class="form-control" id="publishDate" name="publishDate" value="<?php echo $event['publish_date']; ?>">
            </div>

            <div class="mb-4">
                <label class="form-label" for="attachment">Attachment (Optional)</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
                <?php if ($event['attachment']): ?>
                    <p>Current attachment: <a href="<?php echo $event['attachment']; ?>" data-fancybox="gallery">View</a></p>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-success" value="Update Event">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
</body>

</html>
