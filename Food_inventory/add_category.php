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
				echo"<h2>Category Maintenance</h2>";
				echo"<form action='Food_inventory.php' method='post'>
						<input type='submit' value='Home' />
					</form>
				";
				echo"<form action='edit_category.php' method='post'>
						<input type='submit' value='Edit Categories' />
					</form>
				";
				echo"<hr />";
				//-------------------------------------------------------
				
				$action = NULL;
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				//If they've pressed the Add button.
				if(isset($_GET['add'])){
					add_info();
				}
				//Displaying the form.
				display_food();
				//Validate input.
				validate();
				
				function display_food(){
					
					echo"
						<h3>Add a Category</h3>
						<form action='add_category.php?add=true' method='POST' id='add_category_form'>
							<table style='margin: auto; text-align: center;'>
								<tr><td>Category Name: &nbsp <input type='text' name='category_description' id='category_description' /></td></tr>
								<tr><td><input type='submit' value='Add Category' /></td></tr>
							</table>
						</form>
					";
					
				}
				
				function add_info(){
					$categorysql = "SELECT DISTINCT category_description, category_id FROM category";
					$categoryresult = mysql_query($categorysql);
					
					$new_category_name = $_POST['category_description'];
					
					//Checking new input if it's already in the database.
					while($row = mysql_fetch_array($categoryresult)){
						//Case insensitive comparison using strcasecmp.
						if(strcasecmp($new_category_name, $row['category_description']) == 0){
							echo "
								<script>alert('Category name already exists.')</script>
							";
						}else{
							$sql = "INSERT INTO category SET";
							$sql .= " category_description = '".$new_category_name."'";						
						}
					}
					if(strlen($sql) > 1){
						$result = mysql_query($sql);
						header("LOCATION: edit_category.php");
echo $sql;						
					}
				}
				
				function validate(){
					echo'
						<script language = "javascript" type="text/javascript">
							var frmvalidator = new Validator("add_category_form");
							frmvalidator.addValidation("category_description","req","You must fill out a name for a category.");
						</script>
					';
				}
			?>
			
		</div>
	</body>
</html>