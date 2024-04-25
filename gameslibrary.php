<?php
// STARTS THE SESSION
session_start();

include "dbsetup.php";

function get_games()
{

    // DATABASE CONNECTION
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $db = "gamesdb";

    $connection = new mysqli($host, $dbusername, $dbpassword, $db);

    // CHECKS THE CONNECTION
    if ($connection->connect_error) 
    {
        die("Connection failed: " . $connection->connect_error);
    }

    $stmt = $connection->prepare("SELECT * FROM gameslibrary");
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Games Library - SERCsync </title>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css">
		<script src="script.js"></script>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Gluten:wght@900&family=Madimi+One&display=swap" rel="stylesheet">
	
		<link href="recent-news-boxes.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	</head>
	
	<body>
		
		<!-- HEADER LOGO AND BUTTONS -->
		<div id="header">
			<img src="logo.png" width="400" />
			<a class="nonactive" style="float: left" href="index.php"> <button> HOME </button> </a>
			<a class="nonactive" style="float: left" href="gamesnews.php"> <button> GAMES NEWS </button> </a>
			<a class="nonactive" style="float: right" href="login.php"> <button> ACCOUNT </button> </a>
			<a class="active" style="float: right" href="gameslibrary.php"> <button>GAMES LIBRARY </button> </a>
		</div>

		<!-- PARALLAX IMAGE AND FUNCTION -->
		<div class="parallax">
			<div class="parallax-image"></div>
			<div class="parallax-content"></div>
		</div>

        <div class="cardFull4">
			<div class="cardColour4">
				<?php
                    echo("<br>");

                    if (!isset($_SESSION['user_id']))
                    {
                    echo("<a href='login.php'> <pp> You are currently: Not logged in!  Click here to log in! </pp> </a>");
                    }
                    else
                    {
                    echo("<a href='logout.php'> <pp> You are currently: Logged in! Click here to log out! </pp> </a>");
                    } 
                ?>
                <div>
                    <a href="addgame.php">New game</a>
                </div>   
			</div>
		</div>
                
            



<?php
$dbgames = get_games();

if ($dbgames->num_rows == 0)
{
    echo("<pp>No games have been added, yet.</pp>"); 
}    
else
{
    $count = 1;

    echo("<div class='row'>");

    while($row = $dbgames->fetch_assoc())
    {
        if ($count % 4 == 0)
        {
            echo("<div class='row'>");
        }

        echo("<div class='column'>");
        echo("<div class='content'>");
        echo("<hh2>" . $row['title'] . "</hh2>");
        echo("<pp><br>" . $row['description'] ."</pp>");
        echo("<img src='". $row['image_path'] . "' alt='" . $row['description'] ."' class='gameImage'>");
        echo("</div>");
        echo("</div>");

        if ($count % 4 == 0)
        {
            echo("</div>");
        }

        $count++;
    }

}




?>


</body>
</html>