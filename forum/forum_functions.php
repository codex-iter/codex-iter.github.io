<?php
    
    

    //Function to check for invalid characters in the Username
    function checkInvalidCharacters($text) {
        
        return(bool) preg_match("/[^\w-.]/" , $text );
    }

    //Function to encrypt the users Password
    function encrypt_pswd($input , $rounds = 9 ){

        $salt = "";

        $saltChars = array_merge(range('A' , 'Z') , range('a' , 'z') , range('0' , '9') );

        for($i = 0 ; $i < 22 ; $i++)
        {
            $salt .= $saltChars[array_rand($saltChars)];
        }

        return crypt($input  , sprintf('$2a$%02d$' , $rounds) . $salt);
    }

    function log_out() {

        session_destroy();
        echo "<script>location='login2.php'</script>";
    }

    function change_password($user) {
        
        require('connect.php');

        echo "<center><br/><hr/><br/><br/>
            <form action = account.php?action=cp method = 'POST'>
                Current Password :
                <input type = 'password' name = 'curr_pass'><br/>
                New Password :
                <input type = 'password' name = 'new_pass'><br/>
                Retype New Password :
                <input type = 'password' name = 'new_pass2'><br/>
                <input type = 'submit' name = 'change_pass' value = 'Change My password'><br/>
            </form>
            </center>";

            //$flag = 0;

            $curr_pass = @$_POST['curr_pass'];
            $new_pass = @$_POST['new_pass'];
            $new_pass2 = @$_POST['new_pass2'];

            $sql1 = "
                    SELECT password FROM forum.users
                    WHERE username = ? LIMIT 1;
            ";

            $stmt = $link->prepare($sql1);
            $stmt->bind_param('s' , $user);
            $stmt->execute();
            $stmt->bind_result($hashedPswd);

            $stmt->fetch();
             
            if(isset($_POST['change_pass']))
            {
                //$check = mysqli_query($link , "SELECT * FROM forum.users WHERE username = '".$_SESSION['username']."'");
            
                  if(crypt($curr_pass , $hashedPswd) == $hashedPswd )
                    {
                        if (strlen($new_pass) > 7) 
                        {
                            if($new_pass == $new_pass2)
                            {
                                $password = encrypt_pswd($new_pass);

                                //$query = mysqli_query($link , "UPDATE users SET password = '".$new_pass."' WHERE username = '".$user."'");
                    
                                $sql = "UPDATE users SET password = ?
                                        WHERE  username = $user;                                        
                                        ";

                                $stmt = $link->prepare($sql);
                                $stmt->bind_param('s' , $password);

                                //$res = mysqli_query($link , $sql);
                                
                                if($stmt->execute())
                                {
                                    echo "<h3 style='text-align:center; color:green;'>Your Password has been Changed </h3>";
                                }
    
                                else 
                                {
                                    echo "<h3 style='text-align:center; color:red;'>Couldnot change your password</h3>";
                                    echo "Error is =>   " . mysqli_error($link);
                                }
                            }

                            else 
                            {
                                echo "<h3 style='text-align:center; color:red;'>The two passwords do not match</h3>";
                            }
                        }

                        else 
                        {
                            echo "<h3 style='text-align:center; color:red;'>New Password is too short</h3>";    
                        }
                    }

                    else 
                    {
                        echo "<h3 style='text-align:center; color:red;'>Your cuurent Password doesn't match with the typed password</h3>";        
                    }

            }
                               
    }
    
?>