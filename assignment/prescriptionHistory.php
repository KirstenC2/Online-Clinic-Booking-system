<?php
    $pharmaid=$_COOKIE['pharmaid'];
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
    function displayAllPres($pharmaid){
        $conn=connectDB();
        $sql = "SELECT * FROM prescription where pharmacyid = '$pharmaid';";
        
        // Execute the query
        $result = mysqli_query($conn, $sql);
        
        // Check if there are any rows returned from the query
        if (mysqli_num_rows($result) > 0) {
            // Start the HTML table
            echo '<table>';
            
            // Table header
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Doctor ID</th>';
            echo '<th>Pharmacy ID</th>';
            echo '<th>Medication</th>';
            echo '<th>Dosage</th>';
            echo '<th>Description</th>';
            echo '<th>Status</th>';
            echo '</tr>';
            
            // Loop through the rows and output the data
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['doctorid'] . '</td>';
                echo '<td>' . $row['pharmacyid'] . '</td>';
                echo '<td>' . $row['medication'] . '</td>';
                echo '<td>' . $row['dosage'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>' . $row['status'] . '</td>';
                echo '</tr>';
            }
            
            // End the HTML table
            echo '</table>';
        } else {
            // No rows returned from the query
            echo 'No data found';
        }
        
        // Close the database connection
        mysqli_close($conn);
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
			<li><a href="pharmaLogout.php">Logout</a></li>
		</ul>
	</div>
	<div class="appointmentSection">
		<h1>Prescription History</h1>
    	<form method="GET" action="historyPresPharmaSearchBar.php">
    	<select name="search_col">
            <option value="doctorid">Doctor ID</option>
            <option value="id">Prescription ID</option>
            <option value="clinicid">Clinic ID</option>
            <option value="medication">Medication</option>
            <option value="dosage">Dosage</option>
            <option value="description">Description</option>
            <option value="status">Status</option>
          </select>
          <input type="text" name="search_query">
  			<button type="submit" value="Search">Search</button>
        </form>
        <br><br>
		<?php 
		
		displayAllPres($pharmaid);?>
	</div>
</body>
</html>