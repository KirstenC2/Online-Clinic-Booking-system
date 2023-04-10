<?php
    $appointmentid = $_POST['apmtid'];
    $doctorid = $_POST['doctorid'];
    $pharmacyid = $_POST['pharmacyid'];
    $medication = $_POST['medication'];
    $dosage = $_POST['dosage'];
    $description = $_POST['desc'];
    $clinicid = $_COOKIE['adminclinic'];

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
    function generateRandomString() {
        $length = 15;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = 'PR';
        for ($i = 0; $i < $length - 2; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    function updatePrescriptionPatient($appointmentid,$patientid,$randomPresID){
        $conn = connectDB();
        $randomPresID=generateRandomString();
        $query = "INSERT INTO prescriptionpatient (id, patientid,appointmentid,prescription)
          VALUES ('$randomPresID','$patientid','$appointmentid','$randomPresID)";
        $result = mysqli_query($conn, $query);
        
        // Check if update was successful
        if ($result) {
            echo "Prescription ordered successfully";
        } else {
            echo "Error updating prescription: " . mysqli_error($conn);
        }
        
    }
    
    
    function updatePrescription($randomPresID,$clinicid,$doctorid, $pharmacyid, $medication, $dosage, $description,$appointmentid){
        $conn = connectDB();
        
        $query = "INSERT INTO prescription (id, doctorid, pharmacyid, medication, dosage, description,status,clinicid,appointmentid)
          VALUES ('$randomPresID', '$doctorid', '$pharmacyid', '$medication', '$dosage', '$description','Ordered','$clinicid','$appointmentid')";
        $result = mysqli_query($conn, $query);
        
        // Check if update was successful
        if ($result) {
            echo "Prescription ordered successfully";
            header("Location: prescription.php");
        } else {
            echo "Error updating prescription: " . mysqli_error($conn);
        }
    }
    $randomPresID=generateRandomString();
    updatePrescription($randomPresID,$clinicid,$doctorid, $pharmacyid, $medication, $dosage, $description,$appointmentid);

    ?>