<!-- This is the Profile Display Page for the Forum -->

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
            
            echo "<center>";
            if (@$_GET['id']) 
            {
                //echo $_GET['id'];
                $check = mysqli_query($link , "SELECT * FROM forum.users WHERE id = '".$_GET['id']."'");
                
                if(mysqli_num_rows($check) != 0)
                {
                    //echo "<br/><br/>ID Found";
                    while ($row = mysqli_fetch_assoc($check)) 
                    {
                        echo "<br/><br/><br/><br/><img src = '".$row['profile_pic']."' width = 100 height = 100> <h1>" . $row['username'] . "</h1><br/><br/>" ;
                        echo "<br/><h4>No. of Posts  :&nbsp;&nbsp;" . $row['topics'] . "</h4>" ;
                        echo "<br/><h4>No. of Posts Replied to  :&nbsp;&nbsp;" . $row['replies'] . "</h4>" ;
                        echo "<br/><br/><h2 style = 'color:red;font-family:arial'>SCORE   :&nbsp;&nbsp;" . $row['score'] . "</h2>" ;
                    }
                }
                else
                    echo "ID not Found";
            }
            else 
            {
                echo "<script>location='index.php'</script>";
            }
            echo "</center>";
        
        ?>
    
    </body>

</html>
    
    
 <?php   
         
    else :
        echo "Please <a href = 'login.php'>Login</a> to continue";
    endif;

    if (@$_GET['action'] == "logout")
        log_out();

?>