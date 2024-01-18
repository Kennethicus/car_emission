<!-- book-now.php -->
<!-- admin side -->
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduling</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="../app/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../app/assets/fullcalendar/lib/main.min.css">
    <script src="../app/assets/js/jquery-3.6.0.min.js"></script>
    <script src="../app/assets/js/bootstrap.min.js"></script>
    <script src="../app/assets/fullcalendar/lib/main.min.js"></script>
    <style>
        :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            font-family: Apple Chancery, cursive;
        }

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
        }
        table, tbody, td, tfoot, th, thead, tr {
            border-color: #ededed !important;
            border-style: solid;
            border-width: 1px !important;
        }
    </style>
</head>

<body class="bg-light">
<div id="sidebar" class="bg-dark">
    <h3 class="text-center text-white">Dashboard</h3>
    <a href="dashboard.php" class="text-white">Home</a>
    <a href="#" class="text-white">Emission Testing</a>
    <a href="book-now.php" class="text-white">Calendar</a>
    <a href="#" class="text-white">Reports</a>
    <a href="process/logout.php" class="btn btn-danger btn-block mt-4">Logout</a>
</div>
<h2 class="mb-4">Welcome, <?php echo $admin['username']; ?>!</h2>
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
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
                                    <label for="qty_persons" class="control-label">Quantity of Persons</label>
                                    <input type="number" class="form-control form-control-sm rounded-0" name="qty_persons" id="qty_persons" min="1" required>
                                </div>
                                <div class="mb-2">
                                <label for="price" class="control-label">Price</label>
                                <select class="form-control form-control-sm rounded-0" name="price" id="price" required>
                                    <!-- Remove the 'selected' attribute from all options to make it empty by default -->
                                    <option value="" disabled selected>Select Price</option>
                                    <option value="300">300php</option>
                                    <option value="400">400php</option>
                                    <option value="500">500php</option>
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
    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Schedule Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
    <div class="container-fluid">
        <dl>
            <dt class="text-muted">Title</dt>
            <dd id="title" class="fw-bold fs-4"></dd>

            <dt class="text-muted">Description</dt>
            <dd id="description" class=""></dd>

            <dt class="text-muted">Quantity of Persons</dt>
            <dd id="qty_persons" class=""></dd>

            <dt class="text-muted">Price</dt>
<dd id="price" class=""></dd>


            <dt class="text-muted">Start</dt>
            <dd id="start" class=""></dd>

            <dt class="text-muted">End</dt>
            <dd id="end" class=""></dd>
        </dl>
    </div>
</div>

                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Delete</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->


<?php 
$schedules = $connect->query("SELECT * FROM `schedule_list`");
$sched_res = [];
foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
    $row['sdate'] = date("F d, Y h:i A",strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A",strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}
?>
<?php 
if(isset($connect)) $connect->close();
?>
</body>
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="../app/assets/js/script.js"></script>

</html>