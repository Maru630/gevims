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

<!-- GALLERY -->
<section id="gallery" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-down" data-aos-delay="150">
                <div class="section-title">
                    <h1 class="display-4 fw-semibold">Gallery</h1>
                    <div class="line"></div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-down" data-aos-delay="150">
                <div class="gallery-item image-zoom">
                    <div class="image-zoom-wrapper">
                        <img src="./img/basketball (4).jpg" alt="">
                    </div>
                    <a href="./img/basketball (4).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
                <div class="gallery-item image-zoom mt-4">
                    <div class="image-zoom-wrapper">
                        <img src="./img/pavillion (2).jpg" alt="">
                    </div>
                    <a href="./img/pavillion (2).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
                <div class="gallery-item image-zoom mt-4">
                    <div class="image-zoom-wrapper">
                        <img src="./img/playground (2).jpg" alt="">
                    </div>
                    <a href="./img/playground (3).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-down" data-aos-delay="250">
                <div class="gallery-item image-zoom">
                    <div class="image-zoom-wrapper">
                        <img src="./img/mainhall.jpg" alt="">
                    </div>
                    <a href="./img/mainhall.jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
                <div class="gallery-item image-zoom mt-4">
                    <div class="image-zoom-wrapper">
                        <img src="./img/pavillion.jpg" alt="">
                    </div>
                    <a href="./img/pavillion.jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
                <div class="gallery-item image-zoom mt-4">
                    <div class="image-zoom-wrapper">
                        <img src="./img/swim (3).jpg" alt="">
                    </div>
                    <a href="./img/swim (3).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-down" data-aos-delay="350">
                <div class="gallery-item image-zoom">
                    <div class="image-zoom-wrapper">
                        <img src="./img/mainhall (2).jpg" alt="">
                    </div>
                    <a href="./img/mainhall (2).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
                <div class="gallery-item image-zoom mt-4">
                    <div class="image-zoom-wrapper">
                        <img src="./img/pavillion (3).jpg" alt="">
                    </div>
                    <a href="./img/pavillion (3).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
                <div class="gallery-item image-zoom mt-4">
                    <div class="image-zoom-wrapper">
                        <img src="./img/soccer (2).jpg" alt="">
                    </div>
                    <a href="./img/soccer (2).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-down" data-aos-delay="450">
                <div class="gallery-item image-zoom">
                    <div class="image-zoom-wrapper">
                        <img src="./img/picnic.jpg" alt="">
                    </div>
                    <a href="./img/picnic.jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-down" data-aos-delay="550">
                <div class="gallery-item image-zoom">
                    <div class="image-zoom-wrapper">
                        <img src="./img/picnic (2).jpg" alt="">
                    </div>
                    <a href="./img/picnic (2).jpg" data-fancybox="gallery" class="iconbox"><span class="material-symbols-outlined">search</span></a>
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