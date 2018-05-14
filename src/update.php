<?php
/*
Copyright (C) 2015 Sebastian Plaza
This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied arranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program. 
If not, see http://www.gnu.org/licenses/. 
*/

session_start();
?>

<!DOCTYPE html> 
<html>
<head>
    <title>OwnSafe</title>
    <meta charset="utf-8">
    <meta name="robots" content="nofollow">
</head>
<body>
    <h2>OwnSafe</h2>
	
<?php
if (isset($_REQUEST['test'])) {
	error_reporting(E_ALL);
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	require_once('init.php');
    
    $sql = "ALTER TABLE ".$_SESSION['db_userTable']." ADD `protocollength` int(11) NOT NULL DEFAULT '1'";

	if ($mysqli->query($sql) === TRUE) {
		echo "Update OK."
	} else {
		$dbErrorString .= "Error creating table ".$_SESSION['db_userTable'].": " . $mysqli->error . "<br>";
		console.log($dbErrorString);
	}
}

?>
<br>
</body>
</html>
