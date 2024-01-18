<!-- details.php  -->
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



$detailsQuery = "SELECT `user_id`, `first_name`, `middle_name`, `last_name` FROM login";
$detailsResult = $connect->query($detailsQuery);

$loginDetails = array();

if ($detailsResult->num_rows > 0) {
    // Fetch the details and convert them to an array
    while ($row = $detailsResult->fetch_assoc()) {
        $loginDetails[] = $row;
    }
}

// Close the database connection
$connect->close();
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Green Frawg | Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-4-Calendar-No-Custom-Code.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-Calendar.css">
    <link rel="stylesheet" href="assets/css/Continue-Button.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="assets/design/reservation.css">
   <!-- Include Select2 CSS -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/js/select2.min.js"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include 'partials/nav-2.php' ?>
                <!-- Display the details content here -->
                <div class="container mt-3">
                    <div class="row">
                  <div class="col-12 col-md-8 col-lg-9 mx-auto">
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
            <h6 class="text-primary fw-bold m-0"><span style="color: rgb(244, 248, 244);">MOTOR VEHICLE INFORMATION</span></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <!-- Add your table headers here if needed -->
                                            </thead>
                                            <tbody>
                                            <tr>
                                            <!-- <td  class="text-end">App Date<input type="text" style="margin-left: 5px;" ></td> -->
                                            <td class="text-end">
    <label for="userNameInput">Enter or Select a user:</label>
    <input type="text" id="userNameInput" name="userNameInput" list="userSuggestions" style="width: 200px;">
    <datalist id="userSuggestions">  <option value="None">None</option>
        <?php foreach ($loginDetails as $loginEntry): ?>
          
            <option value="<?php echo $loginEntry['first_name'] . ' ' . $loginEntry['middle_name'] . ' ' . $loginEntry['last_name']; ?>">
        <?php endforeach; ?>
        
    </datalist>
</td>



                                                    <td class="text-end" style="width: 200px;">Organization<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                               <tr>
                                                    <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                    <td class="text-end">Vehicle Owner<input type="text" style="margin-left: 5px;" readonly ></td>
                                                </tr> 
                                            <tr>
                                                    <td class="text-end" style="width: 200px;">Plate No.<input type="text" style="margin-left: 5px;" ></td>
                                                    <td class="text-end" style="width: 200px;">Address<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input type="text" style="margin-left: 8px;" ></td>


                                                    <td class="text-end">Engine<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;">Vehicle OR No.<input type="text" style="margin-left: 5px;" ></td>
                                                    <td style="text-align: right;">Chassis<input type="text" style="margin-left: 5px;" ></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">First Registration Date<input type="text" style="margin-left: 5px;"></td>
                                                    <td class="text-end">Make<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Year Model<input type="text" style="margin-left: 5px;" ></td>
                                                    <td class="text-end">Series<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel Type<input type="text" style="margin-left: 5px;" ></td>
                                                    <td class="text-end">Color<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Purpose<input type="text" style="margin-left: 5px;" ></td>
                                                    <td class="text-end">Gross Weight<input type="text" style="margin-left: 8px;" ></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type<input type="text" style="margin-left: 5px;" ></td>
                                                    <td class="text-end">Net Capacity<input type="text" style="margin-left: 8px;"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Region<input type="text" style="margin-left: 5px;"></td>
                                                    <td class="text-end">
    <!-- Input for uploading a picture -->
    <label for="carPictureInput" class="btn btn-primary">
        Upload Picture
        <input type="file" id="carPictureInput" class="d-none" accept="image/*" onchange="displaySelectedPicture(this)">
    </label>

    <!-- Button to display the selected picture -->
    <button type="button" class="btn btn-success" onclick="displaySelectedPicture()" id="selectedPictureFilename">Display</button>



    <!-- Modal for displaying the picture -->
    <div class="modal fade" id="carPictureModal" tabindex="-1" aria-labelledby="carPictureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carPictureModalLabel">Car Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Display the picture here -->
                    <img id="carPictureDisplay" class="img-fluid" alt="Car Picture">
                </div>
            </div>
        </div>
    </div>

    
</td>

                                                </tr>
                                                
                                                
                                                <tr>
                                                    <td class="text-end">MV File No.<input type="text" style="margin-left: 5px;" ></td>
                                                  
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Classification<input type="text" style="margin-left: 5px;" ></td>
                                                     </tr>
                                                <tr>

                                                <td class="text-end">Amount (PHP)<input type="text" style="margin-left: 5px;" ></td>
                                                  </tr>
                                               
                                               
                                                <!-- Add more rows based on your structure -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">

                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">
                        <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">TEST DETAIL</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody> <!-- Wrap the table content in tbody for better structure -->
                <tr>
                        <th><span style="font-weight: normal !important;">Amount(PHP)</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"></td>
                    </tr> 
                <tr>
                        <th><span style="font-weight: normal !important;">Mode of Payment</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"></td>
                    </tr>  
                <tr>
                        <th><span style="font-weight: normal !important;">Payment Status</span></th>
                    </tr>
                    <tr>
                        <td class="text-center" style="color: ;font-size: 23px;"></td>
                    </tr>
                    <tr>
                        <th><span style="font-weight: normal !important;">Book Status</span></th>
                    </tr>
                    <tr>
                        <td class="text-center" style="color: <?php
                            // Set color based on payment status
                            switch ($details['status']) {
                                case 'booked':
                                    echo 'orange';
                                    break;
                                case 'canceled':
                                    echo 'red';
                                    break;
                                    case 'doned':
                                        echo 'green';
                                        break;
                                default:
                                    echo 'black'; // Default color if none of the above matches
                            }
                        ?>;font-size: 23px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>




                            <div class="card" style="height: 160px; margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">


    <button class="btn btn-primary m-2" onclick="editFunction()">Edit</button>
    
    <button class="btn btn-danger m-2" onclick="cancelBookFunction()">Cancel Book</button>


</div>


</div>

<!-- Back button outside the card, positioned lower and larger with increased margin-top -->
<div class="text-end mt-5">
    <button class="btn btn-secondary btn-lg" onclick="goBack()">Back</button>
</div>


      
<script>
    // JavaScript function to navigate back to reservation.php
    function goBack() {
        window.location.href = 'reservation.php';
    }
</script>
</div>
                </div>

            </div>
           
        </div>
        <?php include 'partials/footer.php'?>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>


    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
<!-- Add this script at the end of your HTML, after including jQuery and Bootstrap JS -->

<script>
        // Function to display the selected picture filename
        function displaySelectedPicture() {
            const input = document.getElementById('carPictureInput');
            const selectedFilename = input.files[0] ? input.files[0].name : 'No file selected';
            document.getElementById('selectedPictureFilename').innerText = selectedFilename;

            // Optionally, you can also display the picture in the modal
            if (input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('carPictureDisplay').src = e.target.result;
                    $('#carPictureModal').modal('show'); // Show the modal
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

<script>
    // JavaScript function to handle the "Test" button click
    function testFunction() {
        var reserveId = <?php echo $reserve_id; ?>;
        var ticketId = <?php echo $ticketId; ?>;
        
        // Make an AJAX request to the PHP script for inserting test results
        $.ajax({
            url: 'insert_test_result.php', // Replace with the actual path to your PHP script
            method: 'POST',
            data: {
                reserveId: reserveId,
                ticketId: ticketId
            },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            },
            error: function(error) {
                // Handle errors if needed
                console.error(error);
            }
        });
    }
</script>


</body>

</html>