<!-- navbar.php -->
<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">
            <img src="./img/greenwoods_logo.png" alt="" style="width: 100px; height: auto;">
        </a>
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <a href="#" class="btn position-relative dropdown-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="material-symbols-outlined" style="color: green;">notifications</span>
                    <span id="notificationCount" class="position-absolute top-0 start-60 translate-middle badge bg-danger rounded-pill">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li class="dropdown-item">
                        <label for="notificationFilter" class="form-label">Filter by:</label>
                        <select class="form-select form-select-sm" id="notificationFilter">
                            <option value="all">All</option>
                            <option value="amenities">Reservations</option>
                            <option value="service">Service Requests</option>
                            <option value="dues">Payment Dues</option>
                        </select>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <ul id="notificationList" class="list-unstyled mb-0">
                        <!-- Notifications will be populated here -->
                    </ul>
                </ul>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="profile-picture" src="./img/sample1.png" alt="Profile Picture" style="width: 40px; height: 40px; border-radius: 50%;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<nav class="categories navbar navbar-expand-lg bg-green sticky-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#amenities" id="amenitiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Amenities
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="amenitiesDropdown">
                        <li><a class="dropdown-item" href="amenities.php">Amenities offered</a></li>
                        <li><a class="dropdown-item" href="bookAmenities.php">Book a reservation</a></li>
                        <li><a class="dropdown-item" href="myAmenities.php">My Amenities Reservation</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#services" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="services.php">Services offered</a></li>
                        <li><a class="dropdown-item" href="bookService.php">Book a service</a></li>
                        <li><a class="dropdown-item" href="myService.php">My Service Request</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#payments" id="paymentsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Payments
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="paymentsDropdown">
                        <li><a class="dropdown-item" href="payments.php">View Payments</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#bulletin" id="bulletinDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Bulletin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="bulletinDropdown">
                        <li><a class="dropdown-item" href="announcement.php">Announcements</a></li>
                        <li><a class="dropdown-item" href="events.php">Events</a></li>
                    </ul>
                </li>
            </ul>
            <div class="search-bar-container">
                <form class="d-flex search-bar">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-search" type="submit">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-placeholder').innerHTML = data;
                setupNotificationDropdown();
            });

        function setupNotificationDropdown() {
            const notificationButton = document.getElementById('notificationDropdown');
            const notificationMenu = document.getElementById('notificationMenu');
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
    });
</script>
