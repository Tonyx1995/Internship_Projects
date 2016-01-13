
<!--
================================================
Author: Tony Reed
Date: 11/30/2015
Purpose: Web Form; administrator side
================================================
-->
<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="Styles/style.css" />
		<script type="text/javascript">
			function confirm_delete(){
				if(confirm("Are you sure you want to delete this food item?") == true){
					return true;
				}else{
					return false;
				}
			}
		</script>
	</head>
	<body>
		<div id="container">
			<?php
				include("db.php");
								
				echo"<h2>Food Catalog</h2>";
				//Search/categorizing
				sortBy();
				//Just displaying the add new food button.
				addItem();
				echo "<hr />";
				//Querying the database and displaying.
				getFoodItems();
				
				$id = NULL;
				$action = NULL;
				if(isset($_GET['id'])){
					$id = $_GET['id'];
				}
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				
				
				//If someone pressed delete and the url is set. 
				if($id && $action == "delete"){
					delete($id);
				}else if($id && $action == "edit"){
					header("LOCATION: edit_food.php?id=".$id);
				}
				
				
				function sortBy(){
					$sort_by = "";
					//This variable will hold category types and other various methods of sorting.
					if(isset($_GET['sort_by'])){
						$sort_by = $_GET['sort_by'];
					}
					
					//Query to get all unique category types
					$sql = "SELECT DISTINCT category_description, category_id FROM category";
					$result = mysql_query($sql);
//echo $sql;	
					
					echo "<div id='sort'>
							<table style = 'text-align: center'>
								<tr>
									<form action='Food_inventory.php' method='POST'>
										<td>Search for a food: <input type='text' name='search' id='search' /></td>
										<td><select>";
											while($row = mysql_fetch_array($result)){
												echo "<option value = '".$row['category_id']."'>".$row['category_description']."</option>";
											}
					echo               "</select></td>
										<td><input type='submit' value='Search' /></td>
										<td>
											<form action='Food_inventory.php' method='post'>
												<input type='submit' value='Show all food' />
											</form>
										</td>
								</tr>
									</form>
							</table>
						  </div>";
				}
				
				function delete($id){
					$sql = "DELETE FROM food WHERE food_id = " . $id;
					$result = mysql_query($sql);
					header("LOCATION: Food_inventory.php");
				}
				
				function getFoodItems(){
					$search = $category = "";
					if(isset($_POST['search'])){
						$search = $_POST['search'];
					}
					if(isset($_POST['category_description'])){
						$category = $_POST['category_description'];
					}
					
//echo "<h1>" . $search . "</h1>";
					$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
							f.category_id, f.pic, f.cyclone_card_item, c.category_description, c.category_id
					FROM food f
					INNER JOIN category c ON c.category_id = f.category_id
					ORDER BY food_name ASC";
					//If someone typed in a food item name to search for.
					if($search != ""){
						$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
								f.category_id, f.pic, f.cyclone_card_item, c.category_description, c.category_id
								FROM food f
								INNER JOIN category c ON c.category_id = f.category_id";
						$sql .= " WHERE food_name LIKE '%" . $search . "%'	";
						$sql .= "ORDER BY food_name ASC";
					//If someone has entered a food item name to search for and a food category.
					}else if($search != "" && $category != ""){
						$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
								f.category_id, f.pic, f.cyclone_card_item, c.category_description, c.category_id
								FROM food f
								INNER JOIN category c ON c.category_id = f.category_id";
						$sql .= " WHERE food_name LIKE '%" . $search . "%'	AND ";
						$sql .= "ORDER BY food_name ASC";
					}
					
//echo "<h2>".$sql."</h2>";

					$result = mysql_query($sql); 
					
					echo "<table border=1>
						<th>Preview</th>
						<th>Name</th>
						<th>Description</th>
						<th>Price</th>
						<th>Cyclone Card Item</th>
						<th>Edit</th>
						<th>Delete</th>
					";
					while($row = mysql_fetch_array($result)){
						echo "<tr>";
						echo "<td><img src='Styles/pics/".$row['pic']."' height=120px width=120px alt='No picture found.'/></td>";
						echo"<td>".$row['food_name']."</td>";
						echo"<td>".$row['food_description']."</td>";
						echo"<td>".sprintf('%0.2f', $row['regular_price'])."</td>";
						if($row['cyclone_card_item'] == "1"){
							echo"<td>Yes</td>";
						}else{
							echo"<td>No</td>";
						}						
						//Edit
						echo"<td><form action=food_inventory.php?action=edit&id=".$row['food_id']." method=POST>
							<input type='submit' value='Edit' />
							</form></td>";
							
						//Delete
						echo"<td><form onsubmit='return confirm_delete()' action=food_inventory.php?action=delete&id=".$row['food_id']." method='POST'>
							<input type='submit' value='Delete' />
							</form> </td>";
						echo"</tr>";
					}
				}
				
				function addItem(){
				 echo'<table>
					<tr>
						<form action="add_food.php" method="post">
							<input type="submit" value="Add new item" />
						</form>
					</tr>
					';
			
				}
			?>
		</div>
	</body>
</html>