




<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="style.css" />

<?php 
	
	use Aws\S3\Exception\S3Exception; //aws import

	require 'app/start.php'; //requirement for connecting to the bucket

	session_start(); //session start 
		if(isset($_SESSION['user'])){
			echo "<div id='loginnotif'>";
			print_r($_SESSION['user']);
			echo "</div>";
		}
	function submit_new_obj($name_, $desc_, $long_, $lat_) //function for submitting the new obj
	{
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', '');
		if (isset($_SESSION['user'])){
			try {
				$statement_insert = $pdo->prepare("INSERT INTO objects (id, name, description,longitude,latitude) VALUES (?,?,?,?,?)");//prepared statment to insert into objects
				$statement_insert->execute(array(NULL, $name_,$desc_,$long_,$lat_));
				$pdo = NULL;
				echo '<script>; alert("Submission Successfully Accepted!"); </script>';
				//redirect to submission page
			} catch (PDOException $e) {
				echo $e->getMessage();
				$error = $statement_insert->errorInfo();
				print_r($error);
				$pdo = NULL;
			}
		}else{
			echo '<script>; alert("You need to be logged in to complete this action."); </script>';
		}
	}
	function validatePattern(&$errors, $field_list, $field_name, $pattern){ //server side valid function
		if(!isset($field_list[$field_name]) || $field_list[$field_name] == ""){ 
			// $errors[$field_name] = 'Required';
			return false;
		} else if (!preg_match($pattern, $field_list[$field_name])) { //matches check
			//$errors[$field_name] = "Invalid";
			return false;
		} else {
			return true;
		}
	}

	function find_current_id(){ //return the id of the newly created obj
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', '');
		try {
			$current_id = $pdo->prepare("SELECT id FROM objects ORDER BY id DESC"); //prepare statemtn to get most recent id
			$current_id->execute();//execute
			return $current_id->fetch()['id'];
			$pdo = NULL;
			
		} catch (PDOException $e) {
			echo $e->getMessage();
			$error = $statement_insert->errorInfo();
			print_r($error);
			$pdo = NULL;
		}
	}

	function subValidate(){ //valid function number 2

		$errors = array();
		$val = (
		validatePattern($errors, $_POST, 'subname', '/[a-zA-Z \\s]{1,}$/') ||
		validatePattern($errors, $_POST, 'lat', '/[-+]?[0-9]*[.,]?[0-9]+$/') ||
		validatePattern($errors, $_POST, 'lon', '/[-+]?[0-9]*[.,]?[0-9]+$/') 
		); //valid patterns for each field
		return $val;
	}
	if (isset($_POST["subname"])){ //checks teh post to grab posted information for submission
		if (isset($_FILES['picUp'])){

		}
		$name = $_POST["subname"];
		$desc = $_POST["textSub"];
		$long = $_POST["lon"];
		$lat = $_POST["lat"];

		if (subValidate()){ //vlaidation call for serverside
			submit_new_obj($name,$desc,$long,$lat);
			$new_id = find_current_id();
			if (isset($_FILES['picUp'])){ //checks fro the image upload
	            $file = $_FILES['picUp'];

	            // file details
	            $nameF = $file['name']; //gets name
	            $tmp_name =  $file['tmp_name']; //gets temp name

	            $extension = explode('.', $nameF);
	            $extension = strtolower(end($extension)); //builds teh extentions and full temp name
	            // temp details

	            $key = md5(uniqid());
	            $tmp_file_name = "{$key}.{$extension}";
	            $tmp_file_path = "files/{$tmp_file_name}";
	            

	            // move teh file
	            move_uploaded_file($tmp_name, $tmp_file_path); //moves it to te upload directory
	            
	            try {
	            	//echo '<script>; alert("in the try"); </script>';
	            	$s3->putObject([
	            			'Bucket' => $config['s3']['bucket'],
	            			'Key' => "uploads/{$new_id}.{$extension}",
	            			'Body' => fopen($tmp_file_path, 'rb'),
	            			'ACL' => 'public-read'
	            		]); //connects to teh bucket adn transfers
	            	
	            	//remove the file
	            	unlink($tmp_file_path); 
	            } catch(S3Exception $e) {
	            	die("There was an issue with image server upload!");
	            }
	        }	
		} else {
			echo '<script>; alert("Failed server side validation!"); </script>';
		}
	}
	?>
<html>
	<head> <!--Title place holder for future developed Title and Logo -->
		<script type="text/javascript" src="jscript\script.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		
			
	</head>

	<body> <!--beginning of the body, use wrapper to conatian everything -->
		<div id="wrapper">
			<?php include 'includes/banner.inc' ?>
			<?php include 'includes/menu.inc' ?>
			<?php include 'includes/hbar.inc' ?>


			<div class="content"> <!--content tag, every thing between the head nad footer go into content -->
				<div class="submission"><!-- form for sumitting a bathroom location -->
					<h1>
					Provide information below to submit a Destination:
					</h1>
					<form id="regForm" action="submission.php" method="POST" enctype="multipart/form-data" > <!--refFrom is the form for registration, all fields are required or radio are prefilled in -->
						
						Name:
	 		 			<input type="text" id="subname" name="subname" pattern="[A-Za-z /s]{1,50}" required /><br> <!-- name pattern matched for a 2 length string-->
	  					Location Latitude:
	  					<input type="text" id="lat" name="lat" pattern="[-+]?[0-9]*[.,]?[0-9]+" required><br> <!-- name pattern matched for a int or float numbers for lat-->
	  					Location Longitude:
	  					<input type="text" id="lon" name="lon" pattern ="[-+]?[0-9]*[.,]?[0-9]+" required><br> <!-- name pattern matched for a int or float numbers for lon-->
	  					<input type="button" id="getLoc" name="getLoc" value="Get My Location!" onclick="getLocation();"><br><!--  Field that will polulate the sumbission coordinates of the object with the location of the computer or phone.-->

	  					<p>Note: all Long/Lat coordinates should be intergers or decimal numbers. <br></p>
	  					<p>Additionally, all image uploads must be of the .jpg form.</p>
	  					<input type="file" name="picUp" value="Click Here to Upload Picture"/ required><br>

	  					Description (max 500 characters):
	  					<br>
	  					<textarea id="textSub" name="textSub" ></textarea><br> <!-- additional text is optional -->
					
	  					<input type="submit" id="submitSub" name="submitSub" value="Click here to Submit!" >
					</form>
					
				</div>
			</div>
			
			<div class="footer" > <!-- footer -->
				TJ Walker 2016
			</div>

		</div>	
	</body>
</html>
