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
    <link rel="stylesheet" href="css/homepage.css">
    <style>
        .form-label {
            font-weight: bold;
            color: green;
        }
        .form-select {
            width: 100%;
            max-width: 300px;
            padding: 8px;
            border: 3px solid #428a03;
            border-radius: 4px;
        }
        .list-group-item {
            border: none;
            border-top: 1px solid #e9ecef;
            background-color: #ffffff;
            padding: 15px;
            margin-bottom: 5px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .list-group-item:last-child {
            margin-bottom: 0;
        }
        .list-group-item:hover {
            background-color: #428a03;
            color: #ffffff;
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar">

<div id="navbar-placeholder"></div>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('navbar.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-placeholder').innerHTML = data;
                });
        });
    </script>


 <!-- Main content -->
 <div class="container mt-5">
 <div class="container">
    <div class="section-title" >
    <center><h1 class="display-4 fw-semibold">Notifications</h1></center>
    <div class="line"></div>
    </div>
</div>

        <!-- Filter options -->
        <div class="mb-3">
            <label for="notificationFilter" class="form-label">Filter by:</label>
            <select class="form-select" id="notificationFilter">
                <option value="all">All</option>
                <option value="amenities">Reservations</option>
                <option value="service">Service Requests</option>
                <option value="dues">Payment Dues</option>
            </select>
        </div>

        <!-- Notification list -->
        <ul class="list-group" id="notificationList">
            <!-- Notifications will be dynamically populated here -->
        </ul>
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
                            <li><a href="#about">About</a></li>
                            <li><a href="#amenities">Amenities</a></li>
                            <li><a href="#gallery">Gallery</a></li>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('navbar.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-placeholder').innerHTML = data;
                });
        });

        // Example
        const notifications = [
            { type: 'amenities', message: 'Your reservation for the clubhouse on August 13, 2024 is confirmed.' },
            { type: 'service', message: 'Your plumbing service request is in progress.' },
            { type: 'dues', message: 'Please settle your association dues by July 31, 2024.' }

        ];

        // Filter
        document.getElementById('notificationFilter').addEventListener('change', function() {
            const selectedFilter = this.value;
            const filteredNotifications = selectedFilter === 'all' ? notifications : notifications.filter(notification => notification.type === selectedFilter);
            displayNotifications(filteredNotifications);
        });

        // Function to displaying notification
        function displayNotifications(notifications) {
            const notificationList = document.getElementById('notificationList');
            notificationList.innerHTML = '';
            notifications.forEach(notification => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item';
                listItem.innerText = notification.message;
                notificationList.appendChild(listItem);
            });
        }

        // Display of all notifications
        displayNotifications(notifications);
    </script>
</body>
</html>