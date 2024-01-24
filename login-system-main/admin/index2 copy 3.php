<?php
require_once('db-connect.php');

// Handle Delete Month button
if (isset($_POST['delete_month'])) {
    $monthToDelete = $_POST['month'];

    // Extract year and month from the selected date
    $yearToDelete = date('Y', strtotime($monthToDelete));
    $monthToDelete = date('m', strtotime($monthToDelete));

    // Delete all time slots for the specified month and year
    $sqlDelete = "DELETE FROM schedule_list WHERE YEAR(start_datetime) = $yearToDelete AND MONTH(start_datetime) = $monthToDelete";
    $resultDelete = $conn->query($sqlDelete);

    if ($resultDelete) {
        echo "All time slots for the selected month have been deleted.";
    } else {
        echo "Error deleting time slots: " . $conn->error;
    }
}



if (isset($_POST['insert_month'])) {
    $month = $_POST['month'];
  // Get the first and last day of the specified month
  $firstDayOfMonth = date('Y-m-01', strtotime($month));
  $lastDayOfMonth = date('Y-m-t', strtotime($month));

  // Check if time slots already exist for the specified month
  $sqlCheck = "SELECT COUNT(*) as count FROM schedule_list WHERE start_datetime BETWEEN '$firstDayOfMonth 00:00:00' AND '$lastDayOfMonth 23:59:59'";
  $resultCheck = $conn->query($sqlCheck);

  if ($resultCheck) {
      $rowCheck = $resultCheck->fetch_assoc();
      if ($rowCheck['count'] > 0) {
          echo "Time slots already exist for the selected month. No new entries were added.";
          exit(); // Exit to prevent further execution
      }
  } else {
      echo "Error checking existing time slots: " . $conn->error;
      exit(); // Exit to prevent further execution
  }
    
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





// Handle Insert Day button
if (isset($_POST['insert_day'])) {
    $selectedDate = $_POST['selected_date'];

    // Check if time slots already exist for the selected day
    $sqlCheck = "SELECT COUNT(*) as count FROM schedule_list WHERE DATE(start_datetime) = '$selectedDate'";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck) {
        $rowCheck = $resultCheck->fetch_assoc();
        if ($rowCheck['count'] > 0) {
            echo "Time slots already exist for the selected day. No new entries were added.";
            exit(); // Exit to prevent further execution
        }
    } else {
        echo "Error checking existing time slots: " . $conn->error;
        exit(); // Exit to prevent further execution
    }

    // Define time slots
    $timeSlots = [
        '8:00 AM - 8:30 AM', '8:30 AM - 9:00 AM', '9:00 AM - 9:30 AM', '9:30 AM - 10:00 AM',
        '10:00 AM - 10:30 AM', '10:30 AM - 11:00 AM', '11:00 AM - 11:30 AM', '11:30 AM - 12:00 PM',
        '1:00 PM - 1:30 PM', '1:30 PM - 2:00 PM', '2:00 PM - 2:30 PM', '2:30 PM - 3:00 PM',
        '3:00 PM - 3:30 PM', '3:30 PM - 4:00 PM', '4:00 PM - 4:30 PM', '4:30 PM - 5:00 PM'
    ];

    // Loop through each time slot
    foreach ($timeSlots as $timeSlot) {
        // Extract start and end times from the time slot
        list($startTime, $endTime) = explode(' - ', $timeSlot);

        // Convert to MySQL datetime format
        $startDatetime = date('Y-m-d H:i:s', strtotime($selectedDate . ' ' . $startTime));
        $endDatetime = date('Y-m-d H:i:s', strtotime($selectedDate . ' ' . $endTime));

        // Set the price values
        $price_3 = 600;
        $price_2 = 500;
        $price_1 = 300;

        // Insert into the database
        $sql = "INSERT INTO schedule_list (title, description, qty_of_person, start_datetime, end_datetime, availability, price_3, price_2, price_1) VALUES ('Vehicle Emission', 'emission', 5, '$startDatetime', '$endDatetime', 'available', $price_3, $price_2, $price_1)";
        $result = $conn->query($sql);

        if (!$result) {
            echo "Error: " . $conn->error;
            exit(); // Exit if there is an error
        }
    }

    // Redirect to the same page after successful insertion
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}









