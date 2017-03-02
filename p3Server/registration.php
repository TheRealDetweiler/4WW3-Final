

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jscript/script.js"></script> <!-- link to script file-->

<?php 
	session_start(); //session start
	if(isset($_SESSION['user'])){
		echo "<div id='loginnotif'>";
		print_r($_SESSION['user']);
		echo "</div>";
	}
	function submit_new_user($first_, $last_, $email_, $pass_,$confpass_){ //function
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', '');
		if (validation()) { //for validation on the server
			try {
				$statement_insert = $pdo->prepare("INSERT INTO users (id, email, pass, givename, surname) VALUES (?,?,?,?,?)"); //prepared statement to be inserted into the users table
				$statement_insert->execute(array(NULL, $email_, $pass_, $first_, $last_));
				$pdo = NULL;
				echo '<script>; alert("Submission Successfully Accepted!"); </script>';
			} catch (PDOException $e) {
				echo $e->getMessage();
				$error_ = $statement_insert->errorInfo();
				print_r($error_);
				$pdo = NULL;
				echo '<script>; alert("Submission unsuccessful! "); </script>';
			}
		} else {
			echo '<script>; alert("Submission unsuccessful! "); </script>';
		}	
	}
	function validation(){ //validation function
		$errors = array();
		$val = (
		validatePattern($errors, $_POST, 'firstname', '/[a-zA-Z \\s]{2,}$/') ||
		validatePattern($errors, $_POST, 'lastname', '/[a-zA-Z \\s]{2,}$/') ||
		validatePattern($errors, $_POST, 'email', '/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/') ||
		validatePass($_POST, 'pass') ||
		confirmPass($_POST, 'pass', 'confpass')
		); //validation on the server for pattern matching
		return $val; //returns bool that 
	}
	function validatePattern(&$errors, $field_list, $field_name, $pattern){ //valid pattern function, test is name in list matches pattern
		if(!isset($field_list[$field_name]) || $field_list[$field_name] == ""){ 
			return false;
		} else if (!preg_match($pattern, $field_list[$field_name])) {
			return false;
		} else {
			return true;
		}
	}
	function validatePass($field_list, $field_name){
		if ((strlen($field_list[$field_name]) < 8) || ($pass_ == "")){ //ensures the password length is longer than 8 characters
				return false;
			}else{ //else returns false and displays the error message
				return true;
		}
	}
	function confirmPass($field_list, $field_name, $field_name2){
		if ($field_list[$field_name] == $field_list[$field_name2]){ //ensure both the password fields match
				return true;
		}else{
			return false;
		}
	} 
	if (isset($_POST["email"])){ //method for confirming the hashed password
		$first = $_POST["firstname"];
		$last = $_POST["lastname"];
		$email = $_POST["email"];
		$password = $_POST["pass"];
		$confpass = $_POST["confpass"];
		if(validation()){
			$password_hash = password_hash($password, PASSWORD_DEFAULT);
			submit_new_user($first,$last,$email,$password_hash, $confpass);

		} else {
			echo '<script>; alert("Submission unsuccessful! Error Code: Failed Server Side Validation!"); </script>';
		}
	}
	?>
	
<html>
	<head>
		
		
	</head>

	<body>
		<div id="wrapper"> <!--beginning of the body, use wrapper to conatian everything -->
			<?php include 'includes/banner.inc' ?>
			<?php include 'includes/menu.inc' ?>
			<?php include 'includes/hbar.inc' ?>
			
			<div class="multicontent">
				<div id="contentleft">
					<div id="registration"> <!-- multiple field registration, for fist name, last name, email, password, phone, postal code (if user doesnt wanna use the geo grab) and later i will impliment geograb -->
						<h1>
						Provide information below to register.
						</h1>
						
						<form action="registration.php" method="POST" onsubmit= "return validation();"> <!-- on post validate script is called to validate each field, if the criteria is met, the form returns true, if wront the sripts displays an error message and returns false, on attempting to change the field to the correct format the error message goes away until another submit.-->
							<span id="firstnameMissing"  style="color:red;font-weight:bold;visibility:hidden;font-size:14px;">First name Required! Must be longer than 2 characters.  </span><br> <!-- span to create error message -->
							First Name: 
		 		 			<input type="text" name="firstname" id="firstname" onkeypress="removeError('firstnameMissing');" ><br> <!-- remove error makes the message go away-->

		  					<span id="lastnameMissing" style="color:red;font-weight:bold;visibility:hidden;font-size:14px;"> Last name Required! Must be longer than 2 characters. </span><br>
		  					Last Name:
		  					<input type="text" name="lastname" id="lastname" onkeypress="removeError('lastnameMissing');"><br>
		  			
		  					<span id="emailMissing" style="color:red;font-weight:bold;visibility:hidden;font-size:14px;"> Email Address is Required!  </span><br>Email Addr:
		 		 			<input type="text" name="email"  id="email" onkeypress="removeError('emailMissing');"><br>

		 		 			
		 		 			<span id="passMissing" style="color:red;font-weight:bold;visibility:hidden;font-size:14px;"> Longer Password Required! </span><br>Password: 
		 		 			<input type="password" name="pass" id="pass" onkeypress="removeError('passMissing');"><br>

		 		 			<span id="confpassMissing" style="color:red;font-weight:bold;visibility:hidden;font-size:14px;">Password Confirmation Required! please match with above. </span><br>Confirm Password: 
		 		 			<input type="password" name="confpass" id="confpass" onkeypress="removeError('confpassMissing');"><br>
		  					<input type="submit" id="submit" name="regsub" value="Click here to Register!" ><br>
		  					<!-- <input type="button" id="reg" name="regsub" value="Click here to Register!" > -->
						</form>
					</div>
				</div>
				<div id="contentright" >
					<div id="login">
						<?php
							if(isset($_SESSION['user'])){ //addtional log in dialog same as longin.php, if session is active displat log out, else display regular log in.
								echo "<h1 style='text-align: center'> Already Logged In.</h1>";
								$user = $_SESSION['user'];
								echo "<p> You are currently logged in as $user <br> Click below to logout.</p>";
								echo 	"<form action='login.php' method='POST'>
											<input type='submit' name='logout' value='Click here to logout' >
										</form>";
							} else {
								echo "<h1> Already have an account? Log in here.</h1>  <!-- log in just in case user accidently clicked for convience -->";
								echo "<form action='login.php' method='POST'>
							
										Email Address:
					 		 			<input type='text' name='emailLogin' ><br>
					 		 			Password:
					  					<input type='Password' name='passLogin' ><br>
					  					<input type='submit' id='loginbtn' name='loginbtn' value='Login!'' >
									</form> ";
							}
						?>	
					</div>
				</div>
			</div>	<!-- multicontent ender -->
			<div class="footer" > <!-- footer -->
				TJ Walker 2016
			</div>

		</div>	
	</body>
</html>