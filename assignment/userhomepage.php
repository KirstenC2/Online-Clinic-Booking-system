<?php
   
    $username = $_COOKIE['user'];
    $email = $_COOKIE['email'];
    if (isset($_COOKIE['user'])) {
        // Cookie is not empty, do something with it
    } else {
        // Cookie is empty, do something else
        header("location:login.html");
    }
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
    function checkPendingPrescriptionPayment($email){
        $conn = connectDB();
        $sql = "SELECT p.id,p.amount,p.appointmentID,a.Status,a.email,a.deliveryaddress FROM prescription AS p INNER JOIN appointment AS a
                    ON p.appointmentID = a.appointmentID
                    WHERE p.amount<>0 AND a.email = '$email';";
        $conn = connectDB();
        $result = mysqli_query($conn, $sql);
        
        // Store the results in an array
        $pendingPayment = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $pendingPayment[] = $row;
        }
        return $pendingPayment;

    }
    function getUserInfo($email){
        $sql = "select fullname,icnumber,address,contact,email,birthday,patientID
                 from registration where email= '$email';";
        $conn = connectDB();
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                return array($row['fullname'],$row['icnumber'],$row['address'],$row['contact'],$row['email'],$row['birthday'],$row['patientID']);
            }
        } else {
            echo "0 results";
        }
    }
    list($a,$b,$c,$d,$e,$f,$g)=getUserInfo($email);
    
    function retrieveAppointments($email){
        $conn = connectDB();
        $sql = "SELECT * FROM appointment WHERE email = '$email' AND Status NOT IN ('Cancelled','Completed')";
        $result = mysqli_query($conn, $sql);
        
        // Store the results in an array
        $appointments = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
        return $appointments;
    }
    $appointment = retrieveAppointments($email);
    $pendingPayment = checkPendingPrescriptionPayment($email);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" href="userpage.css">
	<style>
	   body {
            background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);
            background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
            background-attachment: fixed;
            background-repeat: no-repeat;
            
            font-family: 'Vibur', cursive;
        /*   the main font */
            font-family: 'Abel', sans-serif;
            opacity: .95;
        /* background-image: linear-gradient(to top, #d9afd9 0%, #97d9e1 100%); */
        }
	   .paymentBtn{
          font-weight: bold;
          font-size: 2rem;
          cursor: pointer;
          border: none;
          width:20%;
          margin: .1em;
    	       --b: 3px;   /* border thickness */
              --s: .15em; /* size of the corner */
              --c: #555555;
          margin-left:auto;
            margin-right:auto;
            text-align:center;
            
          padding: calc(.05em + var(--s)) calc(.3em + var(--s));
          color: var(--c);
          --_p: var(--s);
          background:
            conic-gradient(from 90deg at var(--b) var(--b),#0000 90deg,var(--c) 0)
            var(--_p) var(--_p)/calc(100% - var(--b) - 2*var(--_p)) calc(100% - var(--b) - 2*var(--_p));
          transition: .3s linear, color 0s, background-color 0s;
          outline: var(--b) solid #555555;
          outline-offset: .2em;
        }
        .paymentBtn a{
            color: var(--c);
          --_p: var(--s);
          text-decoration:none;
        }
        .paymentBtn:hover,
        .paymentBtn:focus-visible{
          --_p: 0px;
          outline-color: var(--c);
          outline-offset: .05em;
        }
        .paymentBtn:active {
          background: var(--c);
          color: #fff;
        }
        .payBtnContainer{

            
        }  
        .center{
          position: relative;
          padding: 50px 50px;
          background: #fff;
          border-radius: 10px;
          text-align:center;
        }
        .center label{
          font-size:20px;
        }
        .center select{
           font-size: 13px;
            color: #888;
            cursor: pointer;
            transition: all .3s ease-in-out;
        
            line-height: 20px
        
            display: block;
            padding: 10px;
        }

/* CSS */
    .button-56 {
      align-items: center;
      background-color: #fee6e3;
      border: 2px solid #111;
      border-radius: 8px;
      box-sizing: border-box;
      color: #111;
      cursor: pointer;

      font-family: Inter,sans-serif;
      font-size: 16px;
      height: 48px;
      justify-content: center;
      line-height: 24px;
      max-width: 100%;
      padding: 0 25px;
      position: relative;
      text-align: center;
      text-decoration: none;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
    }
    
    .button-56:after {
      background-color: #111;
      border-radius: 8px;
      content: "";
      display: block;
      height: 48px;
      left: 0;
      width: 100%;
      position: absolute;
      top: -2px;
      transform: translate(8px, 8px);
      transition: transform .2s ease-out;
      z-index: -1;
    }
    
    .button-56:hover:after {
      transform: translate(0, 0);
    }
    
    .button-56:active {
      background-color: #ffdeda;
      outline: 0;
    }
    
    .button-56:hover {
      outline: 0;
    }
    
    @media (min-width: 768px) {
      .button-56 {
        padding: 0 40px;
      }
    }
	</style>
</head>
<body>
	<div class="navMenu">
		<ul>
			<li><a href="userhomepage.php">Home</a></li>
			<li><a href="makeAppointment.php">Make Appointment</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	<h1>Welcome Back!<?php echo $username ?></h1>
	<div class="box">
		<h2>Your Information</h2>
		<table>
          <tr>
            <td>Name</td>
            <td><?php echo $a?></td>
          </tr>
          <tr>
          	<td>IC Number</td>
          	<td><?php echo $b?></td>
          </tr>
          <tr>
          	<td>Address</td>
          	<td><?php echo $c?></td>
          </tr>
          <tr>
          	<td>Contact Number</td>
          	<td><?php echo $d?></td>
          </tr>
          <tr>
          	<td>Email</td>
          	<td><?php echo $e?></td>
          </tr>
          <tr>
          	<td>Birthday</td>
          	<td><?php echo $f?></td>
          </tr>
          <tr>
          	<td>Patient ID</td>
          	<td><?php echo $g?></td>
          </tr>
        </table>
	</div>
	<div class="tbl-header">
		<h2>Appointment You Have Made</h2>
		<table >
			<thead>
    			<tr>
    				<th>Appointment ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Clinic ID</th>
                    <th>Patient Name</th>
                    <th>Payment</th>
                    <th>Status</th>
                    
                </tr>
             <thead>
        </table>
    </div>
                
     <div class="tbl-content">
     	<table>
            <?php for($i=0;$i<count($appointment);$i++){?>
             <tbody>
             	<tr>
                 	<td><?php echo $appointment[$i]['appointmentID']?></td>
                    <td><?php echo $appointment[$i]['date']?></td>
                    <td><?php echo $appointment[$i]['time']?></td>
                    <td><?php echo $appointment[$i]['clinicID']?></td>
                    
                    <td><?php echo $appointment[$i]['patientname']?></td>
                    <td><?php echo $appointment[$i]['payment']?></td>
                    <td><?php echo $appointment[$i]['Status']?></td>
                    
                 </tr>
                 <?php }?> 
             </tbody>
        </table>   
        
        
        
	</div>
	<div class="center">
		<h1>Cancel Appointment</h1>
        	<form action="userCancelApmt.php" method="post">
        	<div class="head-form">
          	</div>
          	<label for="apmtid">Appointment ID:</label>
        	<select name="appointment_id">
                <?php
                // Call the retrieveAppointments function to get the appointments for the user
                $appointments = retrieveAppointments($email);
                
                // Loop through the appointments and create an option for each one
                foreach ($appointments as $appointment) {
                    // Get the date and time of the appointment
                    $datetime = date('m/d/Y h:i A', strtotime($appointment['date'].$appointment['time']));
                    // Set the appointment ID as the value for the option
                    $id = $appointment['appointmentID'];
                    // Create the option tag with the appointment date, time, and ID
                    echo "<option value='$id'>$datetime - Appointment ID -- $id </option>";
                }
                ?>
            </select>

    	<button class="button-56" role="button" type="submit">Cancel</button>
        </form>
	</div>
	<div class="box">
	<h2>Pending appointment payment</h2>
		<table>
  <tr>
    <th>Appointment ID</th>
    <th>Date</th>
    <th>Time</th>
    <th>Amount</th>
  </tr>
  <?php
  // Query the database for the appointments where the amount has not been paid
  $query = "SELECT p.amount,a.payment,a.email,a.appointmentID,r.email,a.date, a.time
  FROM appointment AS a
  LEFT JOIN prescription AS p ON a.appointmentID = p.appointmentID
  LEFT JOIN registration AS r ON a.email = r.email
    WHERE a.email = '$email';";
      $conn = connectDB();
      $result = mysqli_query($conn, $query);
      
      // Output each appointment as a row in the table
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['appointmentID'] . "</td>";

          echo "<td>" . $row['date'] . "</td>";
          echo "<td>" . $row['time'] . "</td>";
          echo "<td>" . $row['payment'] . "</td>";
          echo "</tr>";
      }
  ?>
</table>
</div>
		<div class="paymentBtn">
			<a href="payment.php">Make Payment</a>
		</div>
	>
	<div class="tbl-header">
		<h2>Pending Prescription Payment</h2>
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
    </div>
	<div class="tbl-content">
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
       </div>
       <div class="paymentBtn">
			<a href="paymentPrescription.php">Make Payment</a>
		</div>
        
	
		
	
	
	
</body>
</html>