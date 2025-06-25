<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Student</title>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
      box-sizing: border-box;
      flex-direction: column;
    }

    font[color="red"] {
      color: #d32f2f !important;
      font-weight: bold;
      font-size: 1.2rem;
      display: block;
      margin-bottom: 10px;
    }

    font[color="green"] {
      color: #388e3c !important;
      font-weight: bold;
      font-size: 1.2rem;
      display: block;
      margin-bottom: 10px;
    }

    a {
      color: #3f51b5;
      text-decoration: none;
      font-weight: bold;
      font-size: 1.1rem;
      display: inline-block;
      margin-top: 15px;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<?php
// Include the database connection file
require_once("dbConnection.php");

if (isset($_POST['update'])) {
	// Escape special characters in a string for use in an SQL statement
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	$name = mysqli_real_escape_string($mysqli, $_POST['name']);
	$age = mysqli_real_escape_string($mysqli, $_POST['age']);
	$email = mysqli_real_escape_string($mysqli, $_POST['email']);	
	$course = mysqli_real_escape_string($mysqli, $_POST['course']);
	
	// Check for empty fields
	if (empty($name) || empty($age) || empty($email)) {
		if (empty($name)) {
			echo "<font color='red'>Name field is empty.</font><br/>";
		}
		
		if (empty($age)) {
			echo "<font color='red'>Age field is empty.</font><br/>";
		}
		
		if (empty($email)) {
			echo "<font color='red'>Email field is empty.</font><br/>";
		}
		if (empty($course)) {
			echo "<font color='red'>Course field is empty.</font><br/>";
		}
		// Show link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else {
		// Update the database table
		$result = mysqli_query($mysqli, "UPDATE students SET `name` = '$name', `age` = '$age', `email` = '$email', `course` = '$course' WHERE `id` = $id");
		
		// Display success message
		echo "<p><font color='green'>Data updated successfully!</font></p>";
		echo "<a href='index.php'>View Result</a>";
	}
}
?>
</body>
</html>
