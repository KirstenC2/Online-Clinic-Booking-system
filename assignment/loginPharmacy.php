<?php
$email = $_POST['email'];
$password = $_POST['password'];
if(strlen($email)=="" || strlen($password)==""){
    header("Location:pharmacyLogin.html");
    
}
else{
    header("Location: pharmaAdminDashboard.php");
}
list($pswget,$username,$adminpharma) = checkValidUser($email);
$validation = comparePassword($pswget,$password);
if($validation == 1){
    $cookie_name1 = "user";
    $cookie_value = $username;
    setcookie($cookie_name1, $cookie_value);
    
    $cookie_name2 = "adminpharma";
    $cookie_value1 = $adminpharma;
    setcookie($cookie_name2, $cookie_value1);
    
    $cookie_email = "email";
    $cookie_value2 = $email;
    setcookie($cookie_email, $cookie_value2);
    
    
    
}
else{
    echo"password incorrect";
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
function checkValidUser($email){
    $passwordGet = "";
    $username = "";
    echo $email;
    $sql = "select * from pharmaadmin where email= '$email';";
    $conn = connectDB();
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $passwordGet = $row["password"];
            $username = $row["email"];
            $adminpharma = $row["pharmaid"];
        }
    } else {
        echo "0 results";
    }
    return array($passwordGet,$username,$adminpharma);
}

function comparePassword($passwordget,$passwordentered){
    if($passwordentered == $passwordget){
        return 1;
        //
    }
    else{
        return 0;
    }
    
}

