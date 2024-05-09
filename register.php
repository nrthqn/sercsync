<?php
    // STARTS SESSION
    session_start();

    include "dbsetup.php";

    // DATABASE CONNECTION
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $db = "gamesdb";

    $connection = new mysqli($host, $dbusername, $dbpassword, $db);

    // CHECKS CONNECTION
    if ($connection->connect_error) 
    {
        die("Connection failed: " . $connection->connect_error);
    }

    // FUNCTION USED TO HASH THE PASSWORD
    function hashPassword($password) 
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    function createUser($username, $email, $password) 
    {
        // DATABASE CONNECTION
        $host = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $db = "gamesdb";


        global $connection;
        $connection = new mysqli($host, $dbusername, $dbpassword, $db);

        // CHECKS THE CONNECTION
        if ($connection->connect_error) 
        {
            die("Connection failed: " . $connection->connect_error);
        }

        $hashed_password = hashPassword($password);

        $stmt = $connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    // CHECKS IF THE FORM IS SUBMITTED
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        // CREATES USER ACCOUNT
        if (createUser($username, $email, $password)) 
        {
            header("Location: login.php");
            exit();
        } 
        else 
        {
            echo "Error creating user account. Please try again!";
        }
    }
    ?>

<!DOCTYPE html>
<html>
    <head>
		<!-- ADDRESS BAR -->
		<title> Register - SERCsync </title>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /> 

		<!-- OTHER -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css">
		<script src="script.js"></script>

		<!-- FONT -->
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

		<!-- PARALLAX IMAGE -->
		<div class="parallax">
			<div class="parallax-image"></div>
			<div class="parallax-content"></div>
		</div>

        <!-- REGISTER CONTAINER -->
		<div class="container">
        <section class="containerEight">
            <div class="containerEight-container">
                    <hh2> <br> Register now! </hh2>
                <form method="post" action="">
                    <label> <br> Username:</label><br>
                        <input type="text" name="username"><br>
                    <label> <br> Password:</label><br>
                        <input type="password" name="password"><br>
                    <label> <br> Email: </label><br>
                        <input type="email" name="email"><br>
                        <input type="submit" value="Register">
                </form>
            </div>
        </section>

</html>