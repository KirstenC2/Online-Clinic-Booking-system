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
    function getClinicName($clinicid) {
        // Connect to the database
        $conn = connectDB();
        
        
        // Prepare the SQL query
        $sql = "SELECT * FROM clinic WHERE clinicid = '$clinicid'";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Fetch the clinic name
        $row = mysqli_fetch_assoc($result);
        $clinicname = $row['name'];
        $cliniccontact = $row['contact'];
        $address = $row['address'];
        
        // Close the database connection
        mysqli_close($conn);
        
        // Return the clinic name
        return array($clinicname,$cliniccontact,$address);
    }

    function getClinicIdByEmail($email) {
        // Connect to the database
        $conn = connectDB();
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Sanitize the email input to prevent SQL injection attacks
        $email = mysqli_real_escape_string($conn, $email);
        
        // Prepare the SQL query
        $sql = "SELECT clinicid FROM adminaccount WHERE email = '$email'";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Fetch the clinicid
        $row = mysqli_fetch_assoc($result);
        $clinicid = $row['clinicid'];
        
        // Close the database connection
        mysqli_close($conn);
        
        // Return the clinicid
        return $clinicid;
    }
    function displayAppointments($clinicid) {
        // Connect to the database
        $conn = connectDB();
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Prepare the SQL query
        $sql = "SELECT * FROM appointment where clinicID = '$clinicid' AND Status NOT IN ('Cancelled','Completed') ;";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Start the HTML table
        echo "<table>";
        echo "<tr><th>Appointment ID</th><th>Clinic ID</th><th>Client Name</th><th>Doctor Name</th><th>Status</th><th>E-mail</th><th>Date</th><th>Time</th></tr>";
        
        // Loop through the result set and display the data in the HTML table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['appointmentID'] . "</td>";
            echo "<td>" . $row['clinicID'] . "</td>";
            echo "<td>" . $row['patientname'] . "</td>";
            echo "<td>" . $row['doctor'] . "</td>";
            echo "<td>" . $row['Status'] . "</td>";
            echo "<td><a href='mailto:" . $row["email"] . "?&subject=EzClinic Appointment'>" . $row["email"] . "</a></td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['time'] . "</td>";
            echo "</tr>";
            
        }
        
        // Close the HTML table
        echo "</table>";
        
        // Close the database connection
        mysqli_close($conn);
    }
    function checkDocStatus($status){
        if($status == "1"){
            echo "In";
        }
        else{
           echo "Off";
        }
    }
    function displayDoctors($clinicid) {
        // Connect to the database
        $conn = connectDB();
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Prepare the SQL query
        $sql = "SELECT * FROM doctor where clinicid = '$clinicid';";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Start the HTML table
        echo "<table id='doctortable'>";
        echo "<tr><th>Doctor ID</th><th>Clinic ID</th><th>Doctor Name</th><th>Doctor Email</th><th>Status</th></tr>";
        
        // Loop through the result set and display the data in the HTML table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['clinicid'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td><a href='mailto:" . $row["email"] . "?&subject=EzClinic Appointment'>" . $row["email"] . "</a></td>";
            echo "<td>";
            if ($row['status'] == 1) {
                echo "On call";
            } else {
                echo "Off";
            }
            echo "</td>";
            echo "</tr>";
        }
        
        // Close the HTML table
        echo "</table>";
        
        // Close the database connection
        mysqli_close($conn);
    }
    list($name,$contact,$address) = getClinicName(getClinicIdByEmail($_COOKIE['email']));
    function generateDoctorSelectField($clinicid) {
        // connect to the database
        $conn = connectDB();
        
        // check if the connection is successful
        if (!$conn) {
            die('Failed to connect to the database.');
        }
        
        // retrieve the list of doctors from the database
        $sql = "SELECT id, name FROM doctor where clinicid = '$clinicid';";
        $result = mysqli_query($conn, $sql);
        
        // generate the doctor selection field
        echo "<label for='docid'>Doctor:</label>";
        echo "<select id='docid' name='docid'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . $row['id']." ".$row['name'] . "</option>";
        }
        echo "</select>";
        
        // close the database connection
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="adminDash.css">

        	
    <style>
        select {
      font-size: 16px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-shadow: none;
      background-color: #fff;
      color: #333;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
    }

    /* style the select arrow */
    select::-ms-expand {
      display: none;
    }
    select::after {
      content: '\25BC';
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      padding: 8px;
      background-color: #eee;
      pointer-events: none;
    }
        
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
	<div class="section">
    	<h1>Clinic Name:<?php echo $name ?></h1>
    	<p>Contact: <?php echo $contact ?></p>
    	<p>Address: <?php echo $address ?></p>
	</div>
	<div class="appointmentSection">
		<h1>Appointments</h1>
		<?php 

		displayAppointments($_COOKIE['adminclinic']);?>
		<br><br>
		<form action="updateApmt.php" method="post">
		<h1>Assignation Doctor</h1>
			<label for="apmtid">Appointment ID:</label>
			<input type="text" id="apmtid" name="apmtid">
			
			<?php generateDoctorSelectField($_COOKIE['adminclinic']);?>
			
			<input type="submit" class="button-36" role="button" value="assign">
		</form>
		
		<br><br>
		<form action="cancelApmt.php" method="post">
		<h1>Cancel Appointment</h1>
			<label for="apmtid">Appointment ID:</label>
			<input type="text" id="apmtid" name="apmtid">
			
			<input type="submit" class="button-36" role="button" value="Cancel">
		</form>
	</div>
	<div class="appointmentSection">
		<h1>Doctor on Board</h1>

		<?php displayDoctors($_COOKIE['adminclinic'])?>
		<form action="updateDocStatus.php" method="post">
		<h1>Doctor Status</h1>
			<?php generateDoctorSelectField($_COOKIE['adminclinic']); ?>
			
			 <label for="status">Status:</label>
              <select id="status" name="status">
                <option value="0">Off</option>
                <option value="1">On call</option>
              </select>
			
			<input type="submit" class="button-36" role="button" value="assign">
		</form>
	</div>
	
	
	
</body>
</html>