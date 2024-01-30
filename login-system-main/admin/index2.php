<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch admin details based on the session information (you may customize this part)
$username = $_SESSION['admin'];
$query = "SELECT * FROM smurf_admin WHERE username = '$username'";
$result = $connect->query($query);

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    // Handle the case where admin details are not found
    echo "Admin details not found!";
    exit();
}

// Fetch scheduled time slots from the database
$sql = "SELECT start_datetime AS start, end_datetime AS end FROM schedule_list";
$result = $connect->query($sql);

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
    $resultCheck = $connect->query($sqlCheck);

    if ($resultCheck) {
        $rowCheck = $resultCheck->fetch_assoc();
        if ($rowCheck['count'] > 0) {
            echo "Time slots already exist for the selected day. No new entries were added.";
            exit(); // Exit to prevent further execution
        }
    } else {
        echo "Error checking existing time slots: " . $connect->error;
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
        $result = $connect->query($sql);

        if (!$result) {
            echo "Error: " . $connect->error;
            exit(); // Exit if there is an error
        }
    }

    // Redirect to the same page after successful insertion
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
   
  
    <link rel="stylesheet" href="assets/css/Continue-Button.css">

   
  <!-- <style>
    .fc-toolbar.fc-header-toolbar .fc-left,
    .fc-toolbar.fc-header-toolbar .fc-right {
        display: none;
    }
</style> -->
<style>
    /* Custom styling for the month input */
    .month-input {
        width: 120px; /* Adjust the width as needed */
    }
</style>
<style>
    /* Adjust the position of the entire datepicker dropdown */
    .datepicker-dropdown {
        top: 230px !important;
        left: 970px !important;
        z-index: 10;
        display: block;
    }
</style>
<style>
    .month-input {
        display: none;
    }
    .sched {
            background-color: green;
        }
</style>

</head>
<body id="page-top">
<div id="wrapper">
<?php include 'partials/nav.php'?>
<div class="d-flex flex-column" id="content-wrapper">
<div id="content">
<?php include 'partials/nav-2.php' ?>
<div class="container mt-5">
    <div class="row">
    <div class="col-md-8">

    <div class="row">
    <div class="col-md-6">
        <!-- Insert Day Form -->
        <div class="mt-3">
            <form method="post" id="insertDeleteDayForm">
                <input type="date" name="selected_date" required>
                <button type="submit" name="action" value="insert_day" class="btn btn-primary">Insert Day</button>
                <button type="submit" name="action" value="delete_day" class="btn btn-danger">Delete Day</button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <!-- Insert/Delete Month Form -->
        <div class="mt-3 mb-3">
            <form method="post" id="insertDeleteForm">
                <button type="submit" id="insertMonthBtn" class="btn btn-primary">Insert Month</button>
                <button type="submit" id="deleteMonthBtn" class="btn btn-danger">Delete Month</button>
                <!-- Add a custom class to style the month input separately -->
                <input type="month" name="month" id="monthInput" class="form-control month-input" required>
            </form>
        </div>
    </div>
</div>

    <!-- Display the calendar here -->
    <div class="row">
        <div class="">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Display the schedule below the calendar -->
    <div class="row">
        <div class="col-md-12">
            <div id="schedule"></div>
        </div>
    </div>
    </div>
    <div class="col-md-4">
                <div class="card rounded-0 shadow">
                    <div class="sched card-header bg-gradient  text-light">
                        <h5 class="card-title">Schedule Form</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="text" name="id" value="" readonly>
                              
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

