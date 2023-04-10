<?php
session_start();
$appointment = $_SESSION['id'];

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
function updateAppointmentPayment($appointment_id) {
    // Connect to the database
    $conn = connectDB();
    
    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    // Update the payment status for the appointment with the given ID
    $sql = "UPDATE prescription SET amount='paid' WHERE id='$appointment_id';";
    
    if ($conn->query($sql) === TRUE) {
        echo "Payment updated successfully";
    } else {
        echo "Error updating payment: " . $conn->error;
    }
    
    // Close the database connection
    $conn->close();
}
foreach ($appointment as $appointment_id) {
    updateAppointmentPayment($appointment_id);
}
header("Location: userhomepage.php");
?>