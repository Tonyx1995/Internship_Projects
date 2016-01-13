<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" href="styles/style.css" />

		<script>
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
				//Header
				echo"<h2>Add an item</h2>";
				echo"<form action='Food_inventory.php' method='post'>
						<input type='submit' value='Home' />
					</form>
				";
				echo"<hr />";
				//-------------------------------------------------------
				
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				//If they've pressed the Add button.
				if(isset($_POST['add'])){
					add_info();
				}
				//Displaying the form.
				display_food();
				//Validate input.
				validate();
				
				function display_food(){
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					$categoryresult = mysql_query($categorysql);
					
					
						echo "<form action='?action=submit' method='post' id='add_food_form'>
						<table border=1 class='full_input'>
							  <th>Name</th>
							  <th>Description</th>
							  <th>Price</th>
							  <th>Cyclone Card Item</th>
							  <th>Cyclone Card Price</th>
							  <th>Category</th>
						<tr>";
						echo"<td><input type='text' name='food_name'</td>";
						echo"<td><textarea name='food_description'></textarea></td>";
						echo"<td><input type='text' name='regular_price'</td>";
							echo"<td><input type='text' placeholder='Yes/No' name='cyclone_card'/></td>
								<td><input type='text' name='cyclone_price' placeholder='Amount or N/A'/></td>
							
							<td><select name='category_id'>
									<option value='' selected disabled>--</option>;
";
								//This is the section to display all food categories (distinct) and default the selection to what the current category is for the food.
											while($categoryrow = mysql_fetch_array($categoryresult)){
												echo "<option value='".$categoryrow['category_id']."'>".$categoryrow['category_description']."</option>";
											}
								//----------------------------------------------------------------------------------------------------------------------------------------
						echo    "</select></td>
						
						<tr>
							<th colspan=3>Sale Start Date</th>
							<th colspan=3>Sale End Date</th>
						</tr>
						<tr>
							<td colspan=3><input type='text' id='sale_start_date' name='sale_start_date' placeholder='Click to set date..' /></td>
							<td colspan=3><input type='text' id='sale_end_date' name='sale_end_date' placeholder='Click to set date..' /></td>
						</tr>
						
						</table>
						<br />";
						
/*Image upload in insert.
echo "<form method='post' action='add_food.php' enctype='multipart/form-data'>";
echo "<table border =0 style='margin: auto; text-align: center;'><tr>";
echo "<td>Select Picture:</td><td> <input type='file' name='filename' size='30' /></td></tr>";
echo "<tr><td colspan=2><input type='submit' value='Upload Picture'></td></tr>";
echo "</table>";
echo "</form>";*/					
						
						echo"<br /><input type='submit' value='Add' name='add' />";
						
					echo'</form>';
					
				}
				
				function add_info(){
					$food_name = $_POST['food_name'];
					$food_description = $_POST['food_description'];
					$regular_price = $_POST['regular_price'];
					$category_id = $_POST['category_id'];
					
					$cyclone_card = $_POST['cyclone_card'];
					if(ucfirst($cyclone_card) == "Yes"){
						$cyclone_card = 1;
					}else if(ucfirst($cyclone_card) == "No"){
						$cyclone_card = 0;
					}
					
					$sql = "INSERT INTO food SET
					food_name = '".$food_name."',
					food_description = '".$food_description."',
					regular_price = '".$regular_price."',
					cyclone_card_item = '".$cyclone_card."',
					category_id = '".$category_id."'";
					
					$result = mysql_query($sql);
					//header("LOCATION: food_inventory.php");
					
echo $sql;
				}
				
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("add_food_form");
							frmvalidator.addValidation("food_name","req","A name for the food must be filled out.");
							frmvalidator.addValidation("food_description","req","Must have a description for the food.");
							frmvalidator.addValidation("category_id","req","Must select a category for the food item.");
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