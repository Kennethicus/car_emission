<?php
//admin side
// book-now.php
// Start the session
session_start();
include("partials/navbar.php");
// Check if the email session variable is set
if (isset($_SESSION['email'])) {
    // Echo a welcome message
    echo 'Welcome, ' . $_SESSION['email'];
} else {
    // If the email session variable is not set, redirect to the login page
    header('Location: index.php');
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <input type="date" class="form-control" id="datePicker" placeholder="date" />
            </div>
        </div>
    </div>

    <div class="container py-5" id="events-container">
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
</body>
</html>