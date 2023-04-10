<?php
$pharmacyid = $_COOKIE['pharmaid'];
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
// Retrieve the appointment ID and payment amount from the form data
$appointment_id = $_POST['appointmentID'];
$payment_amount = $_POST['payment-amount'];
echo $appointment_id."and".$payment_amount;
// Update the prescription table with the new payment amount
$query = "UPDATE prescription SET amount = '$payment_amount' WHERE appointmentID = '$appointment_id'";
$conn = connectDB();
$result = mysqli_query($conn, $query);

if ($result) {
    // If the update was successful, redirect to a success page
    header("Location: pharmaAdminDashboard.php");
} else {
    // If the update failed, redirect to an error page
    echo "error";
}
?>
