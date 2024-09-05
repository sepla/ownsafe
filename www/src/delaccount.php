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

$uid = $_SESSION['uid'];

$qry = "DELETE FROM ".$_SESSION['db_recordsTable']." WHERE userid='".$_SESSION['uid']."'";
$res = $mysqli->query($qry);

$qry = "DELETE FROM ".$_SESSION['db_userTable']." WHERE userid='".$_SESSION['uid']."'";
$res = $mysqli->query($qry);

$qry = "DELETE FROM ".$_SESSION['db_loginTable']." WHERE userid='".$_SESSION['uid']."'";
$res = $mysqli->query($qry);

if ($res) $output = array('status' => true, 'message' => 'ok');
else $output = array('status' => false, 'message' => 'failure');

echo json_encode($output);

?>
