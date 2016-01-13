<html>
	<head>
		<link rel="stylesheet" href="Styles/style.css" />
	</head>
	<body>
	<div id="container">
		<?php
			include("db.php");
			$id = $_GET['id'];
			$sql = "UPDATE food SET ";
			$sql .= "pic_id = " . $id;
			$sql .= " WHERE food_id = " . $id;
//echo $sql;
			$result = mysql_query($sql);
			
			display($id);
		
		function display($id){
			echo "<html><head><title>PHP Form Upload</title></head><body>";
			echo "<h2>Upload an image</h2>";
			echo "<form method='post' action='upload.php?id=".$id."' enctype='multipart/form-data'>";
			//echo "<form method='post' action='Food_inventory.php' enctype='multipart/form-data'>";
			// notice I am sending the post action to this same script
			// I could also send the post to a different script to do the uploading for me if I wanted to
			echo "<table border =0 style='margin: auto; text-align: center;'><tr>";
			echo "<td>Select File:</td><td> <input type='file' name='filename' size='30' /></td></tr>";

			//input type file will create a Browse button to search the users filesystem
			//when the user selects a file the path and name of the file will be in the filename textbox
			echo "<tr>";
			echo "<td colspan=2><input type='submit' value='Upload Picture'></td>";
			//creating a submit button to take the value in the special filename textbox and post it 

			//we could get any other form data we had, 
			//like textbox fields or select options if they were in the form

			echo "</table>";
			echo "</form>";
		}

		//$_FILES is a special superglobal variable built into PHP like $_POST or $_GET. 
		//It takes in any files sent
		// to a PHP script just as $_POST takes any post actions sent to a PHP script


		if ($_FILES){ // Check to see if the script has had any files posted to it
		//before the submit button is click there is nothing in $_FILES so this part doesn't happen
		
			//This is the literal filename. 
			$name = $_FILES['filename']['name'];
			
			
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["filename"]["name"]);
			$extension = end($temp);
			if ((($_FILES["filename"]["type"] == "image/gif")
			|| ($_FILES["filename"]["type"] == "image/jpeg")
			|| ($_FILES["filename"]["type"] == "image/jpg")
			|| ($_FILES["filename"]["type"] == "image/pjpeg")
			|| ($_FILES["filename"]["type"] == "image/x-png")
			|| ($_FILES["filename"]["type"] == "image/png"))
			&& ($_FILES["filename"]["size"] < 200000) //This file limit is 200,000 bytes, or 2 megabytes.
			&& in_array($extension, $allowedExts)){
				if ($_FILES["filename"]["error"] > 0){
					echo "Error: " . $_FILES["filename"]["error"] . "<br>";
				}
				else{
//echo "Upload: " . $_FILES["filename"]["name"] . "<br>";
//echo "Type: " . $_FILES["filename"]["type"] . "<br>";
//echo "Size: " . ($_FILES["filename"]["size"] / 1024) . " kB<br>";
//echo "Stored in: " . $_FILES["filename"]["tmp_name"];
					
					//If-else structure for what format to save our image in. Assigning the current ID in the url as file name.
					if($_FILES["filename"]["type"] == "image/jpeg"
					|| $_FILES["filename"]["type"] == "image/jpg"
					|| $_FILES["filename"]["type"] == "image/pjpeg"){
						$id_name = $id . ".jpg";
					}else if($_FILES["filename"]["type"] == "image/gif"){
						$id_name = $id . ".gif";
					}else if($_FILES["filename"]["type"] == "image/png"
					|| $_FILES["filename"]["type"] == "image/x-png"){
						$id_name = $id . ".png";
					}
					
					//SQL Update to send the image name and extension to the database.
					$sql = "UPDATE food SET 
					pic = '" . $id_name .
					"' WHERE food_id = " . $id;
					$result = mysql_query($sql);
//echo "<br />" . $sql;
//echo "<h2>Pic in database now equals ".$id_name." for item with id " . $id . "<h2>";
					header("LOCATION: Food_inventory.php");
				}
			}else{
					echo "Invalid file.";
				}
				
			
			
		//takes the file that has been uploaded and pull the name out of it, sets it to $name
		//we are pulling from the the input file "filename" that we created above	
			
			//Moving the directory of the file we just uploaded into our own custom path of stles/pics/
			move_uploaded_file($_FILES['filename']['tmp_name'], "styles/pics/" . $id_name);


		//moves the file from the tmp location which we get with ['tmp_name']
		//to this *same directory* we are in and names it the value of $name

		//if we wanted it somewhere else we put the directory path in front of the name
		//for example move_uploaded_file($_FILES["item_photo"]["tmp_name"],"pics/" . $photo_name);
		//moves to a directory pics that is subdirectory of the current directory
		// that would be /html/scripts moving to /html/scripts/pics
		//or to move up in the directory path we would use .. for every directory we want to go down
		//for example if were in /html/scripts and we wanted to file to move to /html/pics we would do 
		//move_uploaded_file($_FILES["item_photo"]["tmp_name"],"../pics/" . $photo_name);

		//the directory we are moving it to needs to be writable. If on a unix server chmod 777.
			
		//convert the name to html format for dealing with spaces 
		//rawurlencode will replace spaces with %20
			$html_name = rawurlencode($name);
			$html_name = rawurlencode($id_name);

//echo "<h3>html_name->$html_name</h3>";
			
//echo "<h3>Uploaded image '$id_name'</h3><br/>";
		echo "<img src=styles/pics/$id_name />";
		//print out the name of the image and display it with the img tag	



		}


		//use the $_FILES['filename']['type'] to get the mime or internet media type
		//this will let you know what type of file has been uploaded
		//examples are application/pdf, image/jpeg, image/png, application/vnd.ms-excel
		//you should always check to make sure you have the correct type of file
		//before saving it somewhere on your server

		//Here is code that will only allow for graphic files to be uploaded
		/*

		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["filename"]["name"]);
		$extension = end($temp);
		if ((($_FILES["filename"]["type"] == "image/gif")
		|| ($_FILES["filename"]["type"] == "image/jpeg")
		|| ($_FILES["filename"]["type"] == "image/jpg")
		|| ($_FILES["filename"]["type"] == "image/pjpeg")
		|| ($_FILES["filename"]["type"] == "image/x-png")
		|| ($_FILES["filename"]["type"] == "image/png"))
		&& ($_FILES["filename"]["size"] < 20000)
		&& in_array($extension, $allowedExts))
		  {
		  if ($_FILES["filename"]["error"] > 0)
			{
			echo "Error: " . $_FILES["filename"]["error"] . "<br>";
			}
		  else
			{
			echo "Upload: " . $_FILES["filename"]["name"] . "<br>";
			echo "Type: " . $_FILES["filename"]["type"] . "<br>";
			echo "Size: " . ($_FILES["filename"]["size"] / 1024) . " kB<br>";
			echo "Stored in: " . $_FILES["filename"]["tmp_name"];
			}
		  }
		else
		  {
		  echo "Invalid file";
		  }
		*/

		//also, it is good practice to create your own name for a file as 
		//it would possible for someone to name a file in a way that could cause issues
		//or overwrite an existing file


		?>
			<br />
			<hr />
			<form action="Food_inventory.php" method="POST">
				<input type="submit" value="Home"/>
			</form>
		</div>
		
	</body>
</html>