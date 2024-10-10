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

if (isset($_REQUEST['email'])) $email = $_REQUEST['email']; else $email = null;

if ($email != null) {

	$qry = "SELECT email,userid FROM ".$_SESSION['db_userTable']." WHERE email='".$email."'";

	$res 		= $mysqli->query($qry);
	$num_row 	= $res->num_rows;

	if ($res !== false) {
        if ($num_row == 0) {
          $output = array('status' => true, 'message' => 'free');
        } else {
            $row            = $res->fetch_assoc();
            $userid = $row['userid'];
            if ($userid == $_SESSION['uid']) {
                $output = array('status' => true, 'message' => 'owner');
            } else {
                $output = array('status' => true, 'message' => 'eexists');
            }
        } 
    } else $output = array('status' => false, 'message' => 'db_error');

} else $output = array('status' => true, 'message' => 'nullemail');

   
echo json_encode($output);
 

?>
