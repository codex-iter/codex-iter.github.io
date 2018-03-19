<!-- Registeration page without Fancy Frontend -->
<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CODEX FORUM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- House Keeping Elements -->
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <!-- Sending register_parse the User's data by passing method = POST -->
    <form action = "register_parse.php" method = "POST">
        Enter a Username : <input type = "text" name = "username" placeholder="Enter a Username"><br><br>
        Enter a Password &nbsp;: <input type="password" name = "password" placeholder="Enter a Password"><br><br>
                   <input type="submit" name="register" value="REGISTER">
    </form>
</body>
</html>