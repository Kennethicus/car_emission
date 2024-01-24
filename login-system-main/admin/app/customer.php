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
                <!-- Change from FullCalendar to a date picker -->
                <input type="date" class="form-control" id="datePicker" placeholder="date" />
            </div>
        </div>
    </div>

    <div class="container py-5" id="events-container">
        <!-- Event details will be dynamically filled by JavaScript -->
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
                                eventDetails += '<p><strong>Time:</strong> ' + formatDate(response[i].start) + ' - ' + formatDate(response[i].end) + '</p>';
                                eventDetails += '</div>';
                                eventDetails += '<div class="col-md-4">';
                                eventDetails += '<p><strong>Slot:</strong> ' + response[i].qty_of_person + '</p>';
                                eventDetails += '</div>';
                                eventDetails += '<div class="col-md-4 text-end">';
                                eventDetails += '<button class="btn btn-primary" onclick="bookNow(' + response[i].id + ')">Book Now</button>';
                                eventDetails += '</div>';
                                eventDetails += '</div>';
                                eventDetails += '<hr>'; // Add a horizontal line between events

                                eventsContainer.append(eventDetails);
                            }
                        } else {
                            eventsContainer.html('<p>No events on selected date</p>');
                        }
                    },
                    error: function () {
                        alert('Error fetching schedule data.');
                    }
                });
            });

            // Function to handle the "Book Now" button click
            window.bookNow = function (eventId) {
                // Implement the logic to handle booking for the selected event
                alert('Booking now for event with ID ' + eventId);
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
