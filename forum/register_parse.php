<?php

    include("connect.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

   /* echo "Registeration Details  : ";
    echo "Username : " . $username . "<br/><br/> Password : " . $password; */
    
    $sql = "INSERT INTO forum.users(username , password)
            VALUES ('$username' , '$password');
    ";

    $res = mysqli_query($link , $sql);

    if($res){
        echo "<b>Successfully Registered</b> as :   " . $username . "<br/><br/>";
    }
    else {
        echo "<b>Registration Failed</b><br/><br/>";
        echo "Error is =>   " . mysql_error();
    }

?>