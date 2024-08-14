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
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar">

<div id="navbar-placeholder"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('navbar_vis.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-placeholder').innerHTML = data;
            });
    });

    function toggleAdditionalFees() {
        var additionalFees = document.getElementById("additional-fees");
        var pavilionRadio = document.getElementById("pavilion");
        if (pavilionRadio.checked) {
            additionalFees.style.display = "block";
        } else {
            additionalFees.style.display = "none";
        }
    }
</script>

<!-- Amenity Reservation Form -->
<div class="container mt-5 mb-5">
    <div class="header text-center mb-4">
        <h1>AMENITY RESERVATION FORM</h1>
    </div>
    <form>
        <!-- Resident Information -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label" style="font-weight: bold;">First Name:</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Juan">
            </div>
            <div class="col-md-6">
                <label for="name" class="form-label" style="font-weight: bold;">Last Name:</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Dela Cruz">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="address" class="form-label" style="font-weight: bold;">Full Address:</label>
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
                        <input class="form-check-input" type="checkbox" id="lightings_and_String_lights" name="additional_fees" value="Lightings and String Lights">
                        <label class="form-check-label" for="sound_system">Lightings and String Lights</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="mobile/audio/video" name="additional_fees" value="Mobile/Audio/Video">
                        <label class="form-check-label" for="projector">Mobile/Audio/Video</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="photo_booth/fog_machine" name="additional_fees" value="Photo Booth/Fog Machine">
                        <label class="form-check-label" for="catering">Photo Booth/Fog Machine</label>
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
                        <li><a href="#about">About</a></li>
                        <li><a href="amenities_vis.php">Amenities</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
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
</body>
</html>