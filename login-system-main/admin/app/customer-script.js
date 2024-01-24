// customer_script.js

document.addEventListener('DOMContentLoaded', function () {
    var customerCalendar = new FullCalendar.Calendar(document.getElementById('customer-calendar'), {
        headerToolbar: {
            left: 'prev,next today',
            right: 'dayGridMonth,dayGridWeek,timeGridDay,list'
        },
        initialView: 'dayGridMonth',
        selectable: true,
        select: function (info) {
            // Send selected time slot information to the server for validation
            checkAvailability(info.start, info.end);
        },
        events: {
            url: 'get_customer_schedule.php', // Fetch schedule events dynamically
            method: 'GET'
        },
        // Configure other options as needed
    });

    customerCalendar.render();

    function checkAvailability(start, end) {
        // AJAX request to check availability on the server
        $.ajax({
            url: 'check-availability.php', // Create this file to handle availability check
            type: 'POST',
            data: {
                start_datetime: start.toISOString(),
                end_datetime: end.toISOString()
            },
            success: function (response) {
                if (response.available) {
                    // Allow the customer to proceed with the selection
                    alert('Selected time: ' + start.toISOString() + ' to ' + end.toISOString() + ' is available.');
                } else {
                    // Display a message to the customer that the time slot is not available
                    alert('This time slot is not available.');
                    customerCalendar.unselect(); // Deselect the current selection
                }
            },
            error: function () {
                // Handle the error if the AJAX request fails
                alert('Error checking availability. Please try again.');
                customerCalendar.unselect(); // Deselect the current selection
            }
        });
    }
});
