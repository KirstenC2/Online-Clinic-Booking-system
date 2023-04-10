<?php
$doctorid = $_POST['docid'];
$status = $_POST['status'];
function connectDB() {
    $servername = "localhost";
    $username = "root";
    $password = "admin1";
    $conn=null;
    $conn = new mysqli($servername,$username,$password,"ezclinic");
    if($conn->error){
        echo "failed";
    }
    return $conn;
}

function updateDoctorStatus($status,$docid){
    $conn = connectDB();
    $sql = "UPDATE doctor SET status='$status' WHERE id='$docid'";
    
    if (mysqli_query($conn, $sql)) {
        echo header("Location:adminDashboard.php");
    } else {
        echo 'Failed to update doctor status: ' . mysqli_error($conn);
    }
    
    // close the database connection
    mysqli_close($conn);
}
updateDoctorStatus($status, $doctorid);
?>
