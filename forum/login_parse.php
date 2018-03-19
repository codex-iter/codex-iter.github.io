<?php
    //Login Functions
    
    include("connect.php");
    include("forum_functions.php");
    
    $username = $_POST['username'];
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
     
        if( checkInvalidCharacters($username))
            echo "Username can only contain <br/><br/><ul><li> A to Z </li><li> a to z </li><li> 0 to 9 </li><li> . and _ </li></ul>";

        else 
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
            if(crypt($password , $hashedPswd) == $hashedPswd )
            {
                echo "The Password <b>Matched</b>";
            }
            else 
            {
                echo "Password <b>Doesn't Match</b><br/>Please enter th ecorect password ";
            }
        }
    }


?>