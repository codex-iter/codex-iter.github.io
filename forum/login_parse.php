<?php
    //Login Functions
    
    session_start();                    //To start sessional storage
    include("connect.php");             //To link to database
    include("forum_functions.php");     //To include neccessary functions
    
    $username = $_POST['username'];     //Gets username and password by the post method.
    $password = $_POST['password'];

   // echo "Username : " . $username . "<br/><br/> Password : " . $password;

   //CHecking If the User forgot to enter the Username or Password
    if ( $username == "" || $password == "")
    {
        echo "Please fill in all the details <br/> Please check if you forgot to enter data in any of the fields <br/>";
    }

    //Checking for invalid Username or short Password
    //The account will be fed to the database only if the above two conditions are satisfied.
    else
    {
     
        if( checkInvalidCharacters($username)) //If there are any invalid chars in username
            echo "Username can only contain <br/><br/><ul><li> A to Z </li><li> a to z </li><li> 0 to 9 </li><li> . and _ </li></ul>";

        //To check if the user hsa created an account or not
        elseif (mysqli_num_rows(mysqli_query($link , "SELECT * FROM forum.users WHERE username = '".$username."'")) == 0) 
        {
            echo "<h1>Username Not Found!!</h1> <br/><br/> You can Register here <br/><br/> <a href='register.php'>Register</a>";
        }

        else                                  //If there are no invalid chars
        {
            $sql = "
                    SELECT password FROM forum.users
                    WHERE username = ? LIMIT 1;
            ";

            $stmt = $link->prepare($sql);
            $stmt->bind_param('s' , $username);
            $stmt->execute();
            $stmt->bind_result($hashedPswd);

            $stmt->fetch();
            if(crypt($password , $hashedPswd) == $hashedPswd ) //If encrypting current password == encrypted password in database
            {
                echo "<b>Successfully Logged In</b>";
                $_SESSION["username"] = $username;
                //header("Location : ");
                echo "<script>location='index.php'</script>";
            }
            else                                              //If not then...
            {
                echo "<b>Wrong Password</b><br/>Please enter the correct password ";
            }
        }
    }


?>