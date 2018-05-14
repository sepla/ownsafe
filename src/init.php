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

if (session_id() != "") session_destroy();
session_start();
define('__ROOT__', dirname(__FILE__).'/..');
require_once(__ROOT__.'/config/config.php');

// Create database
if (!$dbErrorString || strpos($dbErrorString, "DB connection failed")===FALSE) {
	if (!$mysqli->select_db ($_SESSION['db_name'])) {

		$sql = "CREATE DATABASE IF NOT EXISTS ".$_SESSION['db_name'];
		if ($mysqli->query($sql) === TRUE) {
			$dbErrorString = null;
		} else {
			$dbErrorString .= "Error creating database ".$_SESSION['db_name'].": " . $mysqli->error . "<br>";
		}
	}
}
// Select database
if (strpos($dbErrorString, "DB connection failed")===FALSE && !$mysqli->select_db ($_SESSION['db_name'])) {
	$dbErrorString .= "Unable to select DB ".$_SESSION['db_name'].": " . mysql_error() . "<br>";
	//exit;
} else if (!$dbErrorString) {
	$sql = "CREATE TABLE IF NOT EXISTS `".$_SESSION['db_userTable']."` (
		`userid` int(11) NOT NULL AUTO_INCREMENT,
		`username` varchar(100)  NOT NULL,
		`password` blob DEFAULT NULL,
		`email` varchar(100) DEFAULT NULL,
		`session` varchar(100) DEFAULT NULL,
		`lastlogin` datetime DEFAULT NULL,
		`lastfailedlogin` datetime DEFAULT NULL,
		`status` varchar(16) DEFAULT 'new',
		`logincounter` int(11) NOT NULL DEFAULT '0',
		`loginfailure` int(11) NOT NULL DEFAULT '0',
		`hint` blob DEFAULT NULL,
		`pepper` blob DEFAULT NULL,
		`logouttime` int(11) NOT NULL DEFAULT '99',
		`loginnotify` int(11) NOT NULL DEFAULT '3',
		`passgenlength` int(11) NOT NULL DEFAULT '16',
		`protocollength` int(11) NOT NULL DEFAULT '1',
		`ip` varchar(128)  DEFAULT NULL,
		`lastaction` datetime DEFAULT NULL,
		PRIMARY KEY (`userid`) )";

		if ($mysqli->query($sql) === TRUE) {
			//echo "Table ownsafe_user created successfully";
		} else {
			$dbErrorString .= "Error creating table ".$_SESSION['db_userTable'].": " . $mysqli->error . "<br>";
		}

	$sql = "CREATE TABLE IF NOT EXISTS `".$_SESSION['db_recordsTable']."` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`userid` int(11) NOT NULL,
		`title` varchar(256) NOT NULL,
		`user` varchar(128)  DEFAULT NULL,
		`password` varchar(128) DEFAULT NULL,
		`urlentry` text DEFAULT NULL,
		`note` text DEFAULT NULL,
		`iv` varchar(128) NOT NULL,
		PRIMARY KEY (`id`) )";

		if ($mysqli->query($sql) === TRUE) {
			//echo "Table ownsafe_records created successfully";
		} else {
			$dbErrorString .= "Error creating table ".$_SESSION['db_recordsTable'].": " . $mysqli->error ."<br>";
		}
	
	$sql = "CREATE TABLE IF NOT EXISTS `".$_SESSION['db_loginTable']."` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`userid` int(11) NOT NULL,
		`date` datetime DEFAULT NULL,
		`ip` varchar(64) NOT NULL,
		`useragent` text DEFAULT NULL,
		`status` varchar(16) DEFAULT NULL,
		`attempt` int(11) NOT NULL DEFAULT '0',
		`logincounter` int(11) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`) )";

		if ($mysqli->query($sql) === TRUE) {
			//echo "Table ownsafe_records created successfully";
		} else {
			$dbErrorString .= "Error creating table ".$_SESSION['db_loginTable'].": " . $mysqli->error ."<br>";
		}
	
}

$status = "OK";
if ($dbErrorString) $status = "ERROR";
$output = array('status' => $status, 'error' => $dbErrorString);     
echo json_encode($output);
 
?>
