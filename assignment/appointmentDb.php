<?php
    $username = $_COOKIE['user'];
    $email = $_COOKIE['email'];
    
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
    // assuming you have already established a database connection
    
    function createAppointment($deliveryaddress,$patientName,$date, $time, $clinicid, $doctor, $status, $email, $appointmentid) {
         $conn = connectDB(); // assuming $conn is your database connection object
        
        $sql = "INSERT INTO appointment (deliveryaddress,patientname, date, time, clinicID, doctor, Status, email, appointmentID, payment)
            VALUES ('$deliveryaddress','$patientName','$date', '$time', '$clinicid', '$doctor', '$status', '$email', '$appointmentid','100');";
        
        if ($conn->query($sql) === TRUE) {
            return 1;
        } else {
            return 0;
        }
    }
    
    function generate_appointment_id() {
        // Set up database connection
        $conn = connectDB();
        
        // Generate a random appointment ID and check if it already exists in the database
        $max_attempts = 10; // maximum number of attempts to generate a unique ID
        $attempt_count = 0;
        $appointment_id = '';
        while ($attempt_count < $max_attempts) {
            $random_string = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 12); // generate a 12-character random string
            $appointment_id = "APMT" . $random_string; // prepend "APMT" to the random string
            $query = "SELECT * FROM appointment WHERE appointmentID='$appointment_id'";
            $result = $conn->query($query);
            if ($result->num_rows == 0) {
                // Appointment ID is unique, exit the loop
                break;
            }
            $attempt_count++;
        }
        // Close the database connection
        $conn->close();
        return $appointment_id;
    }
    function is_clinic_id_valid($clinic_id) {
        // Set up database connection
        $conn = connectDB();
        
        // Check if the clinic ID exists in the database
        $query = "SELECT * FROM clinic WHERE clinicid='$clinic_id'";
        $result = $conn->query($query);
        $num_rows = $result->num_rows;
        
        // Close the database connection
        $conn->close();
        
        // Return true if the clinic ID exists, false otherwise
        if ($num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<link rel="stylesheet" href="stylingcss.css">
</head>
<body>
	<h1>Appointment Status</h1>
	<?php 
	   $randomAPID = generate_appointment_id();
	   $status = "Placed";
	   $doctor = "-";
	   $clinicid = $_POST["clinicid"];
	   if(is_clinic_id_valid($clinicid)) {
	       // Create a new appointment in the database
	       createAppointment($_POST['deliveryAddress'],$_POST["patientName"],$_POST["appointmentDate"], $_POST["appointmentTime"], $clinicid, $doctor, $status, $email, $randomAPID);
	       echo "Your Appointment has been placed successfully!";
	       ?>
	       <a href = "payment.php">Paynow</a>
	       <?php 
	   } else {
	       // Display an error message if the clinic ID is not valid
	       echo "The clinic ID entered does not exist!";
	   }
	?>
	<a href="userhomepage.php">Back to Homepage</a>
</body>
</html>