<?php
	// SET UP THE DATABASE FOR THE CONNECTION
	
	// SETS UP THE CONNECT TO mysql
	$host = "localhost";
	$username = "root";
	$dbpassword = "";
	
	global $connection;
	$connection = new mysqli($host, $username, $dbpassword); // gets use a connection object to use to access mysql
	
	// CHECKS IF THE CONNECTION WORKS
	if ($connection->connect_error)
	{
		die("Connection failed: " . $connection->connect_error);
		
	}

	// CREATES THE DATABSE FOR THE APPLICATION
	$sql_create_db = "CREATE DATABASE IF NOT EXISTS gamesdb"; 
	if ($connection->query($sql_create_db) === TRUE)
	{
	}
	else
	{
		echo "<br>Error creating database: " . $connection->error;
	}
	
	// SELECTS THE DATABASE
	$connection->select_db("gamesdb");

    // CREATES THE USERS TABLE WITH 3 FIELDS: ID, username AND password
    // ID FIELD IS THE PRIMARY KEY FOR THE TABLE
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(100) NOT NULL
    )";

    if ($connection->query($sql) === TRUE) 
    {
    } 
    else 
    {
        echo "Error creating table: " . $conn->error;
    }

	// CREATES A TABLE FOR THE SELECTED DATABASE
    // SPECIFIES 3 FIELDS: ID, username AND email
    // ID FIELD IS THE PRIMARY KEY FOR THE TABLE
	$sql = "CREATE TABLE IF NOT EXISTS gameslibrary (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title TEXT,
        description TEXT,
        image_path VARCHAR(255) NOT NULL
    )";

    if ($connection->query($sql) === TRUE) 
    {
    } 
    else 
    {
        echo "Error creating table: " . $conn->error;
    }

	// CLOSES THE DATABASE CONNECTION
	$connection->close();
?>

