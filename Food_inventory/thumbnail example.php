		//This is thumbnail section -----------------------------------------------------------------------------------------------
		$picture = NULL;
		if (ISSET($row['image'])){ // If statement to only display and make thumbnails for those pigs that have one in the Database.

			$picture = $row['image']; // Grabbing the image name to display
			//echo "picture: " . $picture;
			
			$im =''; // creating an empty variable to hold my image information
			$file = "pics/".$picture; // this is the image I want to work with
			
		//	echo "<h3>file ->$file</h3>";	
			
			$im = imagecreatefromjpeg($file); // this gets all the image information, all my image are jpegs - see below for handling other image types
		//	echo "<h3>im ->$im</h3>";
			
			$w = imagesx($im); // getting the width in pixels
			$h = imagesy($im); // getting the height in pixels
			
			$tw=$w; // setting a variable for original total width because I might change the value of width later on
			$th=$h; // setting a variable for original total height because I might change the value of height later on

			
			if($w > 60){  // if the image is more than 380 pixels I want to create thumbnail that is 380 pixels in width maximum. 
				$ratio = 60 / $w; // creating a ration so the image is reduced by the same percentage width and height
				$th = $h * $ratio; // set the height
				$tw = $w * $ratio; // set the width
			}
			else if($h > 80){ // same as above but with height. Will happen if image is less than 380 pixels in width, but more than 280 height
				$ratio = 80 / $h; // get ratio for scaling image
				$th = $h * $ratio;
				$tw = $w * $ratio;
			}
			echo '<td><img src ="pics/'.$picture .'" height ="'.$th.'" width ="'.$tw  .'"></td>'; // displaying the thumbnail
		}
		//---------------------------------------------------------------------------------------------------------------------------