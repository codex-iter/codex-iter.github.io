<!-- Login page without Fancy Frontend -->

<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CODEX FORUM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <form action = "login_parse.php" method = "POST">
        Username : <input type = "text" name = "username" placeholder="Enter your Username"><br><br>
        Password &nbsp;: <input type="password" name = "password" placeholder="Enter your Password"><br><br>
                   <input type="submit" name="login" value="LOGIN">
    </form>
</body>
</html>