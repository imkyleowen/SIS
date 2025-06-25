<html>
<head>
	<title>Add Data</title>

	<style>
html, body {
  height: 100%;
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background-color: #f0f4f8;
  display: flex;
  justify-content: center; 
  align-items: center;     
  text-align: center;
  flex-direction: column;  
  padding: 20px;
  box-sizing: border-box;
}

body > * {
  max-width: 600px;
  width: 100%;
  background: white;
  padding: 30px 40px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  margin-bottom: 20px;
  box-sizing: border-box;
}

.header {
  background-color: #3f51b5 !important;
  color: white !important;
  font-size: 2rem;
  font-weight: bold;
  padding: 20px 0;
  margin: 0 0 20px 0;
  border-radius: 10px 10px 0 0;
  box-shadow: none;
}

input[type="text"],
input[type="number"],
input[type="email"],
select {
  width: 80%;
  padding: 15px;
  margin: 10px 0 20px 0;
  font-size: 1.1rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  text-align: center;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

label {
  font-weight: bold;
  font-size: 1.2rem;
  margin-bottom: 5px;
  display: block;
}

.submit-btn {
  background-color: #3f51b5;
  color: white;
  padding: 15px 25px;
  font-size: 1.2rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
}

.submit-btn:hover {
  background-color: #303f9f;
}

.error {
  color: #d32f2f;
  font-weight: bold;
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.success {
  color: #388e3c;
  font-weight: bold;
  font-size: 1.2rem;
  margin-bottom: 10px;
}

a {
  color: #3f51b5;
  text-decoration: none;
  font-weight: bold;
  font-size: 1.1rem;
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



if (isset($_POST['submit'])) {
	// Escape special characters in string for use in SQL statement	
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
		// If all the fields are filled (not empty) 
		
		// Display success message
		echo "<p><font color='green'>Data added successfully!</p>";
		echo "<a href='index.php'>View Result</a>";
	}
}


$mysqli->query("INSERT INTO students (name, age, email, course) VALUES ('$name', $age, '$email', '$course')");


$inserted_id = $mysqli->insert_id;


$sid = "S2025-" . str_pad($inserted_id, 4, "0", STR_PAD_LEFT);


$mysqli->query("UPDATE students SET sid = '$sid' WHERE id = $inserted_id");
?>
</body>
</html>
