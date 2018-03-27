<!-- This is the Posts Page for the Forum -->

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
    
        <?php include("header.php"); ?><br/><br/>
        <form>
            <center>
                <h2 style="font-family:arial">Subject</h2><input type="text" , name = "topic" , style = "width: 200px;height:20px" ><br/>
                <h2 style="font-family:arial">Body</h2><textarea name = "content" style = "text-align : none;resize: none; width: 400px; height: 300px">What do you want to post?</textarea>
                <!--<h2>Body</h2><br/><input type="textarea" , name = "content" , style = "text-align : none;resize: none; width: 400px; height: 300px" ><br/><br/>-->
                <br/><br/><input type="submit" , name = "post_thread" , value = "POST", style = "width: 200px;height:50px;font-family:cursive;font-weight:bold;font-size:30px;" ><br/><br/>
            </center>
        </form>
    
    </body>

</html>
    
    
 <?php   
         
    else :
        echo "Please <a href = 'login.php'>Login</a> to continue";
    endif;

    if (@$_GET['action'] == "logout")
        log_out();
      
    

?>