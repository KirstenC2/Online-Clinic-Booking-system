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
    
    
    function insertNewUser($name,$icno,$address,$phonenumber, $email, $password,$bday){
        
        $conn = connectDB();
        $patientID = "p01";
        $sql = "INSERT INTO registration (fullname,icnumber,address,contact,email,password,birthday,patientID)
                   VALUES ('$name','$icno','$address','$phonenumber','$email','$password','$bday','$icno$name');";
        
        if ($conn->query($sql) === TRUE) {
            return 1;
        } else {
            return 0;
        }
        
        $conn->close();
        
    }
    
    //get value entered by user
    $name = $_POST["fname"];
    $icno = $_POST["icnumber"];
    $bday = $_POST["birthday"];
    $address = $_POST["address"];
    $phonenumber = $_POST["phonenumber"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    
    $a=insertNewUser($name,$icno,$address,$phonenumber, $email, $password,$bday);
    if($a ==1){
        header("Location: registerSuccesful.html");
    }
    else{
        echo "unsuccessful";
    }
?>
