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
define('__ROOT__', dirname(__FILE__).'/..');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/src/check-session.php');  

$realname = null;
$email = null;
$hint = null;

if (isset($_POST['email'])) {
	$email = $_POST['email'];
	$uid = $_SESSION['uid'];

	$qry = "UPDATE ".$_SESSION['db_userTable']." SET email='".$email."', hint='' WHERE userid=".$uid;	
	if (isset($_POST['hint'])) {
		$hint = $_POST['hint'];
		$qry = "UPDATE ".$_SESSION['db_userTable']." SET email='".$email."', hint=AES_ENCRYPT('".$hint."', SHA2('".$email."',512)) WHERE userid=".$uid;
	}	
	$res = $mysqli->query($qry);
	
	if ($res!==false) $output = array('status' => true, 'message' => 'ok');
	else $output = array('status' => false, 'message' => 'DB'.$qry);
} else $output = array('status' => false, 'message' => 'noemail');


echo json_encode($output);
?>
