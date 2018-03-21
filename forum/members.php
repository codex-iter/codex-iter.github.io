<!-- This is the Home Page for the Forum -->

<?php

    include("connect.php");
    session_start();
    require('connect.php');

    if (@$_SESSION["username"]):
?>

    <!--echo "Welcome " . $_SESSION['username'] . " !!<br/>";-->

<!DOCTYPE html>
<html>
    <head>
    
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Members of this forum</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
        <script src="main.js"></script>
        
    </head>

    <body>
    
        <?php 
        
        include("header.php");

        echo "<center><h1>Registered Members</h1>";

        $check = mysqli_query($link , "SELECT * FROM forum.users");
        $rows  = mysqli_num_rows($check);

        while ($row = mysqli_fetch_assoc($check)) 
        {
            $id = $row['id'];
            echo "<a href = 'profile.php?id=$id'>" . $row['username'] . "</a><br/><br/>" ;
        }
        echo "</center>";
        
        ?>
    
    </body>

</html>
    
    
 <?php   
         
    else :
        echo "Please <a href = 'login2.php'>Login</a> to continue";
    endif;

    if (@$_GET['action'] == "logout")
    {
        session_destroy();
        echo "<script>location='login2.php'</script>";
    }

?>