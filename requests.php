<?php
session_start();
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$greenwoods_id = $_SESSION['user_id'];

// Fetch data from booking_amenities table
$amenities_requests = [];
$amenities_sql = "SELECT booking_no AS id, first_name, last_name, amenities AS request_type, date AS date_submitted, status, start_time, end_time FROM booking_amenities WHERE user_id = ?";
$stmt = $conn->prepare($amenities_sql);
$stmt->bind_param("i", $greenwoods_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['table'] = 'booking_amenities';
        $amenities_requests[] = $row;
    }
}
$stmt->close();

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

// Combine both requests
$requests = array_merge($amenities_requests, $services_requests);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_POST['delete_table'])) {
    $delete_id = $_POST['delete_id'];
    $delete_table = $_POST['delete_table'];

    if ($delete_table === 'booking_amenities') {
        $delete_sql = "DELETE FROM booking_amenities WHERE booking_no = ? AND user_id = ?";
    } elseif ($delete_table === 'booking_services') {
        $delete_sql = "DELETE FROM booking_services WHERE request_no = ? AND user_id = ?";
    }

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <title>My Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="CSS/homepage.css">
    <style>
        .filter-dropdown {
            position: absolute;
            right: 0;
        }
        .dropdown-toggle:focus, .dropdown-toggle:hover {
            outline: none;
            box-shadow: none;
        }
        .dropdown-toggle::after {
            display: none;
        }
        .filter-option.active {
            font-weight: bold;
        }
        .dropdown-menu {
            position: absolute;
            top: 45px;
            right: -25px; /* Adjust based on the alignment of the dropdown */
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px; /* Adjust the width as needed */
        }
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -10px;
            right: 30px; /* Adjust based on the alignment of the dropdown */
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid white; /* Match the color of the dropdown menu */
        }
        .dropdown-item {
            white-space: normal; /* Ensure text wraps within the dropdown */
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
                setupNotificationDropdown();
            });

        function setupNotificationDropdown() {
            const notificationButton = document.getElementById('notificationDropdown');
            const notificationMenu = document.getElementById('notificationMenu');
            const notificationCount = document.getElementById('notificationCount');
            const notificationFilter = document.getElementById('notificationFilter');
            const notificationList = document.getElementById('notificationList');
            
            // Placeholder for AJAX request to fetch notifications
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
    });
</script>

<!-- MY REQUESTS -->
<section id="my-requests" class="section-padding border-top">
    <div class="container">
        <div class="row position-relative mb-4">
            <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="150">
                <div class="section-title">
                    <h1 class="display-4 fw-semibold">My Requests</h1>
                    <div class="line"></div>
                    <p style="font-size: 1.2rem; max-width: 900px; margin: 0 auto;">Here you can view and manage your requests.</p>
                </div>
            </div>
            <div class="col-12 text-end filter-dropdown">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-symbols-outlined">filter_list</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item filter-option active" href="#" data-filter="all">All</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="pending">Pending Requests</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="approved">Request Approved</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="in-progress">In-Progress</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="completed">Completed Requests</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-filter="cancelled">Cancelled Requests</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row g-4 text-center">
            <div class="col-lg-12 col-sm-12" data-aos="fade-down" data-aos-delay="150">
                <div class="requests theme-shadow p-lg-5 p-4">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Request ID</th>
                                    <th scope="col">Request Type</th>
                                    <th scope="col">Date Submitted</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $request): ?>
                                <tr class="request-row" data-status="pending">
                                    <td><?= $request['id'] ?></td>
                                    <td><?= $request['request_type'] ?></td>
                                    <td><?= $request['date_submitted'] ?></td>
                                    <td>Pending</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-request" 
                                                data-id="<?= $request['id'] ?>" 
                                                data-first-name="<?= $request['first_name'] ?>" 
                                                data-last-name="<?= $request['last_name'] ?>" 
                                                data-request-type="<?= $request['request_type'] ?>" 
                                                data-date-submitted="<?= $request['date_submitted'] ?>" 
                                                data-status="Pending"
                                                <?php if ($request['table'] == 'booking_amenities'): ?>
                                                data-start-time="<?= $request['start_time'] ?>"
                                                data-end-time="<?= $request['end_time'] ?>"
                                                <?php endif; ?>>View</button>
                                        <button class="btn btn-danger btn-sm cancel-request" 
                                                data-id="<?= $request['id'] ?>" 
                                                data-table="<?= $request['table'] ?>">Cancel</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                        <li><a href="#amenities">Amenities</a></li>
                        <li><a href="#gallery">Services</a></li>
                        <li><a href="#gallery">News</a></li>
                        <li><a href="#gallery">Payments</a></li>
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

document.addEventListener('DOMContentLoaded', function() {
    const filterOptions = document.querySelectorAll('.filter-option');
    const requestRows = document.querySelectorAll('.request-row');

    filterOptions.forEach(option => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            const filter = option.getAttribute('data-filter');

            requestRows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'pending') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            filterOptions.forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
        });
    });

    // Trigger the default filter
    document.querySelector('.filter-option[data-filter="all"]').click();

    // SweetAlert for viewing request details
    document.querySelectorAll('.view-request').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-id');
            const firstName = this.getAttribute('data-first-name');
            const lastName = this.getAttribute('data-last-name');
            const requestType = this.getAttribute('data-request-type');
            const dateSubmitted = this.getAttribute('data-date-submitted');
            const status = this.getAttribute('data-status');
            const startTime = this.getAttribute('data-start-time') || 'N/A';
            const endTime = this.getAttribute('data-end-time') || 'N/A';

            Swal.fire({
                title: `Request ID: ${requestId}`,
                html: `
                    <p><strong>First Name:</strong> ${firstName}</p>
                    <p><strong>Last Name:</strong> ${lastName}</p>
                    <p><strong>Request Type:</strong> ${requestType}</p>
                    <p><strong>Date Submitted:</strong> ${dateSubmitted}</p>
                    <p><strong>Status:</strong> ${status}</p>
                    <p><strong>Start Time:</strong> ${startTime}</p>
                    <p><strong>End Time:</strong> ${endTime}</p>
                `,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        });
    });

    // AJAX request for deleting a request
    document.querySelectorAll('.cancel-request').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-id');
            const requestTable = this.getAttribute('data-table');
            const row = this.closest('.request-row');

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
                    fetch('requests.php', {
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

<?php
$conn->close();
?>
