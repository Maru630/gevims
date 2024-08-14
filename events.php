<?php
// Include the database connection file
include 'mysql_connect.php';

// Set the default time zone to GMT+08:00 (Philippine Time)
date_default_timezone_set('Asia/Manila');

// Function to check if a file is an image
function isImage($filePath) {
    $imageFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    return in_array($extension, $imageFileTypes);
}

// Function to calculate and format the time elapsed for events
function formatDateDisplay($date) {
    $eventDate = new DateTime($date);
    $currentDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $interval = $eventDate->diff($currentDate);

    if ($interval->days == 0) {
        return 'Today';
    } elseif ($interval->days == 1) {
        return 'Yesterday';
    } else {
        return $eventDate->format('F j, Y');
    }
}

// Function to get the file name from a file path
function getFileName($filePath) {
    return basename($filePath);
}

// Check if an event ID is provided in the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch the specific event
    $sql = "SELECT event_name, event_desc, event_day, event_stime, event_etime, publish_date, attachment FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    // Display the full event
} else {
    // Fetch all events if no ID is provided
    $sql = "SELECT event_id, event_name, event_desc, event_day, event_stime, event_etime, publish_date, attachment FROM events ORDER BY publish_date DESC";
    $result = $conn->query($sql);
    $events = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title><?php echo isset($event) ? htmlspecialchars($event['event_name']) : 'Events - Greenwoods Executive Village'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="css/announcement.css"> <!-- Link to the new CSS file -->
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar">

<div id="navbar-placeholder"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-placeholder').innerHTML = data;
                setupNotificationDropdown(); // Initialize the notification dropdown
            });
    });

    function setupNotificationDropdown() {
        const notificationCount = document.getElementById('notificationCount');
        const notificationFilter = document.getElementById('notificationFilter');
        const notificationList = document.getElementById('notificationList');

        const notifications = [
            { type: 'amenities', message: 'Your reservation for the clubhouse on August 13, 2024 is confirmed.' },
            { type: 'service', message: 'Your plumbing service request is in progress.' },
            { type: 'dues', message: 'Please settle your association dues by July 31, 2024.' }
        ];

        function displayNotifications(notifications) {
            notificationList.innerHTML = '';
            notifications.forEach(notification => {
                const listItem = document.createElement('li');
                listItem.className = 'dropdown-item';
                listItem.innerText = notification.message;
                notificationList.appendChild(listItem);
            });
            if (notifications.length === 0) {
                const noNotificationItem = document.createElement('li');
                noNotificationItem.className = 'dropdown-item text-center';
                noNotificationItem.innerText = 'No new notifications';
                notificationList.appendChild(noNotificationItem);
            } else {
                notificationCount.innerText = notifications.length;
            }
        }

        displayNotifications(notifications);

        notificationFilter.addEventListener('change', function() {
            const selectedFilter = this.value;
            const filteredNotifications = selectedFilter === 'all' ? notifications : notifications.filter(notification => notification.type === selectedFilter);
            displayNotifications(filteredNotifications);
        });
    }

    function logout() {
        Swal.fire({
            title: 'Logging Out',
            text: 'Your account is being logged out...',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false,
            willClose: () => {
                window.location.href = 'logout.php';
            }
        });
    }

    function toggleContent(id) {
        const dots = document.getElementById("dots-" + id);
        const moreText = document.getElementById("more-" + id);
        const btnText = document.getElementById("myBtn-" + id);

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            btnText.innerHTML = "See More...";
            moreText.style.display = "none";
        } else {
            dots.style.display = "none";
            btnText.innerHTML = "See Less";
            moreText.style.display = "inline";
        }
    }
</script>

