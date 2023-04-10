<?php
    $email = $_POST['email'];
    $password = $_POST['password'];
    
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
        $sql = "select fullname,password from registration where email= '$email';";
        $conn = connectDB();
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $passwordGet = $row["password"];
                $username = $row["fullname"];
            }
        } else {
            echo "0 results";
        }
        return array($passwordGet,$username);
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
    
    
    
    list($pswget,$username) = checkValidUser($email);
    $validation = comparePassword($pswget,$password);
    if($validation == 1){
        $cookie_name1 = "user";
        $cookie_value = $username;
        setcookie($cookie_name1, $cookie_value);
        
        $cookie_email = "email";
        $cookie_value2 = $email;
        setcookie($cookie_email, $cookie_value2);
        
        header("Location: userhomepage.php");
    }
    else{
        echo"password incorrect";
    }
    

    
?>