<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<script language="JavaScript" type="text/javascript">
			//This jQuery function is to initialize the datepickers.
			$(function(){
				$( "#sale_start_date" ).datepicker();
				$( "#sale_end_date" ).datepicker();
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
					update_info($id);
				}
				
				//Display Food.
				display_food($id);
				//Validate input.
				validate();
				
				function display_food($id){
					
					$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, 
							f.sale_start_date, f.sale_end_date, f.category_id, pic, f.cyclone_card_item, f.cyclone_card_price, c.category_id, c.category_description
							FROM food f INNER JOIN category c ON c.category_id = f.category_id
							WHERE food_id = " . $id;
					$result = mysql_query($sql);
					//Query to get all unique category types to populate our drop-down list.
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					$categoryresult = mysql_query($categorysql);
						
					echo "<form action='?action=submit&id=".$id."' method='post' id='edit_food_form'>
						<table border=1 class='full_input'>
						<th>Image</th>
						<th>Name</th>
						<th>Description</th>
						<th>Price</th>
						<th>Cyclone Card Item</th>
						<th>Cyclone Card Price</th>
					";
					while($row = mysql_fetch_array($result)){
						echo "<tr>";
						echo "<td><img src='Styles/pics/".$row['pic']."' height=120px width=120px alt='No picture found.'/></td>";
						echo"<td><input type='text' name='food_name' value='".$row['food_name']."'</td>";
						echo"<td><textarea name='food_description'>".$row['food_description']."</textarea></td>";
						echo"<td><input type='text' name='regular_price' value='".sprintf('%0.2f', $row['regular_price'])."'</td>";
						if($row['cyclone_card_item'] == "1"){
							echo"<td><input type='text' value='Yes' name='cyclone_card' id='cyclone_card_item' /></td>";
						}else{
							echo"<td><input type='text' value='No' name='cyclone_card' id='cyclone_card_item' /></td>";
						}
						
						if(isset($row['cyclone_card_price'])){
							echo"<td><input type='text' name='cyclone_card_price' value='".sprintf('%0.2f', $row['cyclone_card_price'])."'/></td>";
						}else{
							echo"<td><input type='text' name='cyclone_card_price' value='N/A' /></td>";
						}
						
						//Second row
						echo "</td></tr>
							<tr>
								<th>Sale Price</th>
								<th colspan=2>Sale Start Date</th>
								<th colspan=2>Sale End Date</th>
								<th>Food Category</th>
							</tr>
							<tr>
								<td><input type='text' name='sale_price' value='".sprintf('%.2f', $row['sale_price'])."'</td>
								<td colspan=2><input type='text' name='sale_start_date' id='sale_start_date' value='".format_date($row['sale_start_date'])."'</td>
								<td colspan=2><input type='text' name='sale_end_date' id='sale_end_date' value='".format_date($row['sale_end_date'])."'</td>
								<td><select name='category_id'>
									<option selected='selected' value='".$row['category_id']."'>".$row['category_description']."</option>;
";
								//This is the section to display all food categories (distinct) and default the selection to what the current category is for the food.
											while($categoryrow = mysql_fetch_array($categoryresult)){
												echo "<option value='".$categoryrow['category_id']."'>".$categoryrow['category_description']."</option>";
											}
								//----------------------------------------------------------------------------------------------------------------------------------------
						echo    "</select></td>
								
								
							</tr>
						
						</table>
						<hr />
						<br />";
					}
						
					echo"	<input type='submit' value='Update' name='update' />
						</form>
						<form action='upload.php?id=".$id."' method='post'>
							<input type='submit' value='Upload image' />
						</form>";

				}
				
				function update_info($id){
					$food_name = $_POST['food_name'];
					$food_description = $_POST['food_description'];
					$regular_price = $_POST['regular_price'];
					$sale_price = $_POST['sale_price'];
					$category_id = $_POST['category_id'];
					
					$sale_start_date = $_POST['sale_start_date'];
					$sale_end_date = $_POST['sale_end_date'];
									
					$cyclone_card = $_POST['cyclone_card'];
					if(ucfirst($cyclone_card) == "Yes"){
						$cyclone_card = 1;
					}else if(ucfirst($cyclone_card) == "No"){
						$cyclone_card = 0;
					}
					$cyclone_card_price = $_POST['cyclone_card_price'];
					
					$sql = "UPDATE food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					cyclone_card_item = '".$cyclone_card."',
					cyclone_card_price = '".$cyclone_card_price."',
					sale_price = '".$sale_price."',
					sale_start_date = '".format_date_update($sale_start_date)."',
					sale_end_date = '".format_date_update($sale_end_date)."',
					category_id = '".$category_id."'
					WHERE
					food_id = " . $id;
					$result = mysql_query($sql);
					//header("LOCATION: food_inventory.php");
					
echo $sql;
				}
				
				function format_date($date){
					$formatted_date = '';
					$formatted_date = date('m/d/Y', strtotime($date));
					if($date == "1970-01-01"){
						return "";
					}else{
						return $formatted_date;	
					}
//echo "<h1>This is the date returned from user defined function: " . $formatted_date . "</h1>";
//echo "<h1>This is the date given to the function: " . $date . "</h1>";
				}
					
				function format_date_update($date){
						$formatted_date = '';
						$formatted_date = date('Y-m-d', strtotime($date));
//echo "<h2>".$formatted_date."</h2>";
						return $formatted_date;
					}
					
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("edit_food_form");
							frmvalidator.addValidation("food_name","req","A name for the food must be filled out.");
							frmvalidator.addValidation("food_description","req","Must have a description for the food.");
							
							//Logic for requiring cyclone price and having it be numeric only if the user has selected yes for cyclone item
							if(document.getElementById("cyclone_card_item").value == "Yes" || document.getElementById("cyclone_card_item").value == "yes"){
								frmvalidator.addValidation("cyclone_card_price","req","Cyclone Card price must be set.");
								frmvalidator.addValidation("cyclone_card_price","numeric","Cyclone Card price must be strictly numeric.");
							}
							
							//Logic for requiring sale price if the sale start and sale end date are set
							if(document.getElementById("sale_start_date").value != "" && document.getElementById("sale_end_date").value != ""){
								frmvalidator.addValidation("sale_price","req","Sale price must be set.");
								frmvalidator.addValidation("sale_price","numeric","Sale price must be strictly numeric.");
							}
							
							frmvalidator.addValidation("regular_price","req","Food price must be set.");
							frmvalidator.addValidation("regular_price","numeric","Food price must be strictly numeric.");
							frmvalidator.addValidation("sale_price","numeric","Sale price must be strictly numeric.");
						</script>
					';
				}
			?>
			
		</div>
	</body>
</html>