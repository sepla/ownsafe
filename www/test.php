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
if (!isset($_REQUEST['test'])) {
	echo '<p>This is the Server-URL for your OwnSafe-App<br>
	(without test.php; if you are connected to the same network)</p>
	<h4 id="jsurl"></h4>
	<script language="javascript">
	var url_text = window.location.href;	
	if (url_text.indexOf("/test.php") > -1) {
		url_text = url_text.replace("/test.php", "");
		//window.location.href = window.location.href.replace("test.php", "");
	}
	if (url_text.endsWith("/")) url_text = url_text.slice(0, -1);
	document.getElementById("jsurl").innerHTML = url_text;
	</script>
	<hr>
	';
}
	
echo '<p>Your PHP Version is ' . phpversion() .'</p>';

if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    echo '<p style="color:red">PHP: Mysqli is not installed :(<b>You need to install the PHP mysqli module!</p>';
} else {
    echo '<p>PHP: All right, mysqli ist installed :-)</p>';
}
?>
<hr>
<h4>Test your database settings:</h4>
<p>Clic on the button 'Test DB connection' to test your DB settings.</p>
<p>If you see this: {"status":"OK","error":null} it is all right :-), else you will see some error messeges.<br>The first error message is important mostly.</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="test" value="1"/>
    <input type="submit" value="Test DB connection and create DBs" name="form_submit" />
</form>
<br>
<?php
if (isset($_REQUEST['test'])) {
	error_reporting(E_ALL);
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	require_once(dirname(__FILE__).'/src/init.php');
	
}
?>

<br><hr/><br>	
<form action="./" method="post">
    <input type="submit" value="Start application" name="form_submit" />
</form>
<!--<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="update" value="1"/>
    <input type="submit" value="Update" name="form_submit" />
</form>
-->
<?php
if (isset($_REQUEST['update'])) {
	error_reporting(E_ALL);
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	require_once(dirname(__FILE__).'/src/init.php');
    
	$result = $mysqli->query("SHOW COLUMNS FROM ".$_SESSION['db_userTable']." LIKE 'protocollength'");
	if ($result!==FALSE) {
        $num_row = $result->num_rows;
        if ($num_row == 0) {
            $sql = "ALTER TABLE ".$_SESSION['db_userTable']." ADD `protocollength` int(11) NOT NULL DEFAULT '1'";

            if ($mysqli->query($sql) === TRUE) {
                echo "<br><br>Update 2 OK.";
            } else {
                $dbErrorString .= "Error creating table ".$_SESSION['db_userTable'].": " . $mysqli->error . "<br>";
                echo '<script>console.log("'.$dbErrorString.'")</script>';
                echo "<br><br>ERROR: ".$dbErrorString;
            }
        } else {
            echo "<br><br>Update 1 already implemented";
        }
    }
    $result = $mysqli->query("SHOW COLUMNS FROM ".$_SESSION['db_recordsTable']." LIKE 'counter'");
	if ($result!==FALSE) {
        $num_row = $result->num_rows;
        if ($num_row == 0) {
            $sql = "ALTER TABLE ".$_SESSION['db_recordsTable']." ADD `counter` INT NOT NULL AFTER `iv`;";

            if ($mysqli->query($sql) === TRUE) {
                echo "<br><br>Update 2 OK.";
            } else {
                $dbErrorString .= "Error creating table ".$_SESSION['db_recordsTable'].": " . $mysqli->error . "<br>";
                echo '<script>console.log("'.$dbErrorString.'")</script>';
                echo "<br><br>ERROR: ".$dbErrorString;
            }
        } else {
            echo "<br><br>Update 2 already implemented";
        }
    }
}


//ALTER TABLE `ownsafe_records` ADD `counter` INT NOT NULL AFTER `iv`;
?>
<br>
</body>
</html>
