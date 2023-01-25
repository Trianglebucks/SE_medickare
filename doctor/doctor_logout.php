<?php
	//Function to logout and redirect to index
	session_start();
	if (!isset($_SESSION['email']) && !isset($_SESSION['password']))
	{
		header('location: ../index.php');
	}
	else
	{
		session_destroy();
		header('location: doctor_login.php');
	}
?>