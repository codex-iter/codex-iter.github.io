<!-- This is the User's Account Page for the Forum -->

<?php

    session_start();
    require('connect.php');
    require('forum_functions.php');

    if (@$_SESSION["username"]):
?>

    <!--echo "Welcome " . $_SESSION['username'] . " !!<br/>";-->

<!DOCTYPE html>
<html>
    <head>
    
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CODEX - Forum</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
        <script src="main.js"></script>
        
    </head>

    <body>
    
        <?php
        
        include("header.php"); 
        
        $check = mysqli_query($link , "SELECT * FROM forum.users WHERE username = '".$_SESSION['username']."'");
        while ($row = mysqli_fetch_assoc($check))
        {
            echo "<center><br/><br/><br/><br/><img src = '".$row['profile_pic']."' width = 100 height = 100 alt = 'Your DP'> <h1>" . $row['username'] . "</h1><br/><br/></center>" ;
            $username = $row['username'];
        }

        //echo "<a href = 'profile.php?id=$id'>" . @$_SESSION['username'] . "'s Account" . "</a>";

        ?>
        <center><a href = "account.php?action=cp" >Change Your Password</a></center><br/>    
        <center><a href = "account.php?action=ci" >Change Your Profile Pic</a></center>    

    </body>

</html>
    
    
 <?php   
         
    else :
        echo "Please <a href = 'login2.php'>Login</a> to continue";
    endif;

    if (@$_GET['action'] == "logout")
        log_out();

    if (@$_GET['action'] == "cp")
        change_password($_SESSION["username"]);
    
    if (@$_GET['action'] == "ci")
        change_image($_SESSION["username"]);


?>