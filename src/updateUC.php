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

$uid = $_SESSION['uid'];
$password = hash ( "sha256" , $_REQUEST['pwd'] );
$username = $_REQUEST['usr'];


$qry = "SELECT username FROM ".$_SESSION['db_userTable']." WHERE username='".$username."'";

$res 		= $mysqli->query($qry);
$num_row 	= $res->num_rows;

if ($username != null) {
	if ($res !== false) {
		if( $num_row == 0 ) {

			$pepper =bin2hex(generatePepper());
			$qry = "UPDATE ".$_SESSION['db_userTable']." SET password=AES_ENCRYPT('".$password."', SHA2('".$_REQUEST['pwd']."',512)), pepper=AES_ENCRYPT('".$pepper."', SHA2('".$_REQUEST['pwd']."',512)), username='".$username."' WHERE userid=".$uid;
			$res = $mysqli->query($qry);

			if ($res !== false)	{

				$qry = "SELECT email,AES_DECRYPT(pepper, SHA2('".$_REQUEST['pwd']."',512)) as pepper FROM ".$_SESSION['db_userTable']." WHERE username='".$username."' AND AES_DECRYPT(password, SHA2('".$_REQUEST['pwd']."',512))='".$password."'";
				$res = $mysqli->query($qry);

				if ($res !== false) {
		
					$num_row = $res->num_rows;

					if ($num_row == 1) {
						$row  = $res->fetch_assoc();
						$email = $row['email'];
						$pepper = $row['pepper'];
						
						$output = array('status' => true, 'message' => 'ok', 'upepper' => $pepper, 'uname' => $username);

						$subject = getTXT(512);
						$content = str_replace("[username]", $username, $email_loginchanged_tmpl);
						sendMail($email,$subject,$content);
			
					}  else $output = array('status' => false, 'message' => 'error (user #'.$num_row.')'); 
				} else $output = array('status' => false, 'message' => 'db error (select)'); 
			} else $output = array('status' => false, 'message' => 'db error (update)');
		} else $output = array('status' => false, 'message' => 'uexists');
	} else $output = array('status' => false, 'message' => 'db_error (check username)');
} else $output = array('status' => false, 'message' => 'nouser');

echo json_encode($output);
 
?>
