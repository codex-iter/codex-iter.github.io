<!-- This is  a header that contains -->
<!--        Home Page                -->
<!--        My Account               -->
<!--        Members                  -->
<!--        Logout                   -->

<?php
    require('connect.php');
    
    if (@$_SESSION["username"]):

?>

<style>
    a:link {
    color: indianred;
    background-color: transparent;
    text-decoration: none;
    font-family: sans-serif;
}
</style>

<center>

    |&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href = "index.php">Home Page</a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php 
    
    $check = mysqli_query($link , "SELECT * FROM forum.users WHERE username = '".$_SESSION['username']."'");
    while ($row = mysqli_fetch_assoc($check))
    {
        $id = $row['id'];
    }
    echo "<a href = 'profile.php?id=$id'>" . @$_SESSION['username'] . "'s Info" . "</a>";
    

    ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href = "members.php">  Members</a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href = "account.php">  Account Settings  </a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href = "index.php?action=logout">  Logout  </a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|

    </center>

<?php

    else :
        echo "<script>location='index.php'</script>";
    endif;

?>