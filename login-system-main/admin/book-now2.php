<!-- book-now2.php -->
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
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - Brand</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-4-Calendar-No-Custom-Code.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-Calendar.css">
    <link rel="stylesheet" href="assets/css/Continue-Button.css">

   
    <link rel="stylesheet" href="../app/assets/fullcalendar/lib/main.min.css">
    <script src="../app/assets/js/jquery-3.6.0.min.js"></script>
    <script src="../app/assets/js/bootstrap.min.js"></script>
    <script src="../app/assets/fullcalendar/lib/main.min.js"></script>

    <style>
   
        table, tbody, td, tfoot, th, thead, tr {
            border-color: #ededed !important;
            border-style: solid;
            border-width: 1px !important;
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
               
       <div class="container py-3" id="page-container">
        
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
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

            <dt class="text-muted">Slots</dt>
            <dd id="qty_persons" class=""></dd>

            <dt class="text-muted">MV III</dt>
<dd id="price3" class=""></dd>

            <dt class="text-muted">MV II</dt>
<dd id="price2" class=""></dd>

<dt class="text-muted">MV I</dt>
<dd id="price1" class=""></dd>


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

            </div>

            </div>
       <?php include 'partials/footer.php' ?>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>


    
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
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
  
</html>