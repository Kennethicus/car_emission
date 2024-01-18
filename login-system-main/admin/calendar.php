<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Pricing - Brand</title>
    <link rel="stylesheet" href="../app/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="../app/assets/css/Date-Picker-From-and-To.css">
    <link rel="stylesheet" href="../app/assets/css/Form-Select---Full-Date---Month-Day-Year.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightpick@1.3.4/css/lightpick.min.css">
    <link rel="stylesheet" href="../assets/css/Login-Form-Basic-icons.css">
    <link rel="stylesheet" href="../assets/css/untitled-1.css">
    <link rel="stylesheet" href="../assets/css/untitled.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <style>
        /* Additional styles for smaller form */
        .card-body {
            max-width: 400px; /* Adjust the maximum width of the form */
            margin: auto; /* Center the form */
        }

        .btn-lg {
            padding: 8px 16px; /* Adjust button padding */
            font-size: 16px; /* Adjust button font size */
        }

        .date-select .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
        }

        .date-select .form-group label {
            width: 30%; /* Adjust label width */
            margin-bottom: 0;
        }

        .date-select .form-group input {
            width: 65%; /* Adjust input width */
            margin-bottom: 10px; /* Add margin between fields */
        }

        #calendar {
            width: 100%;
            margin-top: 20px; /* Adjust spacing from the form */
        }
    </style>
</head>

<body class="text-center">

    <section class="py-5">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h2 class="fw-bold" style="color: var(--bs-success-text-emphasis);border-color: var(--bs-teal);">What is Emissions Testing?</h2>
                    <p class="text-muted">Testing procedures gauge your car's emissions and ability to<br> track the pollutants it releases. In particular, a test can potentially review the levels of carbon dioxide (CO2), hydrocarbons (HC), carbon monoxide (CO), oxides of nitrogen (NOx) and other emissions coming from your car.</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4" id="calendar-container"> <!-- Added an ID to the container -->

                    <!-- FullCalendar container -->
                    <div id="calendar"></div>
                </div>
                <div class="col-md-8">
                    <div class="card" id="example-1">
                        <div class="card-body shadow p-4" id="example-3"> <!-- Adjusted padding -->
                            <h2 class="text-center card-title mb-4">Set Your Schedule</h2>
                            <form class="date-select">
                                <div class="form-group mb-3">
                                    <label for="datetimePicker" class="form-label">Select Date and Time:</label>
                                    <input class="form-control form-control-sm" type="datetime-local" id="datetimePicker" required> <!-- Adjusted input size -->
                                </div>
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-label">Quantity of Persons:</label>
                                    <input class="form-control form-control-sm" type="number" id="quantity" min="1" required> <!-- Input for quantity of persons -->
                                </div>
                                <div class="mt-3 form-group mb-3">
                                    <button class="btn btn-primary btn-sm d-block w-100" type="submit">BOOK</button>
                                </div> <!-- Adjusted button size -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-dark">
        <div class="container py-4 py-lg-5">
            <!-- Footer content (if any) -->
        </div>
    </footer>
    <script src="../app/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../app/assets/js/smart-forms.min.js"></script>
    <script src="../app/assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightpick@1.3.4/lightpick.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="../app/assets/js/Date-Picker-From-and-To-datepicker.js"></script>
    <script src="../app/assets/js/bold-and-dark.js"></script>
    <script>
        // Initialize FullCalendar
        $(document).ready(function () {
            $('#calendar').fullCalendar({
                // Add your FullCalendar options here
                // For example:
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultView: 'month',
                editable: true,
                events: [
                    // Add your events here
                    // For example:
                    {
                        title: 'Event 1',
                        start: '2023-11-20T10:00:00',
                        end: '2023-11-20T12:00:00'
                    },
                    {
                        title: 'Event 2',
                        start: '2023-11-22T14:00:00',
                        end: '2023-11-22T16:00:00'
                    }
                ]
            });
        });
    </script>
</body>

</html>
