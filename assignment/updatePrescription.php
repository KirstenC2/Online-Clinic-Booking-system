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

    function updatePrescriptionStatus($prescriptionId, $newStatus) {
        // Create a new MySQLi object and connect to the database
        
        $conn = connectDB();
        
        // Prepare a SQL statement to update the prescription status
        
        $sql = "UPDATE prescription SET status = '$newStatus' WHERE id='$prescriptionId'";
        
        // Execute the SQL statement
        if(strlen($prescriptionId)=="" || strlen($newStatus)==""){
            echo "Enter details to update.";
            header("Location:pharmaAdminDashboard.php");
        }
        else{
            if ($conn->query($sql) === TRUE) {
                
                header("Location:pharmaAdminDashboard.php");
                
                
            } else {
                echo "Error updating prescription: " . $conn->error;
            }
        }
        $conn->close();
    }
    updatePrescriptionStatus($_POST['prescription_id'], $_POST['new_status']);

?>