<?php
    // STARTS THE SESSION
    session_start();

    include "dbsetup.php";

    // FUNCTION WHICH AUTHENTICATES USER
    function authenticateUser($username, $password) 
     {
        // ECREATES DATABASE CONNECTION
        $host = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $db = "gamesdb";

        global $connection;
        $connection = new mysqli($host, $dbusername, $dbpassword, $db);

        // CHECKS CONNECTION
        if ($connection->connect_error) 
        {
            die("Connection failed: " . $connection->connect_error);
        }

        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) 
        {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) 
            {
                return $user;
            }
        }

        return null;
    }

    // LOGIN PROCESS
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = authenticateUser($username, $password);

        if ($user) 
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['upload_error'] = false;
            header("Location: index.php");
            exit();
        } 
        else 
        {
            $login_error = "Invalid username or password";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Log In - SERCsync </title>
            <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <script src="script.js"></script>

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Gluten:wght@900&family=Madimi+One&display=swap" rel="stylesheet">
    </head>
        
    <body>
        
        <!-- HEADER LOGO AND BUTTONS -->
        <div id="header">
            <img src="logo.png" width="400" />
            <a class="nonactive" style="float: left" href="index.php"> <button> HOME </button> </a>
            <a class="nonactive" style="float: left" href="gamesnews.php"> <button> GAMES NEWS </button> </a>
            <a class="active" style="float: right" href="login.php"> <button> ACCOUNT </button> </a>
            <a class="nonactive" style="float: right" href="gameslibrary.php"> <button> GAMES LIBRARY </button> </a>
        </div>

        <!-- PARALLAX IMAGE AND FUNCTION -->
        <div class="parallax">
            <div class="parallax-image"></div>
            <div class="parallax-content"></div>
        </div>

        <!-- LOG IN CONTAINER -->
        <div class="container">
        <section class="containerSeven">
            <div class="containerSeven-container">
                    <hh2> <br> Log in now! </hh2>
                <form method="post" action="">
                    <label> <br> Username:</label><br>
                    <input type="text" name="username"><br>
                    <label> <br> Password:</label><br>
                    <input type="password" name="password"><br>
                    <input type="submit" value="Login">
                </form>
                <hh2> No account? <a href="register.php"> Register </a></hh2>
                <?php if (isset($login_error)) echo "<pp>$login_error</pp>";
                
                echo("<br>");

                 if (!isset($_SESSION['user_id']))
                {
                    echo("<a href='login.php'> <pp> You are currently: Not logged in!  Click here to log in! </pp> </a>");
                }
                else
                {
                    echo("<a href='logout.php'> <pp> You are currently: Logged in! Click here to log out! </pp> </a>");
                } 

                    // LOGOUT PORCESS
                if (isset($_GET['logout'])) 
                {
                    session_destroy();
                    header("Location: login.php");
                    exit();
                }
                ?>
            </div>
        </section>
        </div>
    </body>
</html>
