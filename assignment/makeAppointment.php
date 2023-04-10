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
    function getClinicIds() {
        // Create a new MySQLi object and connect to the database
        $conn = connectDB();
        // Prepare a SQL statement to select clinic ids from the clinic table
        
        $sql = "SELECT clinicid FROM clinic;";
        // Execute the SQL statement
        $result = mysqli_query($conn, $sql);

        $conn->close();
        // Return the result set as an array of clinic ids
        $clinicIds = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $clinicIds[] = $row['clinicid'];
        }
        return $clinicIds;
    }
    
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Make an appointment</title>
<link rel="stylesheet" href="loginUser.css">
</head>
<body>

	<div class="navMenu">
		<ul>
			<li><a href="userhomepage.php">Home</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>


	<div class="overlay">
	<form action="appointmentDb.php" method="post">
		<div class="head-form">
    		<h1>Clinic Appointment Form</h1>
      	 	<p>Please fill out the form below to schedule an appointment:</p>
    	</div>
	  
	    <label for="patientName">Patient Name:</label>
	    <input class="form-input" type="text" id="patientName" name="patientName" required><br><br>
	    
	    <label for="email">Email:</label>
	    <input class="form-input" type="email" id="email" name="email" required>
	    <p>The meeting link will be sent to this email, kindly provide a valid in use email.<br><br>

	    <label for="phoneNumber">Phone Number:</label>
	    <input class="form-input" type="tel" id="phoneNumber" name="phoneNumber" required><br><br>
	    
	    <label for="clinicid">Clinic ID:</label>
	    <div >
    	    <select class="sel" id="clinicid" name="clinicid" required>
              <?php
                $clinicIds = getClinicIds();
                echo $clinicIds;
                foreach ($clinicIds as $clinicId) {
                  echo '<option value="' . $clinicId . '">' . $clinicId . '</option>';
                }
              ?>
            </select><br><br>
	    </div>
	    
	    <?php 
	       $conn = connectDb();
	       $sql = "SELECT * FROM clinic";
	       $result = mysqli_query($conn, $sql);
	       
	       // Create an HTML table to display the data
	       echo "<div style='height: 200px; overflow: auto;'>";
	       echo "<table>";
	       echo "<tr><th>ID</th><th>Name</th><th>Address</th><th>Phone Number</th></tr>";
	       
	       // Loop through each row in the result set and add it to the HTML table
	       while ($row = mysqli_fetch_assoc($result)) {
	           echo "<tr>";
	           echo "<td>" . $row["clinicid"] . "</td>";
	           echo "<td>" . $row["name"] . "</td>";
	           echo "<td>" . $row["contact"] . "</td>";
	           echo "<td>" . $row["address"] . "</td>";
	           echo "</tr>";
	       }
	           
	       echo "</table><br><br>";
	       echo "</div>";
	       
	       // Close the database connection
	       mysqli_close($conn);
	    ?>
		<label for="deliveryAddress">Delivery Address:</label>
<input type="text" id="deliveryAddress" name="deliveryAddress" required><br><br>

	    <label for="appointmentDate">Appointment Date:</label>
	    <input type="date" id="appointmentDate" name="appointmentDate" required min="<?php echo date('Y-m-d', strtotime('today')); ?>" max="<?php echo date('Y-m-d', strtotime('+12 months')); ?>"><br><br>

	    <label for="appointmentTime">Appointment Time:</label>
	    <input type="time" id="appointmentTime" name="appointmentTime" required min="08:00" max="18:00"><br><br>
		
	    <button type="submit">Submit</button>

	</form>
	</div>
	
</body>
</html>