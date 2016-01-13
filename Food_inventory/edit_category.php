<html>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<link rel="stylesheet" href="styles/style.css" />
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
		<div id="edit_container">
			<?php
				include("db.php");
				//Header
				echo"<h2>Category Maintenance</h2>";
				echo"<form action='Food_inventory.php' method='post'>
						<input type='submit' value='Home' />
					</form>
				";
				echo"<form action='add_category.php' method='post'>
						<input type='submit' value='Add a Category' />
					</form>
				";
				echo"<hr />";
				//-------------------------------------------------------
				$id = NULL;
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				if(isset($_GET['id'])){
					$id = $_GET['id'];
				}
				//If someone pressed delete and the url is set. 
				if($id && $action == "delete"){
					delete($id);
				}
				
				//Displaying the form.
				display_food();
				
				function display_food(){
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					$categoryresult = mysql_query($categorysql);
					$numrows = mysql_num_rows($categoryresult);
					
					echo"
						<table border=1 style='width: 40%'>
							<tr>
								<th>Category ID</th>
								<th>Category description</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
					";
					
					while($row = mysql_fetch_array($categoryresult)){
						echo"<tr>";
						echo"<td>".$row['category_id']."</td>";
						echo"<td>".$row['category_description']."</td>";
						//Edit Item
						echo"<td><form action=edit_category.php?id=".$row['category_id']." method=POST>
							<input type='submit' value='Edit' />
							</form></td>";
								
						//Delete Item
						echo"<td><form onsubmit='return confirm_delete()' action='edit_category.php?action=delete&id=".$row['category_id']."' method='POST'>
							<input type='submit' value='Delete' />
							</form></td>";
						echo"</tr>";
					}
					
				}
				
				function delete($id){
					$sql = "DELETE FROM category WHERE category_id = " . $id;
					$result = mysql_query($sql);
					//header("LOCATION: edit_category.php");
echo $sql;
				}
			?>
			
		</div>
	</body>
</html>