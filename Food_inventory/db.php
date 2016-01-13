<?php
	//Use "require once" on your actual php script OR "include('db.php');" is syntax to include this script
	$db_hostname ='localhost';
	$db_username ='root';
	$db_password ='';
	$db_name = "food_inventory";
	
	
	
	
	//For GoDaddy you need to change variables to match your login info.
	
	//If GoDaddy
	/*
	$db_hostname ='tonyre.db.11959136.hostedresource.com';
	$db_username ='tonyre';
	$db_password ='Sctcc!15';
	$db_name ='tonyre';
	*/
	

	

	
	$dbh = mysql_connect($db_hostname, $db_username, $db_password);

	mysql_select_db($db_name)
		or die("Unable to select database: ".mysql_error());
?>