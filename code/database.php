<?php
	// Set PHP default timezone to match your country's timezone
	date_default_timezone_set('Asia/Karachi'); // Replace with your timezone

	$db_host = 'localhost';
	$db_name = 'database';
	$db_user = 'username';
	$db_pass = 'password';
	$conn = new mysqli($db_host,$db_user,$db_pass,$db_name);
	if($conn->connect_error){
		printf("Connect failed: %s\n",$conn->connect_error);
		// Log error as well before exiting
		error_log("Database Connection Failed: " . $conn->connect_error, 3, "quiz_errors.log");
		exit; // Consider a more user-friendly die message or error page
	}

	// Set session timezone to match PHP timezone
	if (!$conn->query("SET time_zone = '+05:00'")) { // Replace +05:00 with your timezone offset
		// Failed to set timezone, log error. 
		// Depending on importance, you might want to die() here or handle it.
		error_log("Failed to set database session timezone: " . $conn->error, 3, "quiz_errors.log");
	}

	// Set session variables for timezone
	$_SESSION['timezone'] = 'Asia/Karachi';
	$_SESSION['timezone_offset'] = '+05:00';
?>