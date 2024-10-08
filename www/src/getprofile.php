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
$username = null;
$email = null;
$hint = null;


if ($_SESSION['uid']!=null) {
	$qry = "SELECT username,email,AES_DECRYPT(hint, SHA2(email,512)) as hint from ".$_SESSION['db_userTable']." WHERE userid='".$_SESSION['uid']."'";
	
	$res 		= $mysqli->query($qry);
	$num_row 	= $res->num_rows;

	if ($res!==false) {
		if ($num_row == 1) {
			$row 		= $res->fetch_assoc();
			$username = $row['username'];
			$email = $row['email'];
			$hint = $row['hint'];

		} else {
			$username =  null;
			$email = null;
			$hint = null;

		}
	
		$output = array('status' => true, 
			'message' => 'ok', 
			'username' => $username, 
			'email' => $email,
			'hint' => $hint);

	} else $output = array('status' => false, 'message' => 'db_error');
} else $output = array('status' => false, 'message' => 'noid');	



echo json_encode($output);
?>
