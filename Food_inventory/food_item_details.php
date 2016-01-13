<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="styles/style.css" />
	</head>
	<body>
		<div id="container">
			<?php
				include("db.php");
				
				echo "<h2>Details</h2>";
				echo"<form action='food_inventory_user.php' method='POST'>
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
				
				//Display Food.
				display_food($id);
				
				function display_food($id){
					
					$sql = "SELECT f.food_id, f.food_name, f.food_description, f.regular_price, f.sale_price, 
							f.sale_start_date, f.sale_end_date, f.category_id, pic, f.cyclone_card_item, f.cyclone_card_price, c.category_id, c.category_description
							FROM food f INNER JOIN category c ON c.category_id = f.category_id
							WHERE food_id = " . $id;
					$result = mysql_query($sql);
					//Query to get all unique category types to populate our drop-down list.
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					$categoryresult = mysql_query($categorysql);
						
					while($row = mysql_fetch_array($result)){						
						echo "
						<h2>" . $row['food_name'] . "</h2>
						<div class='center-div'>
							<div class='left-div'>";
								echo "<img src='Styles/pics/".$row['pic']."' height=200px width=200px alt='No picture found.'/>";
							echo "</div>
							<div class='right-div'>
								<p><strong>Description: </strong>".$row['food_description']."</p>
								<p><strong>Regular Price:</strong> $" . sprintf('%0.2f', $row['regular_price']) . " </p>";
								
								//If this item is cyclone card eligible.
								if($row['cyclone_card_item'] == "1"){
									echo"<p><strong>This item is cyclone card eligible; it's cyclone card price is:</strong> $" . sprintf('%0.2f', $row['cyclone_card_price']) . " </p>";
								}else{
									echo"<p><strong>This item is not cyclone card eligible</strong>.</p>";
								}
								
								echo"<p><strong>Sale Price:</strong> $" . sprintf('%0.2f', $row['sale_price']) . "</p>";
								echo"<p><strong>Sale Start Date:</strong>  " . format_date($row['sale_start_date']) . "</p>";
								echo"<p><strong>Sale End Date:</strong>  " . format_date($row['sale_end_date']) . "</p>";
								echo"<p><strong>Category:</strong>  " . $row['category_description'] . "</p>";
							echo"</div>";
						echo"</div>";
					}
					
				}
				
				
				function format_date($date){
					$formatted_date = '';
					$formatted_date = date('m/d/Y', strtotime($date));
					if($date == "1970-01-01"){
						return "Not on sale.";
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
					
			?>
			
		</div>
	</body>
</html>