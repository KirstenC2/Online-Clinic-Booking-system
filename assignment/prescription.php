<?php 

    $clinicid = $_COOKIE['adminclinic'];
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
    function retrievePrescriptions($clinicid){
        $conn = connectDB();
        $sql = "SELECT * FROM prescription where clinicid = '$clinicid' AND status NOT IN ('Completed', 'Cancelled'); ;";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // Output data of each row in an HTML table
            echo "<table>";
            echo "<tr><th>ID</th><th>Doctor ID</th><th>Pharmacy ID</th><th>Medication</th><th>Dosage</th><th>Description</th><th>Status</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"] . "</td><td>" . $row["doctorid"] . "</td><td>" . $row["pharmacyid"] . "</td><td>" . $row["medication"] . "</td><td>" . $row["dosage"] . "</td><td>" . $row["description"] . "</td><td>" . $row["status"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No prescriptions found";
        }
    }
    function getPharmacies() {
        // Create a new MySQLi object and connect to the database
        $conn = connectDB();
        
        // Prepare a SQL statement to select all pharmacies
        $stmt = $conn->prepare("SELECT pharmacyid,name FROM pharmacy");
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Get the result set
        $result = $stmt->get_result();
        
        // Close the statement and connection
        $stmt->close();
        $conn->close();
        
        // Return the result set as an array of associative arrays
        $pharmacies = array();
        while ($row = $result->fetch_assoc()) {
            $pharmacies[] = $row;
        }
        return $pharmacies;
    }
    function getDoctorsByClinic($clinicId) {
        $conn = connectDB();
        $sql = "SELECT id,name FROM doctor WHERE clinicid = '$clinicId'";
        $result = $conn->query($sql);
        $doctors = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $doctors[$row["id"]] = $row["name"];
            }
        }
        $conn->close();
        return $doctors;
    }
    function getAppointments($clinicid) {
        $conn = connectDB();
        $sql = "SELECT appointmentID, date, time, email,patientname, doctor, status FROM appointment WHERE clinicid = '$clinicid' AND status != 'Completed' AND status != 'Cancelled'";
        $result = $conn->query($sql);
        $appointments = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $appointment = array(
                    "appointmentid" => $row["appointmentID"],
                    "date" => $row["date"],
                    "time" => $row["time"],
                    "patient_id" => $row["email"],
                    "patientname" => $row["patientname"],
                    "doctor" => $row["doctor"],
                    "status" => $row["status"]
                );
                $appointments[] = $appointment;
            }
        }
        $conn->close();
        return $appointments;
    }
    
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="adminDash.css">
	<style>





	</style>
        	

</head>
<body>

	<div class="navMenu">
		<ul>
			<li><a href="admindashboard.php">Home</a></li>
			<li><a href="prescription.php">Prescription</a></li>
			<li><a href="adminLogout.php">Logout</a></li>
		</ul>
	</div>
	<h1>Prescriptions</h1>
	<div class="mainContainer">
	
	<div class="leftSection">
		<div class="appointmentSection">
            <h1>Medicine Order</h1>
            <form action="orderPrescription.php" method="post">
            <label for="apmtid">Appointment ID:</label>
            <select id="apmtid" name="apmtid">
              <?php
                $appointments = getAppointments($clinicid);
                foreach ($appointments as $appointment) {
                  if ($appointment["status"] !== "completed" && $appointment["status"] !== "cancelled") {
                      echo '<option value="' . $appointment["appointmentid"] . '">' . " Date: ".$appointment["date"] ." Time: ".$appointment["time"] . $appointment["patientname"] . '</option>';
                  }
                }
                
              ?>
            </select><br><br>
            
            <?php
            // assume $clinicid is the clinic ID selected by the user
                $doctors = getDoctorsByClinic($clinicid);
            ?>
    
            <label for="doctorid">Prescribing Doctor ID:</label>
            <select id="doctorid" name="doctorid">
              <?php
                $doctors = getDoctorsByClinic($clinicid);
                foreach ($doctors as $id => $name) {
                  echo '<option value="' . $id . '">'.$id ." ". $name . '</option>';
                }
              ?>
            </select><br><br>
    
            
            <label for="pharmacyid">Pharmacy ID:</label>
            <select name="pharmacyid">
                <?php
                $pharmacies = getPharmacies();
                foreach ($pharmacies as $pharmacy) {
                    echo '<option value="' . $pharmacy['pharmacyid'] . '">' .$pharmacy['pharmacyid'] ." ". $pharmacy['name'] . '</option>';
                }
                ?>
            </select><br><br>
            
            <label for="medication">Medication:</label>
            <input type="text" id="medication" name="medication"><br>
            
            <label for="dosage">Dosage:</label>
            <input type="text" id="dosage" name="dosage"><br>
            
            <label for="desc">Description:</label>
            <input type="text" id="desc" name="desc"><br>
            
            <input type="submit" class="button-36" role="button" value="Order">
            </form>
    
    
        </div>
	</div>
	
    <div class="rightSection">
    <div class="appointmentSection">
    	<h1>Prescriptions Ordered</h1>
    	<?php echo retrievePrescriptions($clinicid);?>
    	<h2>Cancel Prescription</h2>
    	<form method="post" action="cancelPrescription.php">
          <label for="appointment-id">Prescription ID:</label>
          <input type="text" id="appointment-id" name="appointment_id" required>
          
          <button type="submit">Cancel Prescription</button>
        </form>
    	</div>
    </div>
    </div>