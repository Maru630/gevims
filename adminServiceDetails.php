<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/adminServiceDetails.css">
</head>

<body>
    <div class="header">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <a href="homepage.php">
            <img src="img/greenwoods_logo.png" alt="logo" style="width: 150px; height: auto;">
        </a>
        <h3>Greenwoods Executive Village</h3>
    </div>

    <div class="back_div">
        <button class="btn btn-danger">Back</button>
    </div>
    <div class="outer_container">
        <h2 class="text-center my-3">SERVICE REQUEST DETAILS</h2>
        <div class="container mt-5 mb-5">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">First Name:</label>
                    <input type="text" class="form-control" value="JUAN" readonly>

                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" value="DELA CRUZ" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="address" class="form-label">Greenwoods Address:</label>
                    <textarea class="form-control" readonly>Tulip St. Phase 2, Greenwoods Executive Village, San Andres, Cainta, Rizal 1900</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number:</label>
                    <input type="text" class="form-control" value="09123456789" readonly>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" value="juandelacruz@gmail.com" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="date_of_request" class="form-label">Date of Request:</label>
                    <input type="text" class="form-control" value="Jul 07, 2024" readonly>
                </div>
                <div class="col-md-4">
                    <label for="preferred_date" class="form-label">Preferred Date for Service:</label>
                    <input type="text" class="form-control" value="Jul 08, 2024" readonly>
                </div>
                <div class="col-md-4">
                    <label for="preferred_time" class="form-label">Preferred Time for Service:</label>
                    <input type="time" class="form-control" value="13:30"  readonly>
                </div>
            </div>
            <div class="row mb-3 ">
                <div class="col-md-12 mt-3">
                    <label class="form-label">Type of Service Requested:</label>
                    <input type="text" class="form-control" value="ELECTRICIAN" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="description" class="form-label">Description of the Issue/Service Needed:</label>
                    <textarea class="form-control" readonly>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Doloremque optio nobis suscipit blanditiis voluptatum veritatis officiis, iste debitis libero? Quis laboriosam similique autem. Debitis, quia sint rerum aspernatur non eius voluptatum alias hic aliquam! Facere placeat nostrum nam laboriosam saepe!</textarea>
                 </div>
            </div>
            <div class="row mb-3">
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="attachments" class="form-label" style="font-weight: bold;">Photos or Attachments:</label>
                       <img class="attached_img" src="https://media.licdn.com/dms/image/C5612AQEdMnfm394J9g/article-cover_image-shrink_600_2000/0/1602932914291?e=2147483647&v=beta&t=wLUN-B2bg2NurzQCpNfxqa5kF3qjXuYp2RNLEHoQZhU">
                </div>
            </div>

            <div class="footer">
            <button class="btn btn-success">APPROVE</button>
            <button class="btn btn-danger">DECLINE</button>
            </div>
        </div>
</body>

</html>