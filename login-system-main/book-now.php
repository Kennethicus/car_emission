

<?php
//admin side
// book-now.php
// Start the session
// homepage.php
// Start the session
session_start();
include("connect/connection.php");
// Check if the email session variable is set
if (isset($_SESSION['email'])) {
    // Get the email from the session
    $email = $_SESSION['email'];

    // Query the database to fetch user details
    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = $connect->query($sql);

    // Check if the query was successful
    if ($result) {
        // Fetch the user details
        $user = $result->fetch_assoc();

        $userName = $user['first_name'];
        // Echo the email, first_name, and last_name
      
    } else {
        // Handle the error, e.g., display an error message
        echo 'Error fetching user details';
    }
} else {
    // If the email session variable is not set, redirect to the login page
    header('Location: index.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - Greenfrog</title>
    <link rel="stylesheet" href="assets2/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="assets2/css/aos.min.css">
    <link rel="stylesheet" href="assets2/css/animate.min.css">
    <link rel="stylesheet" href="assets2/css/NavBar-with-pictures.css">
</head>
<body class="bg-light">
    <?php include("partials/nav.php"); ?>

    <div class="container py-5" id="page-container">
    <div class="row">
        <div class="col-md-9 mx-auto text-center">
            <input type="date" class="form-control" id="datePicker" placeholder="date" />
        </div>
    </div>
</div>


    <div class="container py-5" id="events-container">
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets2/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets2/js/aos.min.js"></script>
    <script src="assets2/js/bs-init.js"></script>
    <script src="assets2/js/bold-and-bright.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
    var datePicker = document.getElementById('datePicker');

    // Set the min attribute to the current date
    datePicker.min = new Date().toISOString().split('T')[0];

    datePicker.addEventListener('change', function () {
        var selectedDate = datePicker.value;

        // Make an AJAX request to get schedule data for the selected date
        $.ajax({
            url: 'get_customer_schedule.php',
            type: 'GET',
            data: { date: selectedDate },
            dataType: 'json',
            success: function (response) {
                // Sort events by start time
                response.sort(function (a, b) {
                    return new Date(a.start) - new Date(b.start);
                });

                // Update event details with the retrieved data
                var eventsContainer = $('#events-container');
                eventsContainer.empty();

                if (response.length > 0) {
                    for (var i = 0; i < response.length; i++) {
                        var eventDetails = '<div class="row mb-3">';
                        eventDetails += '<div class="col-md-4">';
                        eventDetails += '<p><strong>TIME:</strong> ' + formatDate(response[i].start) + ' - ' + formatDate(response[i].end) + '</p>';
                        eventDetails += '</div>';
                        eventDetails += '<div class="col-md-4">';
                        eventDetails += '<p><strong>SLOT:</strong> ' + response[i].qty_of_person + '</p>';
                        eventDetails += '<p><strong>PRICE:</strong> ' + "₱" + response[i].price_1 + '</p>';
                        eventDetails += '<p><strong>STATUS:</strong> ' + response[i].availability + '</p>';
                        eventDetails += '</div>';
                        eventDetails += '<div class="col-md-4 text-end">';
                        

                
                    // Check if reserve_count is equal to qty_of_person
if (response[i].reserve_count === response[i].qty_of_person) {
    eventDetails += '<button class="btn btn-secondary" disabled>Not Available</button>';
} else {
    eventDetails += '<button class="btn btn-primary" onclick="bookNow(' + response[i].id + ')">Book Now</button>';
}
console.log('reserve_count:', response[i].reserve_count);
console.log('qty_of_person:', response[i].qty_of_person);
              

                        eventDetails += '</div>';
                        eventDetails += '</div>';
                        eventDetails += '<hr>'; // Add a horizontal line between events

                        eventsContainer.append(eventDetails);
                    }
                } else {
                    eventsContainer.html('<p>No events on selected date</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status, error);
                alert('Error fetching schedule data. Check the console for details.');
            }
        });
    });

    // Function to handle the "Book Now" button click
    window.bookNow = function (eventId) {
        // Redirect to the booking form page with the event ID as a parameter
        window.location.href = 'booking_form.php?eventId=' + eventId;
    };

    // Function to format date and time
    function formatDate(dateTimeString) {
        var options = { hour: 'numeric', minute: 'numeric', hour12: true };
        var formattedDate = new Date(dateTimeString).toLocaleTimeString('en-US', options);
        return formattedDate;
    }
});

    </script>
<script>
    // Add the 'active' class to the 'Home' link when on home.php
    document.addEventListener('DOMContentLoaded', function () {
        var currentPage = window.location.pathname;
        var homeLink = document.querySelector('.navbar-nav .nav-item:first-child');

        if (currentPage.includes('home.php')) {
            homeLink.classList.add('active');
        }
    });
</script>

</body>
</html>