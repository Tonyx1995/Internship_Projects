<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="styles/style.css" />
		<script>
			//This jQuery function is to initialize the datepickers.
			$(function(){
				//$( "#pickup_date" ).datepicker();
				//$( "#return_date" ).datepicker();
			})
		</script>
	</head>
	<body>
		<div id="edit_container">
			<?php
				include("db.php");
				
				echo"<h2>Edit item</h2>";
				echo"<form action='food_inventory.php' method='POST'>
						<input type='submit' value='Home' />
					</form>
					<hr />
				";
				
				$id = NULL;
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['id'];
				}
				if(isset($_GET['id'])){
					$id = $_GET['id'];	
				}
				
				if(isset($_POST['update'])){
					//May need to add more fields to function.
					update_info($id);
				}
				
				display_food($id);
				
				function display_food($id){
					
					$sql = "SELECT food_id, food_name, food_description, regular_price, sale_price, sale_start_date, sale_end_date, category_id, pic_id, cyclone_card_item
							FROM food
							WHERE food_id = " . $id;
					$result = mysql_query($sql);
						
					echo "<form action='?action=submit&id=".$id."' method='post'>
						<table border=1>
						<th>Image</th>
						<th>Name</th>
						<th>Description</th>
						<th>Price</th>
						<th>Cyclone Card Item</th>
						<th>Update</th>
						<th>Upload</th>
					";
					while($row = mysql_fetch_array($result)){
						echo "<tr>";
						echo "<td><img src='Styles/pics/".$row['pic_id'].".jpg' height=120px width=120px alt='No picture found.'/></td>";
						echo"<td><input type='text' name='food_name' value='".$row['food_name']."'</td>";
						echo"<td><textarea name='food_description'>".$row['food_description']."</textarea></td>";
						echo"<td><input type='text' name='regular_price' value='".sprintf('%0.2f', $row['regular_price'])."'</td>";
						/*if($row['sale_price'] == ""){
							echo"<td>N/A</td>";
						}else{
							echo"<td>".$row['sale_price']."</td>";
						}
						/*if($row['sale_start_date'] == ""){
							echo"<td>N/A</td>";
						}else{
							echo"<td>".$row['sale_start_date']."</td>";
						}
						if($row['sale_end_date'] == ""){
							echo"<td>N/A</td>";
						}else{
							echo"<td>".$row['sale_end_date']."</td>";
						}*/
						if($row['cyclone_card_item'] == "1"){
							echo"<td><input type='text' value='Yes' name='cyclone_card'/></td>";
						}else{
							echo"<td><input type='text' value='No' name='cyclone_card'/></td>";
						}
						echo"<td><input type='submit' value='Update' name='update' /></td>";

					}
					echo'</form>';
					echo "<td><form action='upload.php?id=".$id."' method='post'>
						<input type='submit' value='Upload image' />
						</form>
						</td>";
				}
				
				function update_info($id){
					$food_name = $_POST['food_name'];
					$food_description = $_POST['food_description'];
					$regular_price = $_POST['regular_price'];
					
					$cyclone_card = $_POST['cyclone_card'];
					if(ucfirst($cyclone_card) == "Yes"){
						$cyclone_card = 1;
					}else if(ucfirst($cyclone_card) == "No"){
						$cyclone_card = 0;
					}
					
					$sql = "UPDATE food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					cyclone_card_item = '".$cyclone_card."'
					WHERE
					food_id = " . $id;
					$result = mysql_query($sql);
					header("LOCATION: food_inventory.php");
					
//echo $sql;
				}
			?>
			
		</div>
	</body>
</html>