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
    function showResult(){
        // Get the user's input
        $pharmaid = $_COOKIE['pharmaid'];
        $search_col = $_GET['search_col'];
        $search_query = $_GET['search_query'];
        
        // Build the SQL query
        $sql = "SELECT id,doctorid,clinicid,medication,dosage,description,status FROM prescription WHERE $search_col LIKE '%$search_query%' AND pharmacyid = '$pharmaid'; ";
        $conn = connectDB();
        // Execute the query
        $result = mysqli_query($conn, $sql);
        
        // Display the results in a table
        echo "<table>";
        echo "<tr><th>ID</th><th>Doctor ID</th><th>Pharmacy ID</th><th>Medication</th><th>Dosage</th><th>Description</th><th>Status</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>".$row['id']."</td><td>".$row['doctorid']."</td><td>".$row['clinicid']."</td><td>".$row['medication']."</td><td>".$row['dosage']."</td><td>".$row['description']."</td><td>".$row['status']."</td></tr>";
        }
        echo "</table>";
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
	
	<?php showResult();?>
	<br>
	
	
	<a href="prescriptionHistory.php">Back</a>

</body>
</html>
