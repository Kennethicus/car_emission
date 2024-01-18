<?php 
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}



if(!isset($_GET['id'])){
    echo "<script> alert('Undefined Schedule ID.'); location.replace('book-now2.php') </script>";
    $connect->close();
    exit;
}

$delete = $connect->query("DELETE FROM `schedule_list` where id = '{$_GET['id']}'");
if($delete){
    echo "<script> alert('Event has deleted successfully.'); location.replace('book-now2.php') </script>";
}else{
    echo "<pre>";
    echo "An Error occured.<br>";
    echo "Error: ".$connect->error."<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
}
$connect->close();

?>