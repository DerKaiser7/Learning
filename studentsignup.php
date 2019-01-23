<?php

if (isset($_POST['submit'])) {
	
	include_once 'includes/students.inc.php';

	$first = mysqli_real_escape_string($conn, $_POST['first']);
	$last = mysqli_real_escape_string($conn, $_POST['last']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
	$mtn = mysqli_real_escape_string($conn, $_POST['mtn']);

	//error handlers
	//check for empty fields
	if (empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd) || empty($mtn)) {
		header("Location: ../student.php?signup=empty");
		exit();
	} else{
		//check if input characters are valid
		if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)) {
			header("Location: ../student.php?signup=invalid");
			exit();
		} else {
			//check if email is valid
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				header("Location: ../student.php?signup=invalidemail");
				exit();			
			} else {
			$sql = "SELECT * FROM users WHERE user_uid='$uid'";
			$result = mysqli_query($conn, $sql);
			$resultcheck = mysqli_num_rows($result);

			if ($resultcheck > 0) {
				header("Location: ../student.php?signup=usertaken");
				exit();	
			} else{
				//hashing the password
				$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
				//Insert the user into the database
				$sql = "INSERT INTO students (user_first, user_last, user_email, user_uid, user_pwd, user_mtn) VALUES ('$first', '$last', '$email', '$uid', '$hashedpwd', '$mtn');";
				mysqli_query($conn, $sql);
				header("Location: ../student.php?signup=success");
				exit();
			}
		}
	}
 }
} else{
	header("Location: ../student.php");
	exit();
}