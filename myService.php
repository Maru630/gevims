<?php
session_start();
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$greenwoods_id = $_SESSION['user_id'];

// Fetch data from booking_services table
$services_requests = [];
$services_sql = "SELECT request_no AS id, first_name, last_name, service_type AS request_type, requestdate AS date_submitted, status FROM booking_services WHERE user_id = ?";
$stmt = $conn->prepare($services_sql);
$stmt->bind_param("i", $greenwoods_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['table'] = 'booking_services';
        $services_requests[] = $row;
    }
}
$stmt->close();

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_POST['delete_table'])) {
    $delete_id = $_POST['delete_id'];
    $delete_table = $_POST['delete_table'];

    if ($delete_table === 'booking_services') {
        $delete_sql = "DELETE FROM booking_services WHERE request_no = ? AND user_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("ii", $delete_id, $greenwoods_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete request']);
        }
        $stmt->close();
        $conn->close();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>My Service Request</title>
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
        #other-service {
            display: none;
        }
        .modal-footer {
            justify-content: flex-end; 
        }
        .btn-primary {
            background-color: #2c7c99; 
            border-color: #2c7c99;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #3ca7cf; 
            border-color: #3ca7cf;
        }
        .btn-secondary {
            background-color: #6c757d; 
            border-color: #6c757d;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-secondary:hover {
            background-color: #5a6268; 
            border-color: #5a6268;
        }

        .btn-third {
            background-color: #428a03; 
            border-color: #428a03;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-third:hover {
            background-color: #52ad02; 
            border-color: #52ad02;
            color: #ffffff
        }

        .btn-danger {
            background-color: #dc3545; 
            border-color: #dc3545;
            color: #ffffff;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #c82333; 
            border-color: #bd2130;
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

<!-- Main content -->
<div class="container mt-5">
    <div class="section-title">
        <center><h1 class="display-4 fw-semibold">My Service Request</h1></center>
        <div class="line"></div>
    </div>

    <div class="mb-3">
        <label for="dateFilter" class="form-label">Filter by Date:</label>
        <input type="date" id="dateFilter" class="form-select">
    </div>

    <ul id="requestList" class="list-group">
        <?php foreach ($services_requests as $request): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>Date:</strong> <?= $request['date_submitted'] ?><br>
                <strong>Service:</strong> <?= $request['request_type'] ?>
            </div>
            <div class="action-buttons">
                <button class="btn btn-primary btn-sm me-2 view-request" data-id="<?= $request['id'] ?>">View</button>
                <button class="btn btn-danger btn-sm cancel-request" data-id="<?= $request['id'] ?>" data-table="booking_services">Cancel</button>
            </div>
        </li>
        <?php endforeach; ?>
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
                <div class="col-lg-3 col-sm-6"></div>
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
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="./js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-request').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-id');
            // Code to display request details...
        });
    });

    document.querySelectorAll('.cancel-request').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-id');
            const requestTable = this.getAttribute('data-table');
            const row = this.closest('.list-group-item');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('myService.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'delete_id': requestId,
                            'delete_table': requestTable
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire(
                                'Cancelled!',
                                'Your request has been cancelled.',
                                'success'
                            );
                            row.remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'There was an error processing your request.',
                            'error'
                        );
                    });
                }
            });
        });
    });
});
</script>
</body>
</html>
