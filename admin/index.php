<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <title>Login Page</title>
</head>

<body>
    <form action="login.php" method="post">
        <div class="login-box">
            <h2>ธรรมเจริญพาณิช</h2>

            <div class="textbox">
                <input type="text" placeholder="Username" name="username" value="">
            </div>

            <div class="textbox">
                <input type="password" placeholder="Password" name="password" value="">
            </div>

            <input class="button" type="submit" name="login" value="Sign In">
        </div>
    </form>
</body>
</html>
