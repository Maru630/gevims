<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management</title>
    <link rel="icon" type="image/png" href="./img/greenwoods_logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #28a745; /* Brighter green background color */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .header a img {
            width: 150px;
            height: auto;
        }

        .header h3 {
            margin: 0;
            color: #555;
        }

        .back_div {
            text-align: right;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .back_div .btn {
            color: #fff;
            background-color: #dc3545;
        }

        .outer_container {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .outer_container h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .card {
            background-color: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px; /* Adjusted width for the background */
            height: 320px; /* Adjusted height for the background */
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            margin: 10px;
        }

        .card img.card_image {
            width: 100px;
            height: 100px;
            object-fit: contain; /* Ensure the image fits well inside the box */
        }

        .card .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card .card-title {
            font-size: 1.1rem;
            color: #333; /* Dark color for title */
            margin-top: 10px;
        }

        .card .card-text {
            font-size: 0.9rem;
            color: #555; /* Dark color for text */
            text-align: center;
        }

        .card .btn {
            margin-top: 10px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="header">
        <a href="homepage.php">
            <img src="img/greenwoods_logo.png" alt="logo">
        </a>
        <h3>Greenwoods Executive Village</h3>
    </div>

    <div class="back_div">
        <button class="btn btn-danger" onclick="history.back()">Back</button>
    </div>

    <div class="outer_container">
        <h2 class="text-center my-3">SERVICE REQUEST LIST</h2>
        <div class="container">
            <div id="serviceRequestContainer" class="row"></div>
            <script>
                const serviceRequests = [
                    { id: '0001', serviceDescription: 'Electrician', dateOfRequest: 'Jul 06 2024', status: 'Pending' },
                    { id: '0002', serviceDescription: 'Plumbers', dateOfRequest: 'Jul 07 2024', status: 'Pending' },
                    { id: '0003', serviceDescription: 'Masons', dateOfRequest: 'Jul 07 2024', status: 'Pending' },
                    { id: '0004', serviceDescription: 'Hot Work Specialist', dateOfRequest: 'Jul 07 2024', status: 'Pending' },
                    { id: '0005', serviceDescription: 'Steel Work Specialist', dateOfRequest: 'Jul 07 2024', status: 'Pending' },
                    { id: '0006', serviceDescription: 'Maintenance and Repairs', dateOfRequest: 'Jul 07 2024', status: 'Pending' },
                    { id: '0007', serviceDescription: 'Others', dateOfRequest: 'Jul 07 2024', status: 'Declined' },
                    { id: '0008', serviceDescription: 'Maintenance and Repairs', dateOfRequest: 'Jul 08 2024', status: 'Approved' }
                ];

                function getStatusText(request) {
                    if (request.status === "Approved") {
                        return `<button id="btn_status" class="btn btn-success" disabled>Approved</button>`;
                    } else if (request.status === "Pending") {
                        return `<button id="btn_status" class="btn btn-warning" disabled>Pending</button>`;
                    } else if (request.status === "Declined") {
                        return `<button id="btn_status" class="btn btn-danger" disabled>Declined</button>`;
                    } else {
                        return "";
                    }
                }

                function processButton(request) {
                    if (request.status === "Pending") {
                        return `<button class="btn btn-primary ">Show Details</button>`;
                    } else {
                        return "";
                    }
                }

                function getStatusImage(request) {
                    const imageMap = {
                        "Electrician": "img/Electrician.png",
                        "Plumbers": "img/Plumbers.png",
                        "Masons": "img/Masons.png",
                        "Hot Work Specialist": "img/Hot_Work_Specialist.png",
                        "Steel Work Specialist": "img/Steel_Work_Specialist.png",
                        "Maintenance and Repairs": "img/Maintenance_&_Repair.png",
                        "Others": "img/Others.png"
                    };
                    return `<img class="card_image" src="${imageMap[request.serviceDescription]}" alt="Service Image">`;
                }

                function generateServiceRequestCard(request) {
                    return `
                        <div class="col">
                            <div class="card">
                                ${getStatusImage(request)}
                                <div class="card-body">
                                    <h5 class="card-title"><b>Service Request #${request.id}</b></h5>
                                    <p class="card-text"><b>Service:</b> ${request.serviceDescription}</p>
                                    <small class="text-body-secondary"><b>Date of Request:</b> ${request.dateOfRequest}</small>
                                </div>
                                <div class="card-footer">
                                    ${getStatusText(request)}
                                    ${processButton(request)}
                                </div>
                            </div>
                        </div>`;
                }

                function renderServiceRequests() {
                    const container = document.getElementById('serviceRequestContainer');
                    if (container) {
                        container.innerHTML = '';
                        serviceRequests.forEach(request => {
                            const cardHTML = generateServiceRequestCard(request);
                            container.innerHTML += cardHTML;
                        });
                    }
                }

                renderServiceRequests();
            </script>
        </div>
    </div>
</body>

</html>
