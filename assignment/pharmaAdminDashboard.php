<?php
$pharmaid = getPharmacyIdByEmail($_COOKIE['email']);

setcookie('pharmaid',$pharmaid);


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
    
    function getPharmacyName($pharmacyid) {
        // Connect to the database
        $conn = connectDB();
        
        
        // Prepare the SQL query
        $sql = "SELECT * FROM pharmacy WHERE pharmacyid = '$pharmacyid'";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Fetch the clinic name
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $contact = $row['contact'];
        $address = $row['address'];
        
        // Close the database connection
        mysqli_close($conn);
        
        // Return the clinic name
        return array($name,$contact,$address);
    }
    function getPharmacyIdByEmail($email) {
        // Connect to the database
        $conn = connectDB();
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Sanitize the email input to prevent SQL injection attacks
        $email = mysqli_real_escape_string($conn, $email);
        
        // Prepare the SQL query
        $sql = "SELECT pharmacyid FROM pharmaadmin WHERE email = '$email'";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Fetch the clinicid
        $row = mysqli_fetch_assoc($result);
        $pharmacyid = $row['pharmacyid'];
        
        // Close the database connection
        mysqli_close($conn);
        
        // Return the clinicid
        return $pharmacyid;
    }
    list($name,$contact,$address) = getPharmacyName(getPharmacyIdByEmail($_COOKIE['email']));
    
    

    function getPrescriptionsByPharmacyId($pharmacyId) {
        // Create a new MySQLi object and connect to the database
        $conn = connectDB();
        // Prepare a SQL statement to select prescriptions based on the pharmacy ID
        $stmt = $conn->prepare("SELECT amount,id, doctorid, pharmacyid, medication, dosage, description, status FROM prescription WHERE pharmacyid = ? AND status NOT IN ('completed', 'cancelled');;");
        $stmt->bind_param("i", $pharmacyId);
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Get the result set
        $result = $stmt->get_result();
        
        // Close the statement and connection
        $stmt->close();
        $conn->close();
        
        // Return the result set as an array of associative arrays
        $prescriptions = array();
        while ($row = $result->fetch_assoc()) {
            $prescriptions[] = $row;
        }
        return $prescriptions;
    }
    function displayPrescriptionsInTable($prescriptions) {
        // Generate an HTML table to display the prescription data
        echo "<table>";
        echo "<tr><th>Amount</th><th>ID</th><th>Doctor ID</th><th>Pharmacy ID</th><th>Medication</th><th>Dosage</th><th>Description</th><th>Status</th></tr>";
        foreach ($prescriptions as $prescription) {
            echo "<tr>";
            echo "<td>" . $prescription["amount"] . "</td>";
            echo "<td>" . $prescription["id"] . "</td>";
            echo "<td>" . $prescription["doctorid"] . "</td>";
            echo "<td>" . $prescription["pharmacyid"] . "</td>";
            echo "<td>" . $prescription["medication"] . "</td>";
            echo "<td>" . $prescription["dosage"] . "</td>";
            echo "<td>" . $prescription["description"] . "</td>";
            echo "<td>" . $prescription["status"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    function displayPresInSelection($pharmacy_id){
        $conn = connectDB();
        
        // Get the prescriptions with the given status and pharmacy ID
        // replace with actual pharmacy ID
        $stmt = $conn->prepare("SELECT id FROM prescription WHERE pharmacyid = ? AND status NOT IN ('Cancelled', 'Completed')");
        $stmt->bind_param("s", $pharmacy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Display the prescription IDs in the dropdown
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['id'] . '</option>';
        }
        
        // Close the database connection
        $stmt->close();
        $conn->close();
    }
    
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="adminDash.css">

</head>
<body>
	<div class="navMenu">
		<ul>
			<li><a href="pharmaAdminDashboard.php">Home</a></li>
			<li><a href="prescriptionHistory.php">History Order</a></li>
			<li><a href="invoice.php">Invoice</a></li>
			<li><a href="pharmaLogout.php">Logout</a></li>
		</ul>
	</div>
	<div class="section">
    	<h1>Pharmacy Name:<?php echo $name ?></h1>
    	<p>Contact: <?php echo $contact ?></p>
    	<p>Address: <?php echo $address ?></p>
    	<p>ID: <?php echo $pharmaid;?></p>
	</div>

	<div class="appointmentSection">
		<h1>Pending Prescription</h1>
		<p>Check for Medicine parcel to be send.</p>
		
		<?php 
		  $prescriptions = getPrescriptionsByPharmacyId($pharmaid);
		  displayPrescriptionsInTable($prescriptions)?>
		  <br><br>
		  <form action ="updatePrescription.php" method="post">
            <label for="prescription_id">Prescription ID:</label>
            <select id="prescription_id" name="prescription_id">
            	<?php 
            	   displayPresInSelection($pharmaid);
            	
            	?>
            	</select>
            <br>
            <label for="new_status">New Status:</label>
            <select id="new_status" name="new_status">
                <option value="Preparing">Preparing</option>
                <option value="Sent Out">Sent Out</option>
                <option value="Cancelled">Cancelled</option>
                <option value="Completed">Completed</option>
            </select>
            <br>
            <input type="submit" class="button-36" role="button" name="submit" value="Update">
        </form>
  
        
	</div>

</body>
</html>