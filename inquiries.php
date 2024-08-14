<?php
session_start();
include 'mysql_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$greenwoods_id = $_SESSION['user_id'];

// Fetch user info from residents table
$sql = "SELECT firstname, lastname, house_number, lot_number, block_number, street, phase, area_number, city, email FROM residents WHERE greenwoods_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $greenwoods_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$full_address = 'House ' . $user['house_number'] . ', ' . 'Blk.' . ' ' .  $user['block_number'] . ' ' . 'Lot' . ' ' . $user['lot_number'] . ' ' . $user['street'] . ' ' . 'Phase ' . $user['phase'] . ', ' . 'Area ' . $user['area_number'] . ', ' . $user['city'];

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
                if (!notificationList) return; // Exit if the notification list element is not found

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

        // Pre-fill form fields with user data
        const user = <?php echo json_encode($user); ?>;
        const fullAddress = <?php echo json_encode($full_address); ?>;
        const greenwoodsId = <?php echo json_encode($greenwoods_id); ?>;
        if (user) {
            document.getElementById('greenwoods_id').value = greenwoodsId;
            document.getElementById('first_name').value = user.firstname;
            document.getElementById('last_name').value = user.lastname;
            document.getElementById('address').value = fullAddress;
            document.getElementById('email').value = user.email;
        }
    });

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

    function submitForm(event) {
        event.preventDefault();

        const formData = new FormData(event.target);

        // Debugging: Log form data to ensure it's being sent correctly
        for (let pair of formData.entries()) {
            console.log(pair[0]+ ': ' + pair[1]); 
        }

        fetch('submitInquiry.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Submission Successful!',
                text: data.message,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'inquiries.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear the 'Subject' field after clicking 'OK'
                document.getElementById('subject').value = '';
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again later.'
        });
    });
}
</script>

<!-- Inquiry/Suggestion Form -->
<div class="container mt-5 mb-5">
<form id="inquiryForm" action="" onsubmit="submitForm(event)" method="POST" enctype="multipart/form-data">
    <div class="header text-center mb-4">
        <h1>INQUIRY AND SUGGESTION FORM</h1>
    </div>
    
    <div class="text-center mb-4">
        <p>Welcome to the Greenwoods Executive Village Inquiry and Suggestion Page. We value your feedback and are committed to improving our community. Please use this form to submit any inquiries or suggestions you may have. Our staff will review and respond to your submission promptly.</p>
    </div>
    
    <form onsubmit="submitForm(event)">
        <input type="hidden" name="form_type" value="inquiry">
        <input type="hidden" id="greenwoods_id" name="greenwoods_id" value="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label" style="font-weight: bold;">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required placeholder="e.g. Juan">
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label" style="font-weight: bold;">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required placeholder="e.g. Dela Cruz">
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
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="subject" class="form-label" style="font-weight: bold;">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required placeholder="e.g. Suggestion for improving security">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="details" class="form-label" style="font-weight: bold;">Details:</label>
                <textarea class="form-control" id="details" name="details" rows="5" required placeholder="Please provide your detailed inquiry or suggestion here..."></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label for="attachment" class="form-label" style="font-weight: bold;">Attachment (Optional):</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
            </div>
        </div>

        <!-- Acknowledgment Checkbox -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="acknowledgment" name="acknowledgment" required>
                    <label class="form-check-label" for="acknowledgment">
                        I acknowledge that the information provided is accurate and that the Greenwoods Executive Village staff may contact me for further details if necessary.
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
            <button type="submit" class="form-btn">SUBMIT INQUIRY/SUGGESTION</button>
        </div>
    </form>
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
                        <li><a href="inquiry.php">Make an Inquiry</a></li>
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
