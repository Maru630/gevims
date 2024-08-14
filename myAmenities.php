<?php
session_start();
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$greenwoods_id = $_SESSION['user_id'];

// Fetch amenity reservations from booking_amenities table
$amenity_requests = [];
$sql = "SELECT * FROM booking_amenities WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $greenwoods_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $amenity_requests[] = $row;
    }
}

$stmt->close();
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

    function viewRequest(id) {
        const request = serviceRequests.find(r => r.id == id);
        
        document.getElementById('name').value = request.first_name;
        document.getElementById('lastName').value = request.last_name;
        document.getElementById('address').value = request.address;
        document.getElementById('phone').value = request.phone_number;
        document.getElementById('email').value = request.email;
        document.getElementById('date').value = request.date;
        document.getElementById('start_time').value = request.start_time;
        document.getElementById('end_time').value = request.end_time;
        document.getElementById('purpose').value = request.purpose;  // Fetch and set the purpose of reservation
        
        const amenityRadioButtons = document.getElementsByName('amenity');
        amenityRadioButtons.forEach(button => {
            if (button.value === request.amenity) {
                button.checked = true;
            }
        });

        toggleAdditionalFees();
    }

    function cancelRequest(id) {
        const confirmed = confirm('Are you sure you want to cancel this reservation?');
        if (confirmed) {
            fetch('cancelReservation.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reservation canceled.');
                    location.reload(); // Refresh the page to reflect the changes
                } else {
                    alert('Failed to cancel reservation.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>

<!-- Main content -->
<div class="container mt-5">
    <div class="section-title">
        <center><h1 class="display-4 fw-semibold">My Amenity Reservation</h1></center>
        <div class="line"></div>
    </div>

    <div class="mb-3">
        <label for="dateFilter" class="form-label">Filter by Date:</label>
        <input type="date" id="dateFilter" class="form-select">
    </div>

    <ul id="requestList" class="list-group">
        <?php foreach ($amenity_requests as $request): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>Date:</strong> <?= htmlspecialchars($request['date']) ?><br>
                <strong>Description:</strong> <?= htmlspecialchars($request['purpose']) ?>
            </div>
            <div class="action-buttons">
                <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#viewRequestModal" onclick="viewRequest(<?= htmlspecialchars($request['id']) ?>)">View Reservation</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="cancelRequest(<?= htmlspecialchars($request['id']) ?>)">Cancel Request</button>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- View Request Modal -->
<div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRequestModalLabel">View Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="requestForm">
                    <!-- Resident Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label" style="font-weight: bold;">First Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Juan">
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label" style="font-weight: bold;">Last Name:</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required placeholder="e.g. Dela Cruz">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="address" class="form-label" style="font-weight: bold;">Greenwoods Address:</label>
                            <textarea class="form-control" id="address" name="address" required></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label" style="font-weight: bold;">Phone Number:</label>
                            <input type="text" class="form-control" id="phone" name="phone" required placeholder="e.g. 09123456789">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label" style="font-weight: bold;">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="e.g. juan.delacruz@gmail.com">
                        </div>
                    </div>

                    <!-- Amenity Selection -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label mb-3" style="font-weight: bold;">Select Amenity:</label>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card amenity-card">
                                        <input type="radio" class="form-check-input" id="pavilion" name="amenity" value="Pavilion/Main Hall" required>
                                        <label class="form-check-label card-body" for="pavilion">
                                            <span class="material-symbols-outlined">house</span>
                                            <strong>Pavilion/Main Hall</strong><br>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card amenity-card">
                                        <input type="radio" class="form-check-input" id="swimming_pool" name="amenity" value="Swimming Pool" required>
                                        <label class="form-check-label card-body" for="swimming_pool">
                                            <span class="material-symbols-outlined">pool</span>    
                                            <strong>Swimming Pool</strong><br>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card amenity-card">
                                        <input type="radio" class="form-check-input" id="basketball_court" name="amenity" value="Basketball Court" required>
                                        <label class="form-check-label card-body" for="basketball_court">
                                            <span class="material-symbols-outlined">sports_basketball</span>    
                                            <strong>Basketball Court</strong><br>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card amenity-card">
                                        <input type="radio" class="form-check-input" id="picnic_grounds" name="amenity" value="Picnic Grounds" required>
                                        <label class="form-check-label card-body" for="picnic_grounds">
                                            <span class="material-symbols-outlined">shopping_basket</span>    
                                            <strong>Picnic Grounds</strong><br>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card amenity-card">
                                        <input type="radio" class="form-check-input" id="football_field" name="amenity" value="Football Field" required>
                                        <label class="form-check-label card-body" for="football_field">
                                            <span class="material-symbols-outlined">sports_soccer</span>    
                                            <strong>Football Field</strong><br>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Fees -->
                    <div id="additional-fees" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label mb-3" style="font-weight: bold;">Additional Fee of P600.00 for each of the following:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lightings_and_String_lights" name="additional_fees" value="Lightings and String Lights">
                                    <label class="form-check-label" for="lightings_and_String_lights">Lightings and String Lights</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="mobile_audio_video" name="additional_fees" value="Mobile/Audio/Video">
                                    <label class="form-check-label" for="mobile_audio_video">Mobile/Audio/Video</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="photo_booth_fog_machine" name="additional_fees" value="Photo Booth/Fog Machine">
                                    <label class="form-check-label" for="photo_booth_fog_machine">Photo Booth/Fog Machine</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reservation Details -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label" style="font-weight: bold;">Date:</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-4">
                            <label for="start_time" class="form-label" style="font-weight: bold;">Start Time:</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_time" class="form-label" style="font-weight: bold;">End Time:</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="purpose" class="form-label" style="font-weight: bold;">Purpose of Reservation:</label>
                            <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-third" onclick="saveChanges()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
    const serviceRequests = <?= json_encode($amenity_requests) ?>;

    function toggleAdditionalFees() {
        const selectedAmenity = document.querySelector('input[name="amenity"]:checked');
        const additionalFeesContainer = document.getElementById('additional-fees');
        if (selectedAmenity.value === 'Pavilion/Main Hall') {
            additionalFeesContainer.style.display = 'block';
        } else {
            additionalFeesContainer.style.display = 'none';
        }
    }

    function saveChanges() {
    const formData = new FormData(document.getElementById('requestForm'));

    fetch('updateAmenityReserve.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reservation updated.');
            location.reload(); // Refresh the page to reflect the changes
        } else {
            alert('Failed to update reservation.');
        }
    })
    .catch(error => console.error('Error:', error));
}


    document.getElementById('dateFilter').addEventListener('input', function() {
        const selectedDate = this.value;
        const filteredRequests = serviceRequests.filter(request => request.date === selectedDate);
        displayRequests(filteredRequests);
    });

    function displayRequests(requests) {
        const requestList = document.getElementById('requestList');
        requestList.innerHTML = '';
        requests.forEach(request => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.innerHTML = `
                <div>
                    <strong>Date:</strong> ${request.date}<br>
                    <strong>Description:</strong> ${request.purpose}
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#viewRequestModal" onclick="viewRequest(${request.id})">View Reservation</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="cancelRequest(${request.id})">Cancel Request</button>
                </div>
            `;
            requestList.appendChild(listItem);
        });
    }

    displayRequests(serviceRequests);
</script>
</body>
</html>
