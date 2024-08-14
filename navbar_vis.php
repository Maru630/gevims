<!-- navbar_vis.php -->
<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">
            <img src="./img/greenwoods_logo.png" alt="" style="width: 100px; height: auto;">
        </a>
        <div class="d-flex align-items-center">
            <div class="notification-button">
                <a href="notification.php" class="btn position-relative">
                    <span class="material-symbols-outlined">notifications</span>
                     <span class="position-absolute top-0 start-60 translate-middle badge bg-danger rounded-pill">3</span>
                </a>
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


<!-- TABLES -->
<nav class="categories navbar-expand-lg bg-green sticky-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage_vis.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#amenities" id="amenitiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Amenities
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="amenitiesDropdown">
                        <li><a class="dropdown-item" href="amenities_vis.php">Amenities offered</a></li>
                        <li><a class="dropdown-item" href="bookAmenities_vis.php">Book a reservation</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#services" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        About Us
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="gallery.php">Gallery</a></li>
                        <li><a class="dropdown-item" href="contact.php">Contact</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
