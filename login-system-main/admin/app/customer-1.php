<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Scheduling</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fullcalendar/lib/main.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Create a new stylesheet for your custom styles -->
</head>

<body class="bg-light">
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="customer-calendar" class="rounded"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="schedule-details-modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="schedule-details-content">
                <!-- Content will be dynamically filled by JavaScript -->
            </div>
        </div>
    </div>
</div>


    <!-- Reordered script tags to include Bootstrap and Popper.js first -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../fullcalendar/lib/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightpick@1.3.4/lightpick.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var customerEvents = <?php
                require_once('../db-connect.php');

                $customerEvents = [];
                $result = $conn->query("SELECT * FROM `schedule_list`");

                while ($row = $result->fetch_assoc()) {
                    $customerEvents[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                        'qty_of_person' => $row['qty_of_person'],
                        'start' => $row['start_datetime'],
                        'end' => $row['end_datetime'],
                        'availability' => $row['availability'], // Added availability information
                    ];
                }

                $conn->close();

                echo json_encode($customerEvents);
            ?>;

            var customerCalendar = new FullCalendar.Calendar(document.getElementById('customer-calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                themeSystem: 'bootstrap',
                events: <?php echo json_encode($customerEvents);?>,
                selectable: true,
                height: 'auto',
                eventClick: function (info) {
                    var modalContent = '<h5>' + info.event.title + '</h5>';
                    modalContent += '<p><strong>Quantity of Persons:</strong> ' + info.event.extendedProps.qty_of_person + '</p>';
                    modalContent += '<p><strong>Start:</strong> ' + info.event.start.toLocaleString() + '</p>';
                    modalContent += '<p><strong>End:</strong> ' + info.event.end.toLocaleString() + '</p>';
                    modalContent += '<p><strong>Availability:</strong> ' + info.event.extendedProps.availability + '</p>';

                    document.getElementById('schedule-details-content').innerHTML = modalContent;
                    $('#schedule-details-modal').modal('show');
                },
                select: function (info) {
                    var selectedDate = info.start.toISOString().split('T')[0];
                    var nextDate = info.end.toISOString().split('T')[0];

                    var eventsOnSelectedDate = customerCalendar.getEvents().filter(function (event) {
                        var eventDate = event.start.toISOString().split('T')[0];
                        return eventDate === selectedDate || eventDate === nextDate;
                    });

                    if (eventsOnSelectedDate.length === 0) {
                        alert('No events on selected date or the next day!');
                    }
                },
                eventColor: 'green'
            });

            customerCalendar.render();
        });
    </script>
</body>

</html>
