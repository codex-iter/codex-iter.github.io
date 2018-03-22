<?php

    include("connect.php");
    include("forum_functions.php");

    $username = $_POST['username'];
    $password = $_POST['password'];
    $repass   = $_POST['repass'] ;

    if ($repass == $password) 
    {
    
        $minpl = 8;

    /* echo "Registeration Details  : ";
    echo "Username : " . $username . "<br/><br/> Password : " . $password; */

    //CHecking If the User forgot to enter the Username or Password
    if ( $username == "" || $password == ""){
        echo "Please fill in all the details <br/> Please check if you forgot to enter data in any of the fields <br/>";
    }

    //Checking for invalid Username or short Password
    //The account will be fed to the database only if the above two conditions are satisfied.
    else 
    {
        if( checkInvalidCharacters($username) )
            echo "Username can only contain <br/><br/><ul><li> A to Z </li><li> a to z </li><li> 0 to 9 </li><li> . and _ </li></ul>";

        else 
        {
            if (strlen($password) >= $minpl) 
            {
                    $password = encrypt_pswd($password);
                    
                    $sql = "INSERT INTO forum.users(username , password)
                            VALUES ( ? , ? );
                            ";

                    $stmt = $link->prepare($sql);
                    $stmt->bind_param('ss' , $username , $password);

                    //$res = mysqli_query($link , $sql);

                    if($stmt->execute())
                    {
                        echo "<b>Successfully Registered</b> as :   " . $username . "<br/><br/>";
                    }
    
                    else 
                    {
                        echo "<b>Registration Failed</b><br/><br/>";
                        echo "Error is =>   " . mysqli_error($link);
                    }

                
            }

            else {
                echo "Password is too short. <br/> <b>Minimum length is 8 characters</b>";
            }
        }
    }
     
    }

    else 
    {
        echo "The two Passwords Do not Match <br/> Please Re-enter the password";
    }

     

?>