<?php
// STARTS THE SESSION
session_start();

    include "dbsetup.php";

        if (isset($_SESSION['user_id']))
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                // DATABASE CONNECTION CONFIG
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "gamesdb";

                // CREATES CONNECTION
                $connection = new mysqli($servername, $username, $password, $database);

                // CHECKS THE CONNECTION
                if ($connection->connect_error) 
                {
                    die("Connection failed: " . $connection->connect_error);
                }

                $title = $_POST["title"];
                $description = $_POST["description"];

                // FILE UPLOAD CONFIG
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // CHECKS IF THE IMAGE IS AN ACTUAL IMAGE FILE
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) 
                {
                    $uploadOk = 1;
                } 
                else 
                {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }

                // CHECKS THE IMAGE SIZE
                if ($_FILES["image"]["size"] > 5000000) 
                {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // ALLOWS ONLY CERTAIN FILE FORMATS
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") 
                {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // CHECKS IF $uploadOk IS SET TO 0 BY DEFAULT
                if ($uploadOk == 0) 
                {
                    echo "Sorry, your file was not uploaded.";
                } 
                else 
                {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) 
                    {
                        // WHERE TO INSERT GAMES DETAILS INTO
                        $stmt = $connection->prepare("INSERT INTO gameslibrary (title, description, image_path) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $title, $description, $target_file);

                        if ($stmt->execute()) 
                        {
                            $connection->close();
                            header("Location: gameslibrary.php");
                            exit();
                        } 
                        else 
                        {
                            $connection->close();
                            $_SESSION["upload_error"] = true;
                            header("Location: addgame.php");
                            exit();
                        }
                    } 
                    else 
                    {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    else
        {
            header("Location: login.php");
            exit();
        }
?>

<!DOCTYPE html>
<html>
	<head>
		<title> Add a game - SERCsync </title>
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
			<a class="active" style="float: left" href="gamesnews.php"> <button> GAMES NEWS </button> </a>
			<a class="nonactive" style="float: right" href="login.php"> <button> ACCOUNT </button> </a>
			<a class="nonactive" style="float: right" href="gameslibrary.php"> <button>GAMES LIBRARY </button> </a>
		</div>

		<!-- PARALLAX IMAGE AND FUNCTION -->
		<div class="parallax">
			<div class="parallax-image"></div>
			<div class="parallax-content"></div>
		</div>

<?php
if (!isset($_SESSION['user_id']))
{
    echo("<a href='login.php'>Login</a>");
}
else
{
    echo("<a href='logout.php'>Log out</a>");
}

?>
    </div>
    
    <div>

<?php
    if ($_SESSION["upload_error"] == true)
    {
        echo("<p>There was an error uploading the game.</p>");
    }
?>

        <form method="post" action=""  enctype="multipart/form-data">
            <label for="title">Title:</label><br>
            <input type="text" name="title" required><br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="3" required></textarea>

            <label for="image">Choose Image:</label>
            <input type="file" name="image" id="image" required><br>

            <input type="submit" value="Upload">

        </form>
    </div>
</body>
</html>