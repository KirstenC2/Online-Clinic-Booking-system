<?php 
    $apmtid = $_POST['apmtid'];
    $docid = $_POST['docid'];
    assignDoc($apmtid, $docid);
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
    
    function getDocName($docid) {
        // Connect to the database
        $conn = connectDB();
        
        
        // Prepare the SQL query
        $sql = "SELECT name FROM doctor WHERE id = '$docid'";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $sql);
        
        // Check for errors
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        
        // Fetch the clinic name
        $row = mysqli_fetch_assoc($result);
        $docname = $row['name'];

        
        // Close the database connection
        mysqli_close($conn);
        
        // Return the clinic name
        return $docname;
    }
    
    function assignDoc($apmtid,$docid){
        $doctor_name = getDocName($docid);
        $conn = connectDB();
        $sql = "UPDATE appointment SET doctor='$doctor_name' WHERE appointmentid='$apmtid'";
        
        if(strlen($apmtid)=="" || strlen($docid)==""){
            echo "Enter details to assign doctors.";
            header("Location:admindashboard.php");
        }
        else{
            if ($conn->query($sql) === TRUE) {
                
                header("Location:admindashboard.php");
                
                
            } else {
                echo "Error updating appointment: " . $conn->error;
            }
        }
        
        
        
        // Close the database connection
        $conn->close();
    }
    
?>