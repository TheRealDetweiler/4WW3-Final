

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jscript\script.js"></script>

<?php 
	session_start(); // session start for users account and login, when logged in in a current session the users mail appears on the top right of the screen.
	if(isset($_POST['logout'])){ //if the user presses teh log out button it destroys thier session
		session_unset();
		session_destroy();
	}
	if(isset($_SESSION['user'])){ //to start the session and print the email at the top of the screeen
		echo "<div id='loginnotif'>";
		print_r($_SESSION['user']);
		echo "</div>";
	}
	function login_user($userEmail, $password) //login function
	{
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', ''); //creates pdo connection
		try {
			$logIn = $pdo->prepare("SELECT * from users WHERE (email = ?)"); //prepares the statement to check if the account exists that was attempted to be logged into
			$logIn->execute(array($userEmail)); //executes the statement
			$res = $logIn->fetch();
			
			$password_check = password_verify($password, $res['pass']); //verifies the password provided to the stored hash
			if ($password_check == True){ // if they match alert the user and define the session variable
				$_SESSION['user'] = $res['email'];
				echo '<script>; alert("Log In was Successful"); </script>';
				$pdo = NULL;		
			}else{ //else notify to the user
			echo '<script>; alert("Log In was unsuccessful"); </script>';
		}
			$pdo = NULL;
		} catch (PDOException $e) { //error catch on the query
			echo $e->getMessage();
			$error = $statement_insert->errorInfo();
			print_r($error);
			$pdo = NULL;
		}
	};
	if (isset($_POST["emailLogin"])){
		login_user($_POST["emailLogin"],$_POST["passLogin"]); //setting globals to be access later after the login.
	}
	?>

<html>
	<head>
	</head>

	<body>
		<div id="wrapper"> <!--beginning of the body, use wrapper to conatian everything -->
			<?php include 'includes/banner.inc' ?>
			<?php include 'includes/menu.inc' ?> <!-- used to remove the duplicated code for my menus-->
			<?php include 'includes/hbar.inc' ?>
			<div class="content" > <!--log in using email and password, doesnt submit anywhere yet-->
				<div id="login">
					
					<?php // this is the control structure for changing hte login display, if logged it then display the logout button and mesage, else display the regular log in divs
					if(isset($_SESSION['user'])){
						echo "<h2 style='text-align: center'> Already Logged In.</h2>";
						$user = $_SESSION['user'];
						echo "<p> You are currently logged in as $user <br> Click below to logout.</p>";
						echo 	"<form action='login.php' method='POST'>
									<input type='submit' name='logout' value='Click here to logout' >
								</form>";
					} else {
						echo "<h2 style='text-align: center'> Log In Below.</h2>";
						echo "<form action='login.php' method='POST' >
						
							Email:
		 		 			<input type='text' name='emailLogin'  ><br>
		 		 			Password:
		  					<input type='Password' name='passLogin'  ><br>
		  					<input type='submit' id='loginbtn' name='loginbtn' value='Login!' >
		  					
						</form>";
					}
					?>
					
				</div>
			</div>
			<div class="footer" > <!-- footer -->
				TJ Walker 2016
			</div>

		</div>	
	</body>
</html>
