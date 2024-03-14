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

$email = null;
$hint = null;
if (isset($_REQUEST['email'])) $email = $_REQUEST['email']; else $email = null;

$username = $_REQUEST['usr'];
$password = hash ("sha256", $_REQUEST['pwd'] );

$qry = "SELECT username FROM ".$_SESSION['db_userTable']." WHERE username='".$username."'";
$res 		= $mysqli->query($qry);

if ($res !== false) {
	$num_row 	= $res->num_rows;

	if( $num_row == 0 ) {

		if ($email != null) {
			$qry = "SELECT email FROM ".$_SESSION['db_userTable']." WHERE email='".$email."'";

			$res 		= $mysqli->query($qry);

			if ($res !== false) {
				$num_row 	= $res->num_rows;
				
				if( $num_row == 0 ) {
				
					$pepper =bin2hex(generatePepper());
					$qry = "INSERT INTO ".$_SESSION['db_userTable']." (username, password, session, email, hint, pepper, logouttime, loginnotify, passgenlength) VALUES ('$username', AES_ENCRYPT('".$password."', SHA2('".$_REQUEST['pwd']."',512)), 'logout', '$email', '$hint', AES_ENCRYPT('".$pepper."', SHA2('".$_REQUEST['pwd']."',512)), '99', '3', '16')";
					$res = $mysqli->query($qry);

					$output = array('status' => true, 'message' => 'OK');	
				
				} else $output = array('status' => true, 'message' => 'eexists', 'email' => $email);
				
			} else $output = array('status' => false, 'message' => 'db2_error');

		} else {

			$pepper =bin2hex(generatePepper());
			$qry = "INSERT INTO ".$_SESSION['db_userTable']." (username, password, session, email, hint, pepper, logouttime, loginnotify, passgenlength) VALUES ('$username', AES_ENCRYPT('".$password."', SHA2('".$_REQUEST['pwd']."',512)), 'logout', '$email', '$hint', AES_ENCRYPT('".$pepper."', SHA2('".$_REQUEST['pwd']."',512)), '99', '3', '16')";
			$res = $mysqli->query($qry);
			
			if ($res !== false) {
							$output = array('status' => true, 'message' => 'OK');	
			} else $output = array('status' => false, 'message' => 'db_error');

		}

	} else $output = array('status' => true, 'message' => 'uexists', 'user' => $username);


} else {
	$output = array('status' => false, 'message' => 'db2_error');
}
 
echo json_encode($output);
 
?>
