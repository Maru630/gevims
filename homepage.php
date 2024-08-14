<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the index page if not logged in
    header("Location: index.php");
    exit();
}

include 'mysql_connect.php';

// Fetch up to 3 random announcements
$announcementSql = "SELECT ann_id, ann_title, ann_content FROM announcements ORDER BY RAND() LIMIT 3";
$announcementResult = $conn->query($announcementSql);
$announcements = $announcementResult->fetch_all(MYSQLI_ASSOC);

// Fetch up to 3 random events
$eventSql = "SELECT event_id, event_name, event_desc FROM events ORDER BY RAND() LIMIT 3";
$eventResult = $conn->query($eventSql);
$events = $eventResult->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>Greenwoods Executive Village</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="css/homepage.css">
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

        // Placeholder for AJAX request to fetch notifications
        // Replace this with actual AJAX call if necessary
        const notifications = [
            { type: 'amenities', message: 'Your reservation for the clubhouse on August 13, 2024 is confirmed.' },
            { type: 'service', message: 'Your plumbing service request is in progress.' },
            { type: 'dues', message: 'Please settle your association dues by July 31, 2024.' }
        ];

        // Function to display notifications
        function displayNotifications(notifications) {
            notificationList.innerHTML = ''; // Clear previous content
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

        // Initialize with fetched notifications
        displayNotifications(notifications);

        // Filter notifications
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
</script>

<!-- Welcome Section -->
<section id="welcome" class="py-5 text-center">
    <div class="container">
        <h2>Welcome to Greenwoods Executive Village</h2>
        <p class="lead">Discover the perfect balance of comfort and community living.</p>
    </div>
</section>

<!-- Announcements Section -->
<section id="announcements" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center">Announcements</h2>
        <div class="row">
            <?php foreach ($announcements as $announcement): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($announcement['ann_title']); ?></h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars(substr($announcement['ann_content'], 0, 50)); ?>...
                            </p>
                            <a href="announcement.php?id=<?php echo $announcement['ann_id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (count($announcements) < 3): ?>
                <!-- Display an empty card if fewer than 3 announcements -->
                <div class="col-md-4"></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Carousel -->
<div id="carouselIndicators" class="carousel slide custom-carousel" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="./img/mainhall (2).jpg" class="d-block w-100" style="height: 500px; object-fit: cover; object-position: 30% 65%;" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="./img/picnic.jpg" class="d-block w-100" style="height: 500px; object-fit: cover; object-position: 30% 80%;" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="./img/swim (4).jpg" class="d-block w-100" style="height: 500px; object-fit: cover; object-position: 70% 50%;" alt="Slide 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Events Section -->
<section id="events" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center">Events</h2>
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars(substr($event['event_desc'], 0, 50)); ?>...
                            </p>
                            <a href="events.php?id=<?php echo $event['event_id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (count($events) < 3): ?>
                <!-- Display an empty card if fewer than 3 events -->
                <div class="col-md-4"></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
        /* Custom CSS */
        .theme-shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; 
        }

        .card-title {
            font-size: 1.5rem; 
            color: white;
        }

        .card-text {
            font-size: 1rem; 
            color: white;
        }

        .card {
            transition: transform 0.2s ease-in-out;
            background-color: #428a03; 
        }

        .card:hover {
            transform: translateY(-5px); 
        }

        
    </style>

<!-- Gallery Section -->
<section id="gallery" class="py-5">
    <div class="container">
        <h2 class="text-center">Gallery</h2>
        <div class="row">
            <div class="col-md-4">
                <a href="./img/entrace.jpg" data-fancybox="gallery" data-caption="Entrance">
                    <img src="./img/entrace.jpg" class="img-fluid mb-4" alt="Entrance">
                </a>
            </div>
            <div class="col-md-4">
                <a href="./img/fountain.jpg" data-fancybox="gallery" data-caption="Fountain">
                    <img src="./img/fountain.jpg" class="img-fluid mb-4" alt="Fountain">
                </a>
            </div>
            <div class="col-md-4">
                <a href="./img/office.jpg" data-fancybox="gallery" data-caption="Main Office">
                    <img src="./img/office.jpg" class="img-fluid mb-4" alt="Main Office">
                </a>
            </div>
            <div class="col-md-4">
                <a href="./img/pavillion.jpg" data-fancybox="gallery" data-caption="Pavillion">
                    <img src="./img/pavillion.jpg" class="img-fluid mb-4" alt="Pavillion">
                </a>
            </div>
            <div class="col-md-4">
                <a href="./img/basketball (3).jpg" data-fancybox="gallery" data-caption="Basketball Court">
                    <img src="./img/basketball (3).jpg" class="img-fluid mb-4" alt="Basketball">
                </a>
            </div>
            <div class="col-md-4">
                <a href="./img/playground (3).jpg" data-fancybox="gallery" data-caption="Playground">
                    <img src="./img/playground (3).jpg" class="img-fluid mb-4" alt="Playground">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Additional sections for amenities, gallery, etc., would go here... -->

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
                    <h5 class="mb-0 text-white">CONTACT</h5>
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
                    <h5 class="mb-0 text-white">QUICK LINKS</h5>
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
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="./js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