<!-- Add this to your HTML file where you want the modal to appear -->
<div id="deleteConfirmationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title">Delete Month Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage">Are you sure you want to delete all time slots for the selected month?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Add this to your HTML file where you want the modal to appear -->
<div id="deleteDayConfirmationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Day Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="deleteDayConfirmationMessage">Are you sure you want to delete all time slots for the selected day?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteDayBtn">Yes, Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div></div>
<?php include 'partials/footer.php' ?>
</div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
</div>
<script src="../app/assets/js/script.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
<script>
$(document).ready(function () {
    // Function to handle form submission via AJAX
    function submitForm(selectedDate, action) {
        var formData = { selected_date: selectedDate, action: action };

        $.ajax({
            url: 'insert_or_delete_day3.php', // Path to the separate PHP file
            type: 'POST',
            data: formData,
            success: function (response) {
                // Handle the response, you can display a message or perform other actions
                alert(response);

                // Check if modal flag is present in the response
                var modalFlag = response.split('|modal:')[1];
                if (modalFlag === '1') {
                    // Show the modal
                    $('#deleteDayConfirmationModal').modal('show');
                } else {
                    // Reload the page or update the calendar as needed
                    location.reload();
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    // Event listener for form submission
    $('#insertDeleteDayForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        var selectedDate = $(this).find('input[name="selected_date"]').val();
        var action = $(this).find('button[type="submit"]:focus').val();

        submitForm(selectedDate, action);
    });

    // Event listener for the "Confirm Delete" button in the modal
    $('#confirmDeleteDayBtn').on('click', function () {
        // Perform the actual delete action
        var selectedDate = $('#insertDeleteDayForm input[name="selected_date"]').val();
        submitForm(selectedDate, 'delete_day_confirm');
    });

});

</script>



<script>
$(document).ready(function () {
    // Function to handle form submission via AJAX
    function submitForm(action) {
        var formData = $('#insertDeleteForm').serializeArray();
        formData.push({ name: 'action', value: action });

        $.ajax({
            url: 'insert_or_delete_month3.php', // Replace with the actual server-side script
            type: 'POST',
            data: formData,
            success: function (response) {
                // Split the response to get the modal flag and message
                var parts = response.split('|modal:');
                var modalFlag = parts[1];
                var message = parts[0];

                // Handle the response
                if (message) {
                    alert(message); // Display alert only if there is a message
                }

                // Check if the modal flag is set to '1' (true) and display the modal
                if (modalFlag === '1') {
                    $('#confirmationMessage').text(message); // Set the response message in the modal
                    $('#deleteConfirmationModal').modal('show');
                } else {
                    // No modal, reload the page
                    location.reload();
                }

                // Re-initialize the calendar after inserting or deleting a month
                initCalendar();
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    // Event listener for form submission
    $('#insertDeleteForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        var action = ($(event.submitter).attr('id') === 'insertMonthBtn') ? 'insert_month' : 'delete_month';
        submitForm(action);
    });

    // Event listener for the "Yes, Delete" button in the modal
    $(document).on('click', '#confirmDeleteBtn', function () {
        // Trigger the form submission with the 'delete_confirm' action
        submitForm('delete_confirm');
        // Close the modal after clicking "Yes, Delete"
        $('#deleteConfirmationModal').modal('hide');
    });
});

</script>





<script>
       function displayScheduleDay(selectedDate) {
            // Fetch schedule for the selected date from the database
            $.ajax({
                url: 'fetch_details3.php', // Replace with the actual server-side script to fetch schedule
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
    left: ' today',
    center: 'title',
    right: 'customHeader'
},
customButtons: {
    customHeader: {
        text: 'Select Month',
        click: function () {
            var currentDate = $('#calendar').fullCalendar('getDate');
            var selectedDate = currentDate.format('YYYY-MM');

            // Initialize datepicker
            $('#monthInput').datepicker({
                format: 'yyyy-mm',
                startView: 'months',
                minViewMode: 'months',
                autoclose: true,
                container: 'body' // Set the container to 'body' to append the datepicker to the body
                
            });

            // Set the initial value of datepicker
            $('#monthInput').datepicker('setDate', selectedDate);

            // Show the datepicker
            $('#monthInput').datepicker('show');

            // Handle date selection
            $('#monthInput').on('changeDate', function (e) {
                var newDate = moment(e.date);
                $('#calendar').fullCalendar('gotoDate', newDate);
                $('#monthInput').datepicker('hide');
            });
        }
    }
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
            // select: function(start, end) {
            //     // Handle the selection of time slot here
            //     alert('Selected ' + start.format() + ' to ' + end.format());
            // },
            select: function (selectedDate) {
    displaySchedule(selectedDate.format('YYYY-MM-DD'));
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
                url: 'fetch_details3.php', // Replace with the actual server-side script to fetch schedule
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
    right: 'customHeader'
},
customButtons: {
    customHeader: {
        text: 'Select Month',
        click: function () {
            var currentDate = $('#calendar').fullCalendar('getDate');
            var selectedDate = currentDate.format('YYYY-MM');

            // Initialize datepicker
            $('#monthInput').datepicker({
                format: 'yyyy-mm',
                startView: 'months',
                minViewMode: 'months',
                autoclose: true,
                container: 'body',
      // Add the custom class to the datepicker container
      beforeShow: function (input, inst) {
        inst.dpDiv.addClass('datepicker-container');
      }, // Set the container to 'body' to append the datepicker to the body
            });

            // Set the initial value of datepicker
            $('#monthInput').datepicker('setDate', selectedDate);

            // Show the datepicker
            $('#monthInput').datepicker('show');

            // Handle date selection
            $('#monthInput').on('changeDate', function (e) {
                var newDate = moment(e.date);
                $('#calendar').fullCalendar('gotoDate', newDate);
                $('#monthInput').datepicker('hide');
            });
        }
    }
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
<script>
function showDetails(scheduleId) {
    // Fetch detailed information for the selected schedule via AJAX
    $.ajax({
        url: 'fetch_schedule_list3.php', // Replace with the actual server-side script to fetch details
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
    // Fetch detailed information for the selected schedule via AJAX
    $.ajax({
        url: 'fetch_schedule_list3.php', // Replace with the actual server-side script to fetch details
        type: 'POST',
        data: { id: scheduleId },
        success: function(response) {
            // Parse the JSON response
            var scheduleDetails = JSON.parse(response);

            // Debug: Log the scheduleDetails to check the values
            console.log('Fetched schedule details:', scheduleDetails);
            // Debug: Log the scheduleDetails after conversion
            console.log('Schedule details after conversion:', scheduleDetails);

            // Populate the schedule form with the details
            $('#schedule-form input[name="id"]').val(scheduleId);
            $('#idTitle').val(scheduleDetails.id);
            $('#title').val(scheduleDetails.title);
            $('#description').val(scheduleDetails.description);
            $('#qty_persons').val(scheduleDetails.qty_of_person);
            $('#price3').val(scheduleDetails.price_3);
            $('#price2').val(scheduleDetails.price_2);
            $('#price1').val(scheduleDetails.price_1);
            $('#start_datetime').val(scheduleDetails.start_datetime);
            $('#end_datetime').val(scheduleDetails.end_datetime);

            // Close the modal
            $('#scheduleModal').modal('hide');
        },
        error: function(error) {
            console.error('Error fetching schedule details:', error);
        }
    });
}





// Function for handling Delete button click
function deleteSchedule(scheduleId) {
    // Check if scheduleId is also an event_id in car_emission
    $.ajax({
        url: 'check_event_time_id3.php',
        type: 'POST',
        data: { scheduleId: scheduleId },
        dataType: 'json', // Specify the expected data type
        success: function(response) {
            console.log('Response:', response);

            if (response.status === 'true') {
                // If scheduleId is also an event_id in car_emission, ask for confirmation
                if (confirm('This schedule has associated car emissions. Do you want to delete it and its associated emissions?')) {
                    // Implement the logic for deletion here
                    alert('Delete schedule with ID ' + scheduleId + ' and its associated emissions.');
                    performDeletion(scheduleId); // Call the function to perform deletion
                    
                }
            } else {
                // If scheduleId is not an event_id in car_emission, proceed with deletion
                if (confirm('Do you want to delete this schedule?')) {
                    // Implement the logic for deletion here
                    alert('Delete schedule with ID ' + scheduleId);
                    performDeletion(scheduleId); // Call the function to perform deletion
                }
            }
        },
        error: function(error) {
            console.error('Error checking scheduleId:', error);
        }
    });
}

function performDeletion(scheduleId) {
    // Implement your deletion logic here
    $.ajax({
        url: 'delete_event_time_schedule3.php',
        type: 'POST',
        data: { scheduleId: scheduleId },
        dataType: 'json', // Specify the expected data type
        success: function(response) {
            // Log the response
            console.log('Deletion response:', response);

            // Handle the response after deletion, if needed
            if (response.status === 'success') {
                // Deletion was successful
                console.log('Deletion successful');

                // Pass the start_datetime to displaySchedule
                displayScheduleDay(response.formatted_start_datetime);
            } else {
                // Error in deletion
                console.error('Error deleting schedule:', response.message);
            }
        },
        error: function(error) {
            console.error('Error deleting schedule:', error);
        }
    });
}







// Function for handling Update button click
function updateSchedule(scheduleId) {
    // You can implement the logic for updating here
    alert('Update schedule with ID ' + scheduleId);
}
</script>
</body>
</html>
