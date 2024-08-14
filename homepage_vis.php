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
</script>

    <!-- HERO -->
    <section id="hero" class="min-vh-100 d-flex align-items-center text-center">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 data-aos="fade-left" class="text-uppercase text-white fw-semibold display-4">Greenwoods Executive Village</h1>
                    <h5 class="text-white mt-3 mb-4" data-aos="fade-right">Pasig City, Taytay, and Cainta, Rizal</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="50">
                    <div class="section-title">
                        <h1 class="display-4 fw-semibold">About us</h1>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6" data-aos="fade-down" data-aos-delay="50">
                    <img src="./img/homebg.jpg" alt="">
                </div>
                <div data-aos="fade-down" data-aos-delay="150" class="col-lg-5">
                    <h1>Greenwoods Executive Village</h1>
                    <p class="mt-3 mb-4">Greenwoods Executive Village is a premier residential community offering modern amenities and natural beauty for a tranquil lifestyle. With landscaped gardens, state-of-the-art facilities, and a secure environment, we foster a strong sense of community, making Greenwoods not just a place to live, but a place to thrive.</p>
                </div>
            </div>
        </div>
    </section>

<!-- COUNTER -->
    <section id="counter" class="section-padding">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="150">
                    <h1 class="text-white display-4">7,500+</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Approximate Number of Houses</h6>
                </div>
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="250">
                    <h1 class="text-white display-4">15</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Fitness Facilities</h6>
                </div>
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="350">
                    <h1 class="text-white display-4">30K+</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Residents</h6>
                </div>
                <div class="col-lg-3 col-sm-6" data-aos="fade-down" data-aos-delay="450">
                    <h1 class="text-white display-4">3</h1>
                    <h6 class="text-uppercase mb-0 text-white mt-3">Number of Gates</h6>
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
</body>

</html>