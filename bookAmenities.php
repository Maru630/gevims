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

    function toggleAdditionalFees() {
        const selectedAmenity = document.querySelector('input[name="amenity"]:checked');
        if (!selectedAmenity) return; // Exit if no amenity is selected

        if (selectedAmenity.value === 'Pavilion/Main Hall') {
            additionalFeesContainer.style.display = 'block';
        } else {
            additionalFeesContainer.style.display = 'none';
            additionalFeesCheckboxes.forEach(checkbox => checkbox.checked = false);
            document.getElementById('totalAdditionalFees').textContent = '0';
        }
        calculateAdditionalFees();
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

    function submitForm(event) {
        event.preventDefault();

        const formData = new FormData(event.target);

        // Debugging: Log form data to ensure it's being sent correctly
        for (let pair of formData.entries()) {
            console.log(pair[0]+ ': ' + pair[1]); 
        }

        fetch('submitBooking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Reservation Successful!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'myAmenities.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
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

<!-- Amenity Reservation Form -->
<div class="container mt-5 mb-5">
    <div class="header text-center mb-4">
        <h1>AMENITY RESERVATION FORM</h1>
    </div>
    <form onsubmit="submitForm(event)">
    <input type="hidden" name="form_type" value="amenities">
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

        <!-- Amenity Selection -->
        <div class="row mb-4">
            <div class="col-md-12">
                <label class="form-label mb-3" style="font-weight: bold;">Select Amenity:</label>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card amenity-card">
                            <input type="radio" class="form-check-input" id="pavilion" name="amenity" value="Pavilion/Main Hall" required onchange="toggleAdditionalFees()">
                            <label class="form-check-label card-body" for="pavilion">
                                <span class="material-symbols-outlined">house</span>
                                <strong>Pavilion/Main Hall</strong><br>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card amenity-card">
                            <input type="radio" class="form-check-input" id="swimming_pool" name="amenity" value="Swimming Pool" required onchange="toggleAdditionalFees()">
                            <label class="form-check-label card-body" for="swimming_pool">
                                <span class="material-symbols-outlined">pool</span>    
                                <strong>Swimming Pool</strong><br>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card amenity-card">
                            <input type="radio" class="form-check-input" id="basketball_court" name="amenity" value="Basketball Court" required onchange="toggleAdditionalFees()">
                            <label class="form-check-label card-body" for="basketball_court">
                                <span class="material-symbols-outlined">sports_basketball</span>    
                                <strong>Basketball Court</strong><br>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card amenity-card">
                            <input type="radio" class="form-check-input" id="picnic_grounds" name="amenity" value="Picnic Grounds" required onchange="toggleAdditionalFees()">
                            <label class="form-check-label card-body" for="picnic_grounds">
                                <span class="material-symbols-outlined">shopping_basket</span>    
                                <strong>Picnic Grounds</strong><br>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card amenity-card">
                            <input type="radio" class="form-check-input" id="football_field" name="amenity" value="Football Field" required onchange="toggleAdditionalFees()">
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
                        <input class="form-check-input" type="checkbox" id="lightings_and_String_lights" name="additional_fees[]" value="Lightings and String Lights">
                        <label class="form-check-label" for="lightings_and_String_lights">Lightings and String Lights</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="mobile_audio_video" name="additional_fees[]" value="Mobile/Audio/Video">
                        <label class="form-check-label" for="mobile_audio_video">Mobile/Audio/Video</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="photo_booth_fog_machine" name="additional_fees[]" value="Photo Booth/Fog Machine">
                        <label class="form-check-label" for="photo_booth_fog_machine">Photo Booth/Fog Machine</label>
                    </div>
                    <div class="mt-3">
                        <strong>Total Additional Fees:</strong> ₱<span id="totalAdditionalFees">0</span>
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

        <div class="note">
            <p><strong>Note:</strong> PLEASE SUBMIT THIS FORM AT LEAST 2-3 DAYS BEFORE YOUR PREFERRED RESERVATION DATE. YOUR RESERVATION IS SUBJECT TO AVAILABILITY AND APPROVAL.</p>
            <button type="button" class="btn btn-link p-0" style="color: red; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#rulesModal">Guidelines for use of amenities</button>
        </div>
        
        <!-- Submit Button -->
        <div class="text-center mt-4">
            <button type="submit" class="form-btn">RESERVE AMENITY</button>
        </div>
    </form>
</div>

<!-- MODAL POP-UP FOR RULES -->
<div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rulesModalLabel">Amenities Usage Guidelines</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ol><strong>a.</strong> Reservation should be at least 7 days prior to actual use of the facility.</ol>
                <ol><strong>b.</strong> Coordinate with the facility management or facility staff on duty to complete the reservation process including review of facility schedule and rental fee.</ol>
                <ol><strong>c.</strong> A 50% down payment upon reservation or pencil blocking of date is required, remaining balance must be paid at least 7 days before the scheduled event.</ol>
                <ol><strong>d.</strong> Down payment will be forfeited if cancellation is done 7 days before the scheduled event.</ol>
                <ol><strong>e.</strong> Re-scheduling will be allowed one day before the event if there is a sudden weather disturbance and may only be re-scheduled to an available date.</ol>
                <ol><strong>f.</strong> A Bond of Php 3,000.00 is required for the use of the Pavillion. Payment upon the reservation of the event. Refundable at the end of the event if there is no charges or violation on the rules.</ol>
                <ol><strong>g.</strong> Additional fee will be charged for every use of electrical equipment not included in the package. Charges will start from Php 300.00 and up depending on the equipment to be used.</ol>
                <ol><strong>h.</strong> For night event, the volume of sound/music must be turned off by 9:00PM.</ol>
                <ol><strong>i.</strong> The venue must be used up to six (6) hours per event.</ol>
                <ol><strong>j.</strong> An additional of Php 1,000.00/HR for the extension during daytime and Php 1,500.00/HR during night time will be charged.</ol>
                <ol><strong>k.</strong> GEVHAI management shall not be held liable for any loss or damage to valuables, vehicles, physical injury or any unforeseen incident that may happen to the client or guest.</ol>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amenityRadios = document.querySelectorAll('input[name="amenity"]');
    const additionalFeesCheckboxes = document.querySelectorAll('input[name="additional_fees[]"]');
    const additionalFeesContainer = document.getElementById('additional-fees');
    
    function toggleAdditionalFees() {
        const selectedAmenity = document.querySelector('input[name="amenity"]:checked');
        if (!selectedAmenity) return; // Exit if no amenity is selected

        if (selectedAmenity.value === 'Pavilion/Main Hall') {
            additionalFeesContainer.style.display = 'block';
        } else {
            additionalFeesContainer.style.display = 'none';
            additionalFeesCheckboxes.forEach(checkbox => checkbox.checked = false);
            document.getElementById('totalAdditionalFees').textContent = '0';
        }
        calculateAdditionalFees();
    }

    function calculateAdditionalFees() {
        let total = 0;
        additionalFeesCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += 600;
            }
        });
        document.getElementById('totalAdditionalFees').textContent = total;
    }

    amenityRadios.forEach(radio => {
        radio.addEventListener('change', toggleAdditionalFees);
    });

    additionalFeesCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateAdditionalFees);
    });

    toggleAdditionalFees(); // Initialize on page load
});

</script>
</body>
</html>
