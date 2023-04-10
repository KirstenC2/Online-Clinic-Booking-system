<?php
    $clinicData =  array();
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
    $conn = connectDB();
    
    function retrieveClinics(){

        $sql = "select * from clinic;";
        $conn = connectDB();
        $result = $conn->query($sql);
        $array = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
        } else {
            echo "0 results";
        }
        return $array;
    }
    $clinicData = retrieveClinics();
   
?>
    
<!DOCTYPE html>
<html>
<head>
	<title>Clinics</title>
	<link rel="stylesheet" href="stylingcss.css">
	<style>
	   
	   .header{
			padding: 60px;
		  text-align: center;
		  color: white;
		  font-size: 30px;
		  background: #408080;
		}
	</style>
</head>
<body>
	<div class="navMenu">
		<ul>
			<li><a href="MainPage.html">Home</a>
			<li><a href="Clinics.php">Clinics</a>
			<li><a href="pharmacy.php">Pharmacy</a>
			<li><a href="login.html">Login</a>
		</ul>
	</div>
	<div class="header">
		<h1>Our Partner Clinics</h1>
		<p>Our online clinics established by many different professionals.</p>
	</div>
	<?php for($i = 0;$i<count($clinicData);$i++){?>
    	<div class="card">
          <div class="container">
            <h4><b><?php echo $clinicData[$i]['clinicid'];?></b></h4>
            <p><?php echo $clinicData[$i]['name']; ?></p>
            <p><?php echo $clinicData[$i]['contact']; ?></p>
            <p><?php echo $clinicData[$i]['address']; ?></p>
          </div>
	</div>
	<?php }?>
	<footer>
      <p>Copyright © 2023 EzClinic Website</p>
      <nav>
        <a href="#">About</a> |
        <a href="#">Contact</a> |
        <a href="#">Privacy Policy</a>
      </nav>
	</footer>
</body>
</html>