// Handle Delete Day button
if (isset($_POST['delete_day'])) {
    $selectedDateForDelete = $_POST['selected_date_for_delete'];

    // Delete all time slots for the selected day
    $sqlDelete = "DELETE FROM schedule_list WHERE DATE(start_datetime) = '$selectedDateForDelete'";
    $resultDelete = $conn->query($sqlDelete);

    if ($resultDelete) {
        echo "All time slots for the selected day have been deleted.";
    } else {
        echo "Error deleting time slots: " . $conn->error;
    }

    // Redirect to the same page after successful deletion
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
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

<div class="container mt-3 ml-5 mr-5">
<div class="container mt-3 ml-5 mr-5">

<div class="row">
  <div class="col-lg-5">
    <form method="post">
    <input type="month" name="month" required>
    <button type="submit" name="insert_month" class="btn btn-primary">Insert Month</button>
    <button type="submit" name="delete_month" class="btn btn-danger">Delete Month</button>
</form>
  </div>
  <div class="col-lg-5">
<form class="container mt-3" method="post">
    <input type="date" name="selected_date" required>
    <button type="submit" name="insert_day" class="btn btn-primary">Insert Day</button>
</form>


<form method="post" class="container mt-3">
    <input type="date" name="selected_date_for_delete" required>
    <button type="submit" name="delete_day" class="btn btn-danger">Delete Day</button>
</form>
  </div>
</div>
<br>


<div class="row">
    
  <div class="col-12 col-md-8 col-lg-8">
   
 
      
      <!-- Display the calendar here -->
      <div id="calendar"></div>

      <!-- Display the schedule below the calendar -->
      <div id="schedule"></div>
  
  </div>

  <div class="col-lg-4 mt-5">
  <div class="">
                <div class="card rounded-0 shadow">
                    <div class="sched card-header bg-gradient  text-light">
                        <h5 class="card-title">Schedule Form</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="mb-2">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                                </div>
                                <div class="mb-2">
                                    <label for="description" class="control-label">Description</label>
                                    <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="qty_persons" class="control-label">Slots</label>
                                    <input type="number" class="form-control form-control-sm rounded-0" name="qty_persons" id="qty_persons" min="1" required>
                                </div>
                                <div class="mb-2">
    <label for="price3" class="control-label">MV III</label>
    <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="
Shuttle Bus
Tourist Bus
Truck Bus
Trucks
Trailer"></i>
    <select class="form-control form-control-sm rounded-0" name="price3" id="price3" required>
        <!-- Remove the 'selected' attribute from all options to make it empty by default -->
        <option value="" disabled selected>Select Price</option>
        <option value="600.00">600php</option>
        <option value="700.00">700php</option>
        <option value="800.00">800php</option>
    </select>
</div>

<div class="mb-2">
    <label for="price2" class="control-label">
        MV II 
        <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="
Car
Sports Utility Vehicle
Utility Vehicle
School Bus
Rebuilt
Mobil Clinic"></i>
    </label>
    <select class="form-control form-control-sm rounded-0" name="price2" id="price2" required>
        <!-- Remove the 'selected' attribute from all options to make it empty by default -->
        <option value="" disabled selected>Select Price</option>
        <option value="400.00">400php</option>
        <option value="500.00">500php</option>
        <option value="600.00">600php</option>
    </select>
</div>

<div class="mb-2">
    <label for="price1" class="control-label">MV I</label>
    <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" 
    title="
Tricycle
Mopeds (0-49 cc)
Non-conventional MC (Car)
Motorcycle w/ sidecar
Motorcycle w/o sidecar"></i>
    <select class="form-control form-control-sm rounded-0" name="price1" id="price1" required>
        <!-- Remove the 'selected' attribute from all options to make it empty by default -->
        <option value="" disabled selected>Select Price</option>
        <option value="300.00">300php</option>
        <option value="400.00">400php</option>
        <option value="500.00">500php</option>
    </select>
</div>


                                <div class="mb-2">
                                    <label for="start_datetime" class="control-label">Start</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                                </div>
                                <div class="mb-2">
                                    <label for="end_datetime" class="control-label">End</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Save</button>
                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
  </div>      
  
  </div>
</div>






<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleModalLabel">Schedule Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="scheduleModalBody">
        <!-- Content will be dynamically added here -->
      </div>
    </div>
  </div>
</div>


<script>
function showDetails(scheduleId) {
    // Fetch detailed information for the selected schedule via AJAX
    $.ajax({
        url: 'fetch_schedule_list.php', // Replace with the actual server-side script to fetch details
        type: 'POST',
        data: { id: scheduleId },
        success: function(response) {
            // Parse the JSON response
            var scheduleDetails = JSON.parse(response);

            // Build the modal content
            var modalContent = '<p><strong>Title:</strong> ' + scheduleDetails.title + '</p>';
            modalContent += '<p><strong>Description:</strong> ' + scheduleDetails.description + '</p>';
            modalContent += '<p><strong>Quantity of Persons:</strong> ' + scheduleDetails.qty_of_person + '</p>';
            modalContent += '<p><strong>Reserved Count:</strong> ' + scheduleDetails.reserve_count + '</p>';
            modalContent += '<p><strong>Price for 3 Persons:</strong> $' + scheduleDetails.price_3 + '</p>';
            modalContent += '<p><strong>Price for 2 Persons:</strong> $' + scheduleDetails.price_2 + '</p>';
            modalContent += '<p><strong>Price for 1 Person:</strong> $' + scheduleDetails.price_1 + '</p>';
            modalContent += '<p><strong>Start Datetime:</strong> ' + scheduleDetails.start_datetime + '</p>';
            modalContent += '<p><strong>End Datetime:</strong> ' + scheduleDetails.end_datetime + '</p>';
            modalContent += '<p><strong>Availability:</strong> ' + scheduleDetails.availability + '</p>';

            // Set the modal body content
            $('#scheduleModalBody').html(modalContent);

            // Clear existing modal footer content
            $('#scheduleModal').find('.modal-footer').remove();

            // Build the modal footer content
            var modalFooter = '<div class="modal-footer">';
            modalFooter += '<button type="button" class="btn btn-primary" onclick="editSchedule(' + scheduleId + ')">Edit</button>';
            modalFooter += '<button type="button" class="btn btn-danger" onclick="deleteSchedule(' + scheduleId + ')">Delete</button>';
            modalFooter += '<button type="button" class="btn btn-success" onclick="updateSchedule(' + scheduleId + ')">Update</button>';
            modalFooter += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
            modalFooter += '</div>';

            // Append the new modal footer content
            $('#scheduleModal').find('.modal-content').append('<div class="modal-footer">' + modalFooter + '</div>');

            // Show the modal
            $('#scheduleModal').modal('show');
        },
        error: function(error) {
            console.error('Error fetching schedule details:', error);
        }
    });
}


// Function for handling Edit button click
function editSchedule(scheduleId) {
    // You can implement the logic for editing here
    alert('Edit schedule with ID ' + scheduleId);
}

// Function for handling Delete button click
function deleteSchedule(scheduleId) {
    // You can implement the logic for deletion here
    alert('Delete schedule with ID ' + scheduleId);
}

// Function for handling Update button click
function updateSchedule(scheduleId) {
    // You can implement the logic for updating here
    alert('Update schedule with ID ' + scheduleId);
}
</script>

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