<div class="container">
    <?php if (isset($event)): ?>
        <div class="section-title">
            <center><h1 class="display-4 fw-semibold"><?php echo htmlspecialchars($event['event_name']); ?></h1></center>
            <div class="line"></div>
        </div>

        <div>
            <p><strong>Date Published:</strong> <?php echo formatDateDisplay($event['publish_date']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($event['event_desc'])); ?></p>
            <p><strong>Date of Event:</strong> <?php echo date('F j, Y', strtotime($event['event_day'])); ?></p>
            <p><strong>Time of Event:</strong> <?php echo date('g:i A', strtotime($event['event_stime'])) . " - " . date('g:i A', strtotime($event['event_etime'])); ?></p>

            <?php if ($event['attachment']): ?>
                <div class="attachment">
                    <h5>Attachment:</h5>
                    <?php if (isImage($event['attachment'])): ?>
                        <a data-fancybox="gallery" href="<?php echo htmlspecialchars($event['attachment']); ?>" data-caption="<?php echo htmlspecialchars($event['event_name']); ?>">
                            <img src="<?php echo htmlspecialchars($event['attachment']); ?>" alt="Attachment" class="img-fluid">
                        </a>
                    <?php else: ?>
                        <p><a href="<?php echo htmlspecialchars($event['attachment']); ?>" download><?php echo htmlspecialchars(getFileName($event['attachment'])); ?></a></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="section-title">
            <center><h1 class="display-4 fw-semibold">Events</h1></center>
            <div class="line"></div>
        </div>

        <div class="announcement-feed-container">
            <?php foreach ($events as $event): ?>
                <div class="announcement-feed">
                    <h5><?php echo htmlspecialchars($event['event_name']); ?></h5>
                    <div class="date"><?php echo formatDateDisplay($event['publish_date']); ?></div>
                    <p>
                        <?php
                        if (strlen($event['event_desc']) > 50) {
                            $shortContent = htmlspecialchars(substr($event['event_desc'], 0, 50));
                            $fullContent = htmlspecialchars(substr($event['event_desc'], 50));
                            echo $shortContent;
                            echo '<span id="dots-' . $event['event_id'] . '">...</span>';
                            echo '<span id="more-' . $event['event_id'] . '" style="display: none;">' . $fullContent . '</span>';
                            echo '&nbsp;<a href="javascript:void(0);" onclick="toggleContent(' . $event['event_id'] . ');" id="myBtn-' . $event['event_id'] . '">See More...</a>';
                        } else {
                            echo nl2br(htmlspecialchars($event['event_desc']));
                        }
                        ?>
                    </p>

                    <?php if ($event['attachment']): ?>
                        <div class="attachment">
                            <?php if (isImage($event['attachment'])): ?>
                                <a data-fancybox="gallery" href="<?php echo htmlspecialchars($event['attachment']); ?>" data-caption="<?php echo htmlspecialchars($event['event_name']); ?>">
                                    <img src="<?php echo htmlspecialchars($event['attachment']); ?>" alt="Attachment" class="img-fluid">
                                </a>
                            <?php else: ?>
                                <p><a href="<?php echo htmlspecialchars($event['attachment']); ?>" download><?php echo htmlspecialchars(getFileName($event['attachment'])); ?></a></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="bg-white">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-3 col-sm-6">
                    <a href="#"><img src="./img/greenwoods_logo.png" alt=""></a>
                    <div class="line"></div>
                    <p>We look forward to welcoming you to our community soon!</p>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 class="mb-0 text-green">CONTACT</h5>
                    <div class="line"></div>
                    <ul>
                        <li>Tulip St. Phase 2, Greenwoods Executive Village, San Andres, Cainta, Rizal 1900</li>
                        <li>8470-4057 / 7276-1723</li>
                        <li>gevhaioffice.gev2022@gmail.com</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 class="mb-0 text-green">QUICK LINKS</h5>
                    <div class="line"></div>
                    <ul>
                        <li><a href="bookAmenities.php">Amenities</a></li>
                        <li><a href="bookService.php">Services</a></li>
                        <li><a href="payments.php">Payments</a></li>
                        <li><a href="announcement.php">Bulletin</a></li>
                        <li><a href="inquiries.php">Make an Inquiry</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <strong>© 2024 MarsTech. </strong>All rights reserved.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="./js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Initialize Fancybox with zoom feature -->
<script>
    Fancybox.bind("[data-fancybox='gallery']", {
        Toolbar: {
            display: [
                "zoom",
                "close",
            ],
        },
        Image: {
            zoom: true,
            click: "zoom",
            wheel: "zoom",
        },
        Buttons: [
            "zoom",
            "close"
        ],
    });
</script>

</body>
</html>
