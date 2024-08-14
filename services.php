<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>Greenwoods Executive Village</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="CSS/homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<!-- SERVICES -->
<section id="services-section" class="section-padding border-top">
    <div class="row">
    <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="150">
                    <div class="section-title">
                        <h1 class="display-4 fw-semibold">Village Services</h1>
                        <div class="line"></div>
                        <p style="font-size: 1.2rem; max-width: 900px; margin: 0 auto;">Discover the exceptional amenities at Greenwoods Executive Village that are all designed to enhance your lifestyle.</p>
                    </div>
                </div>
    </div>
    <div class="row">
        <div class="column">
            <a href="bookService.php" class="card-link">
                <div class="card">
                    <div class="icon-wrapper">
                        <span class="material-symbols-outlined">electrical_services</span>
                    </div>
                    <h5 class="card-title">Electricians</h5>
                    <p class="card-text">Skilled electricians to handle all your electrical needs.</p>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="bookService.php" class="card-link">
                <div class="card">
                    <div class="icon-wrapper">
                        <span class="material-symbols-outlined">plumbing</span>
                    </div>
                    <h5 class="card-title">Plumbers</h5>
                    <p class="card-text">Professional plumbers for any plumbing issues you may face.</p>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="bookService.php" class="card-link">
                <div class="card">
                    <div class="icon-wrapper">
                        <span class="material-symbols-outlined">build</span>
                    </div>
                    <h5 class="card-title">Masons</h5>
                    <p class="card-text">Expert masons for construction and repair projects.</p>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="bookService.php" class="card-link">
                <div class="card">
                    <div class="icon-wrapper">
                        <span class="material-symbols-outlined">construction</span>
                    </div>
                    <h5 class="card-title">Steelwork Specialists</h5>
                    <p class="card-text">Specialists in steelwork for various structural needs.</p>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="bookService.php" class="card-link">
                <div class="card">
                    <div class="icon-wrapper">
                        <span class="material-symbols-outlined">local_fire_department</span>
                    </div>
                    <h5 class="card-title">Hot Works Specialists</h5>
                    <p class="card-text">Professionals specialized in hot works for complex projects.</p>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="bookService.php" class="card-link">
                <div class="card">
                    <div class="icon-wrapper">
                        <span class="material-symbols-outlined">build_circle</span>
                    </div>
                    <h5 class="card-title">Maintenance and Repair</h5>
                    <p class="card-text">Comprehensive support for all maintenance needs.</p>
                </div>
            </a>
        </div>
    </div>
</section>



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
                        <li><a href="">Make an Inquiry</a></li>
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