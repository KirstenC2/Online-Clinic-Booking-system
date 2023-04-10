<?php
$appointment_id=$_POST['appointment_id'];
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
function cancelAppointment($appointment_id) {
    // Prepare and execute the SQL statement to cancel the appointment
    $conn = connectDB();
    
    // Prepare and execute SQL statement to cancel appointment
    $sql = "UPDATE appointment SET Status = 'Cancelled' WHERE appointmentid = '$appointment_id';";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        header("Location:userhomepage.php");
    } else {
        echo "Error cancelling appointment: " . mysqli_error($conn);
    }
    
    // Close database connection
    mysqli_close($conn);
    
}

cancelAppointment($appointment_id);

?>