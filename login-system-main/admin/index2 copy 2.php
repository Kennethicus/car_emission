<?php
require_once('db-connect.php');

if (isset($_POST['insert_month'])) {
    $month = $_POST['month'];

    // Get the total number of days in the specified month
    $lastDay = date('t', strtotime($month));

    // Define time slots
    $timeSlots = [
        '8:00 AM - 8:30 AM', '8:30 AM - 9:00 AM', '9:00 AM - 9:30 AM', '9:30 AM - 10:00 AM',
        '10:00 AM - 10:30 AM', '10:30 AM - 11:00 AM', '11:00 AM - 11:30 AM', '11:30 AM - 12:00 PM',
        '1:00 PM - 1:30 PM', '1:30 PM - 2:00 PM', '2:00 PM - 2:30 PM', '2:30 PM - 3:00 PM',
        '3:00 PM - 3:30 PM', '3:30 PM - 4:00 PM', '4:00 PM - 4:30 PM', '4:30 PM - 5:00 PM'
    ];

    // Loop through each day of the month
    for ($day = 1; $day <= $lastDay; $day++) {
        // Check if the current day is not Saturday or Sunday
        $currentDayOfWeek = date('N', strtotime($month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT)));
        if ($currentDayOfWeek >= 6) { // 6 is Saturday, 7 is Sunday
            continue; // Skip weekends
        }

        // Combine the current date with the time slots
        foreach ($timeSlots as $timeSlot) {
            // Combine the current date with the time slot
            $currentDate = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

            // Extract start and end times from the time slot
            list($startTime, $endTime) = explode(' - ', $timeSlot);

            // Convert to MySQL datetime format
            $startDatetime = date('Y-m-d H:i:s', strtotime($currentDate . ' ' . $startTime));
            $endDatetime = date('Y-m-d H:i:s', strtotime($currentDate . ' ' . $endTime));

            // Set the price values
            $price_3 = 600;
            $price_2 = 500;
            $price_1 = 300;

            // Insert into the database
            $sql = "INSERT INTO schedule_list (title, description, qty_of_person, start_datetime, end_datetime, availability, price_3, price_2, price_1) VALUES ('Vehicle Emission', 'emission', 5, '$startDatetime', '$endDatetime', 'available', $price_3, $price_2, $price_1)";
            $result = $conn->query($sql);

            if (!$result) {
                echo "Error: " . $conn->error;
            }
        }
    }
}

// Fetch scheduled time slots from the database
$sql = "SELECT start_datetime AS start, end_datetime AS end FROM schedule_list";
$result = $conn->query($sql);

$scheduledTimeSlots = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scheduledTimeSlots[] = [
            'start' => $row['start'],
            'end' => $row['end'],
            'color' => 'green', // Set the color property to green
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar Slot</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
</head>
<body>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <form method="post">
        <input type="month" name="month" required>
        <button type="submit" name="insert_month" class="btn btn-primary">Insert Month</button>
      </form>
      <!-- Display the calendar here -->
      <div id="calendar"></div>

      <!-- Display the schedule below the calendar -->
      <div id="schedule"></div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    <?php if ($result->num_rows > 0): ?>
        // If there is at least one scheduled time slot, set the entire day background to green
        var scheduledTimeSlots = <?php echo json_encode($scheduledTimeSlots); ?>;
        var highlightedDates = [];

        // Extract the date part from each scheduled time slot
        scheduledTimeSlots.forEach(function(slot) {
            var startDate = moment(slot.start).format('YYYY-MM-DD');
            highlightedDates.push(startDate);
        });

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            events: scheduledTimeSlots,
            eventRender: function(event, element) {
                // Highlight the entire day with one green color
                element.css('background-color', 'green');
                // Remove the time row
                element.find('.fc-time-grid').remove();
                // Remove the time display within the event element
                element.find('.fc-time').remove();
                // Hide the start and end dates
                element.find('.fc-title').remove();
                // Attach a click event to show more details if needed
                element.click(function() {
                    // Show the schedule for the selected day
                    displaySchedule(event.start.format('YYYY-MM-DD'));
                });
            },
            select: function(start, end) {
                // Handle the selection of time slot here
                alert('Selected ' + start.format() + ' to ' + end.format());
            },
            dayRender: function(date, cell) {
                // Check if the date is in the highlightedDates array
                var dateString = date.format('YYYY-MM-DD');
                if (highlightedDates.includes(dateString)) {
                    // Hide the date number
                    cell.find('.fc-day-number').css('visibility', 'hidden');
                    // Set the background color of the entire day to green
                    cell.css('background-color', 'green');
                }
            },
            eventAfterRender: function(event, element, view) {
                if (view.name === 'agendaWeek' || view.name === 'agendaDay') {
                    // Remove the entire row in agenda views
                    element.closest('tr').remove();
                }
            }
        });

        function displaySchedule(selectedDate) {
            // Fetch schedule for the selected date from the database
            $.ajax({
                url: 'fetch_details.php', // Replace with the actual server-side script to fetch schedule
                type: 'POST',
                data: { date: selectedDate },
                success: function(response) {
                    // Display the schedule below the calendar
                    $('#schedule').html(response);
                },
                error: function(error) {
                    console.error('Error fetching schedule:', error);
                }
            });
        }

    <?php else: ?>
        // If there are no scheduled time slots, initialize the calendar without additional customization
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            events: <?php echo json_encode($scheduledTimeSlots); ?>,
            eventRender: function(event, element) {
                element.css('background-color', 'green');
                element.find('.fc-time-grid').remove();
                element.click(function() {
                    alert('Selected ' + event.start.format('dddd'));
                });
            },
            select: function(start, end) {
                // Handle the selection of time slot here
                alert('Selected ' + start.format() + ' to ' + end.format());
            }
        });
    <?php endif; ?>
});
</script>

</body>
</html>
