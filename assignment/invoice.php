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
function checkPendingPrescriptionPayment($pharmaid){
    $conn = connectDB();
    $sql = "SELECT p.id,p.amount,p.appointmentID,a.Status,a.email,a.deliveryaddress FROM prescription AS p INNER JOIN appointment AS a
                    ON p.appointmentID = a.appointmentID
                    WHERE p.amount<>0 AND pharmacyid = '$pharmaid';";
    $conn = connectDB();
    $result = mysqli_query($conn, $sql);
    
    // Store the results in an array
    $pendingPayment = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $pendingPayment[] = $row;
    }
    return $pendingPayment;
    
}

$pendingPayment = checkPendingPrescriptionPayment($pharmacyid);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Pharmacy Invoice</title>
	<link rel="stylesheet" href="stylingcss.css">
	<style type="text/css">
		body {
			font-family: Arial, sans-serif;
			font-size: 14px;
			color:black;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 20px;
		}
		table th, table td {
			padding: 8px;
			text-align: left;
			border: 1px solid #ddd;
		}
		table th {
			background-color: #f2f2f2;
		}
		h1 {
			font-size: 24px;
			margin-bottom: 10px;
		}
	</style>
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
	<div>
		<h1>Pharmacy Invoice</h1>

		<p><strong>Invoice Date:</strong> March 5, 2023</p>
		
	</div>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <h1>Select prescription</h1>
      
      <label for="apmtid">Prescription ID:</label>
      <select name="apmtid" id="apmtid" required><br><br>
        <option value="">Select Prescription ID</option>
        <?php
          // connect to database and retrieve prescription IDs
          $conn = connectDB();
          $sql = "SELECT * FROM prescription";
          $result = mysqli_query($conn, $sql);
          
          // loop through results and display as options in select tag
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['appointmentid'] . '">' . $row['id'] . '</option>';
          }
        ?>
      </select><br><br>
      <button type="submit">Check prescription</button>
      
</form>
<?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve the selected prescription ID from the form
        $apmtid = $_POST['apmtid'];
        setcookie("amptidtopay",$apmtid);
        // Query the database for the prescription and appointment data
        $query = "SELECT p.id,p.appointmentid,p.clinicid, appointment.*
                FROM prescription as p
                INNER JOIN appointment ON p.appointmentID = appointment.appointmentID
                WHERE p.appointmentid = '$apmtid' AND appointment.status NOT IN ('Cancelled', 'Completed')";
        // Execute the query and store the results
        $conn=connectDB();
        $result = mysqli_query($conn, $query);
        
    // Output the results in a table
      if (mysqli_num_rows($result) > 0) {
          echo "<h2>Prescription Details</h2>";
          echo "<table>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>Prescription ID</th>";
          echo "<th>Appointment ID</th>";
          echo "<th>Date</th>";
          echo "<th>Time</th>";
          echo "<th>Email</th>";
          echo "<th>Patient Name</th>";
          echo "<th>Delivery Address</th>";
          echo "<th>Clinic ID</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>";
              echo "<td>" . $row['appointmentid'] . "</td>";
              echo "<td>" . $row['date'] . "</td>";
              echo "<td>" . $row['time'] . "</td>";
              echo "<td>" . $row['email'] . "</td>";
              echo "<td>" . $row['patientname'] . "</td>";
              echo "<td>" . $row['deliveryaddress'] . "</td>";
              echo "<td>" . $row['clinicID'] . "</td>";
              echo "</tr>";
          }
          echo "</tbody>";
          echo "</table>";
      } else {
          echo "No results found.";
      }}
      ?>
        <form action="pharmaUpdatePayment.php" method="POST">
          <h1>Enter Payment Amount</h1>
          
          <label for="appointmentID">Appointment ID:</label>
        <input type="text" name="appointmentID" id="appointmentID"required>
        
          <label for="payment-amount">Payment Amount:</label>
          <input type="number" name="payment-amount" id="payment-amount" required>
        
          <button type="submit">Update Payment</button>
        </form>
		<h2>All Pending payment</h2>
    	<table >
			<thead>
    			<tr>
    				<th>Prescription ID</th>
                    <th>Amount to pay</th>
                    <th>Appointment ID</th>
                    <th>Status</th>
                    <th>E-mail</th>
                    <th>Delivery Address</th>
                    
                </tr>
             <thead>
        </table>
        <table>
            <?php for($i=0;$i<count($pendingPayment);$i++){?>
             <tbody>
             	<tr>
                 	<td><?php echo $pendingPayment[$i]['id']?></td>
                    <td><?php echo $pendingPayment[$i]['amount']?></td>
                    <td><?php echo $pendingPayment[$i]['appointmentID']?></td>
                    <td><?php echo $pendingPayment[$i]['Status']?></td>
                    
                    <td><?php echo $pendingPayment[$i]['email']?></td>
                    <td><?php echo $pendingPayment[$i]['deliveryaddress']?></td>
                    
                 </tr>
                 <?php }?> 
             </tbody>
        </table>   
</body>
</html>
