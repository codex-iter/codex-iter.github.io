<?php

    //Connecting to the Database
    $database = array();
    $database['host'] = "localhost";
    $database['port'] = "3306";
    $database['name'] = "forum";
    $database['username'] = "root";
    $database['password'] = "";
    
    $link = mysqli_connect($database['host'] , $database['username'] , $database['password'] , $database['name'] );

    if($link){
        echo "<b>Successfully</b> connected to database   :  " . $database['name'] . "<br/><br/>";
    }
    else {
        echo "Coonection to database  " . $database['name'] . " <b>FAILED</b> <br/>";
        echo "Error is =>   " . mysql_error();
    }

?>