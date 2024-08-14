<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/homepage.css">
    <title>Create Event - Admin Panel</title>
</head>

<body>
    <div class="container mt-5 mb-5">
        <form id="eventForm" action="processCreateEvent.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="header text-center mb-4">
                <a href="homepage.php">
                    <img src="img/greenwoods_logo.png" alt="logo" class="img-fluid" style="width: 150px; height: auto;">
                </a>
                <h3>Greenwoods Executive Village - Admin Panel</h3>
            </div>

            <div class="text-start mb-4">
                <button class="btn btn-danger" onclick="window.location.href='adminEvents.php';">Back</button>
            </div>

            <h2 class="text-center my-3">CREATE AN EVENT</h2>

            <div class="mb-3">
                <label class="form-label" for="eventName">Event Name:</label>
                <input type="text" class="form-control" id="eventName" name="eventName" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-2">
                    <label class="form-label" for="what">What:</label>
                    <input type="text" class="form-control" id="what" name="what" required>
                </div>

                <div class="col-2">
                    <label class="form-label" for="where">Where:</label>
                    <input type="text" class="form-control" id="where" name="where" required>
                </div>

                <div class="col-2">
                    <label class="form-label" for="when">When:</label>
                    <input type="date" class="form-control" id="when" name="when" required>
                </div>

                <div class="col-3">
                    <label class="form-label" for="timeStart">Start Time:</label>
                    <div class="d-flex align-items-center">
                        <input type="number" class="form-control me-1" id="startHour" name="startHour" min="1" max="12" placeholder="HH" required>
                        <span>:</span>
                        <input type="number" class="form-control ms-1" id="startMinute" name="startMinute" min="0" max="59" placeholder="MM" required>
                        <select class="form-select ms-2" id="startPeriod" name="startPeriod" required>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <label class="form-label" for="timeEnd">End Time:</label>
                    <div class="d-flex align-items-center">
                        <input type="number" class="form-control me-1" id="endHour" name="endHour" min="1" max="12" placeholder="HH" required>
                        <span>:</span>
                        <input type="number" class="form-control ms-1" id="endMinute" name="endMinute" min="0" max="59" placeholder="MM" required>
                        <select class="form-select ms-2" id="endPeriod" name="endPeriod" required>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="description">Description:</label>
                <textarea id="description" class="form-control" name="description" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="publishDate">Publish Date</label>
                <input type="date" class="form-control" id="publishDate" name="publishDate" value="<?php echo date('Y-m-d'); ?>">
                <small class="form-text text-muted">Leave this field empty to use today’s date.</small>
            </div>

            <div class="mb-4">
                <label class="form-label" for="attachment">Attachment (Optional)</label>
                <input type="file" class="form-control" id="attachment" name="attachment">
            </div>

            <div class="text-center">
                <input type="submit" class="btn btn-success" value="Publish Event">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function validateForm() {
            const eventName = document.getElementById('eventName');
            const what = document.getElementById('what');
            const where = document.getElementById('where');
            const when = document.getElementById('when');
            const startHour = document.getElementById('startHour');
            const startMinute = document.getElementById('startMinute');
            const endHour = document.getElementById('endHour');
            const endMinute = document.getElementById('endMinute');
            const description = document.getElementById('description');

            if (!eventName.value.trim()) {
                alert("Please fill out the Event Name field.");
                eventName.focus();
                return false;
            }

            if (!what.value.trim()) {
                alert("Please fill out the What field.");
                what.focus();
                return false;
            }

            if (!where.value.trim()) {
                alert("Please fill out the Where field.");
                where.focus();
                return false;
            }

            if (!when.value.trim()) {
                alert("Please fill out the When field.");
                when.focus();
                return false;
            }

            if (!startHour.value.trim() || !startMinute.value.trim()) {
                alert("Please fill out the Start Time field.");
                startHour.focus();
                return false;
            }

            if (!endHour.value.trim() || !endMinute.value.trim()) {
                alert("Please fill out the End Time field.");
                endHour.focus();
                return false;
            }

            if (!description.value.trim()) {
                alert("Please fill out the Description field.");
                description.focus();
                return false;
            }

            // If all fields are filled, allow form submission
            return true;
        }
    </script>
</body>

</html>
