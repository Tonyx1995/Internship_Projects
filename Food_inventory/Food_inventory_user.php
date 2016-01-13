
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
				echo"<h4>Click images to get more details.</h4>";
				
				//Search/categorizing
				sortBy();
				
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
				
				
				
				function sortBy(){
					$sort_by = "";
					//This variable will hold category types and other various methods of sorting.
					if(isset($_GET['sort_by'])){
						$sort_by = $_GET['sort_by'];
					}
					
					//Query to get all unique category types to populate our drop-down list.
					$sql = "SELECT DISTINCT category_description, category_id FROM category";
					$result = mysql_query($sql);
//echo $sql;	
					
					echo "<div id='sort'>
							<table style = 'text-align: center'>
								<tr>
									<form action='Food_inventory_user.php' method='POST'>
										<td>Food name: <input type='text' name='search' id='search' /></td>
										<td>Food type: <select name='category_description'>
											<option selected=selected>--</option>";
											while($row = mysql_fetch_array($result)){
												echo "<option value = '".$row['category_id']."'>".$row['category_description']."</option>";
											}
					echo               "</select></td>
										<td><input type='submit' name='search_button' value='Search' /></td>
										<td>
											<form action='Food_inventory_user.php' method='post'>
												<input type='submit' value='Show all food' />
											</form>
										</td>
								</tr>
									</form>
							</table>
						  </div>";
				}
				
				
				function getFoodItems(){
					$search = $category = $is_a_search = "";
					if(isset($_POST['search'])){
						$search = $_POST['search'];
					}
					if(isset($_POST['search_button'])){
						$is_a_search = $_POST['search_button'];
					}
					if(isset($_POST['category_description'])){
						$category = $_POST['category_description'];
					}
					

					
					
					//Has someone clicked the search button?
					if($is_a_search){
						//Decision-structure for our search options.
						if($search != "" && $category == "--"){ //If someone typed in a food item name to search for.
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
									f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
									FROM food f
									INNER JOIN category c ON c.category_id = f.category_id";
							$sql .= " WHERE food_name LIKE '%" . $search . "%'	";
							$sql .= "ORDER BY food_name";
						//If someone has entered a food item name to search for and a food category.
						}else if($search != "" && $category != "--"){
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
									f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
									FROM food f
									INNER JOIN category c ON c.category_id = f.category_id";
							$sql .= " WHERE food_name LIKE '%" . $search . "%'	AND c.category_id = '" . $category . "' ";
							$sql .= "ORDER BY food_name";
						}else if($search == "" && $category != "--"){
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
									f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
									FROM food f
									INNER JOIN category c ON c.category_id = f.category_id";
							$sql .= " WHERE c.category_id = '" . $category . "' ";
							$sql .= "ORDER BY food_name";	
						}else{
							$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
							f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
							FROM food f
							INNER JOIN category c ON c.category_id = f.category_id
							ORDER BY food_name";
						}
					}else{
						$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, f.sale_start_date, f.sale_end_date,
							f.category_id, f.pic, f.cyclone_card_item, f.cyclone_card_price, c.category_description, c.category_id
							FROM food f
							INNER JOIN category c ON c.category_id = f.category_id
							ORDER BY food_name";
					}

					$result = mysql_query($sql);
					$num_rows = mysql_num_rows($result);
					
					//If there is more than 1 result in the query.
					if($num_rows > 0){
						echo "<table border=1>
							<th>Preview</th>
							<th>Name</th>
							<th>Description</th>
							<th>Price</th>
							<th>Cyclone Card Price</th>
						";
						while($row = mysql_fetch_array($result)){
							//For calculating if today's date is within the sale range.
							$current_date = date("Y-m-d");
							$current_date = date("Y-m-d", strtotime($current_date));
							$sale_start = date("Y-m-d", strtotime($row['sale_start_date']));
							$sale_end = date("Y-m-d", strtotime($row['sale_end_date']));
							
							echo "<tr>";
							echo "<td><a href='food_item_details.php?id=".$row['food_id']."'><img src='Styles/pics/".$row['pic']."' height=120px width=120px alt='No picture found.'/><a/></td>";
							echo"<td>".$row['food_name']."</td>";
							echo"<td>".$row['food_description']."</td>";
							//If item is within sale range (inclusive)
							if(($current_date >= $sale_start) && ($current_date <= $sale_end)){
					//This example is without the sales icon thumbnail.
								//echo "<td style='font-weight: bold; color: red;'><s style='color: black; font-weight: normal;'>$".sprintf('%0.2f', $row['regular_price'])."</s>  $".sprintf('%0.2f', $row['sale_price'])."</td>";
					//This example includes a thumbnail; swap if needed.
								echo "<td style='font-weight: bold; color: red;'><img src='Styles/pics/sale.jpg' height='45px' width='45px' /><br /><s style='color: black; font-weight: normal;'>$".sprintf('%0.2f', $row['regular_price'])."</s> <br /> $".sprintf('%0.2f', $row['sale_price'])."</td>";
							}else{
								echo"<td>$".sprintf('%0.2f', $row['regular_price'])."</td>";
							}
							//If item is cyclone card applicable
							if($row['cyclone_card_item'] == "1"){
								echo"<td style='font-weight: bold;'><img src='Styles/pics/SCTCC_Logo.jpg' height='45px' width='45px' />$".sprintf('%0.2f', $row['cyclone_card_price'])."</td>";
							}else{
								echo"<td>N/A</td>";
							}						
						}
					}else{
						echo "<h3>No items to display. Try a different search.</h3>";
					}
				}
				
				
			
			?>
		</div>
	</body>
</html>