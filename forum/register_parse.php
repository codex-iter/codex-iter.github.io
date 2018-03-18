<?php

    include("connect.php");
    include("forum_functions.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

    $minpl = 8;

   /* echo "Registeration Details  : ";
    echo "Username : " . $username . "<br/><br/> Password : " . $password; */

    if ( $username == "" || $password == ""){
        echo "Please fill in all the details <br/> Please check if you forgot to enter data in any of the fields <br/>";
    }

    else {
        if( checkInvalidCharacters($username) )
            echo "Username can only contain <br/><br/><ul><li> A to Z </li><li> a to z </li><li> 0 to 9 </li><li> . and _ </li></ul>";

        else {
            if (strlen($password) >= $minpl) {

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

                
            }

            else {
                echo "Password is too short. <br/> <b>Minimum length is 8 characters</b>";
            }
        }
    }
    

?>