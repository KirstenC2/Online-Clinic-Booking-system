<?php
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
$conn=connectDB();
$prescriptionId=$_POST['appointment_id'];
$sql = "UPDATE prescription SET status='Cancelled' WHERE id='$prescriptionId';";

if ($conn->query($sql) === TRUE) {
    echo "Prescription cancelled successfully";
    header("Location:prescription.php");
} else {
    echo "Error cancelling prescription: " . $conn->error;
}

$conn->close();
