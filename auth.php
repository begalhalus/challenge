<?php
session_start();

// set class
include('./Classes/User.php');
include('./Classes/Auth.php');

$auth = new Auth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = new User($username, $password);
    $auth = new Auth();

    if ($auth->authenticate($user)) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        $configSessionName = 'config_' . $username;

        if (isset($_SESSION[$configSessionName])) {
            // echo'<pre>';
            // print_r($_SESSION[$configSessionName]);
        } else {
            $_SESSION[$configSessionName] = [
                'username' => $username,
                'password' => $password,
                'balance' => 0,
                'history' => []
            ];
        }

        header('Location: ./');
        exit();
    } else {
        $errorMessage = "Login failed!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="./main.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <title>Login</title>
</head>

<body>
    <div class="auth">
        <h2>Sign In</h2>
        <form action="auth.php" method="post">
            <div class="error-messages"><?php if (isset($errorMessage)) echo $errorMessage; ?></div>
            <input type="text" name="username" title="Username" placeholder="Username" />
            <input type="password" name="password" title="Password" placeholder="Password" />
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>

</html>