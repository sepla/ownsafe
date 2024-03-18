<?php
// Copyright (C) 2015 Sebastian Plaza
// This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License along with this program. 
// If not, see http://www.gnu.org/licenses/. 


// mySQL SB
$db_server 		= 'ownsafe_db';
$db_user 		= 'ownsafe';
$db_password 	= 'ownsafe';
$db_name		= 'ownsafe';

// Set to false if you do not want to be notified about unsecure connection
$show_nohttps_message = true;

// Key setting (ATTENTION: the higher the iterations value, the higher the key generation time. It depends on device performance)
$keySize 		= "512/32";
$iterations 	= "21";

//Mail settings
$server = $_SERVER['SERVER_NAME'];
$sender = "ownsafe@$server";


// ---------------------------------------------------------------------------
// Normally you don't need to do any changes below this line
// ---------------------------------------------------------------------------

// Default language - currently available values: EN, DE, PL
$language="DE";

// DB Settings
$db_userTable					= "ownsafe_user";
$db_recordsTable				= "ownsafe_records";
$db_loginTable				    = "ownsafe_login";
$_SESSION['db_userTable']		= $db_userTable;
$_SESSION['db_recordsTable']	= $db_recordsTable;
$_SESSION['db_loginTable']	    = $db_loginTable;

if (isset($_REQUEST['db_server'])) 		$db_server 		= $_SESSION['db_server'];
if (isset($_REQUEST['db_user'])) 		$db_user  		= $_SESSION['db_user'];
if (isset($_REQUEST['db_password'])) 	$db_password 	= $_SESSION['db_password'];
if (isset($_REQUEST['db_name'])) 		$db_name 		= $_SESSION['db_name'];

$_SESSION['db_server'] 		= $db_server;
$_SESSION['db_user'] 		= $db_user;
$_SESSION['db_password'] 	= $db_password;
$_SESSION['db_name'] 		= $db_name;


// Create DB connection
$mysqli = new mysqli($_SESSION['db_server'],$_SESSION['db_user'],$_SESSION['db_password']);


// Check DB connection
$dbErrorString = null;
if ($mysqli->connect_error) {
    $dbErrorString .= "DB connection failed: " . $mysqli->connect_error . "<br>";
} else if (!$mysqli->select_db ($_SESSION['db_name'])) {
		$dbErrorString .= "Unable to select DB ".$_SESSION['db_name']."<br>";
}


if ($show_nohttps_message) $_SESSION['show_nohttps_message'] = "1";
else $_SESSION['show_nohttps_message'] = "0";

if ($keySize!=null) $_SESSION['keySizeValue'] = $keySize;
else $_SESSION['keySizeValue'] = "512/32";

if ($iterations!=null) $_SESSION['iterationsValue'] = $iterations;
else $_SESSION['iterationsValue'] = "21";

if (isset($_SESSION['language'])) $language = $_SESSION['language'];
else $_SESSION['language'] = $language;
if (isset($_REQUEST['language'])) {
	$language = $_REQUEST['language'];
	$_SESSION['language'] = $language;
}

// load language specific text
$lines = file(__ROOT__.'/config/'.$language.'_language.txt');
$language_array = array();
foreach ($lines as $line) {
	$colonpos = strpos($line, ":");
    $position = substr($line, 0, $colonpos);
    $text = trim(substr($line, $colonpos+1, strlen($line)));
    $language_array["$position"]="$text";
}

function getTXT($pos) {
	global $language_array;
 	return $language_array[$pos];
}

function generatePepper() {
	// Character set for password generator
	$charListForUserSalt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789!@#$%*?=+-_,.;:";
	$userSaltLength = 16;

	$i = 0;
	$pepper = "";
	while ($i < $userSaltLength) {
		$pepper .= $charListForUserSalt[mt_rand(0, (strlen($charListForUserSalt) - 1))];
		$i++;
	}
	return $pepper;
}

// Email headers
function sendMail($recipient,$subject,$content) {
	global $server;
	global $sender;
	$header  = 'MIME-Version: 1.0'."\r\n";
	$header .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$header .= 'From: '.$sender."\r\n".'Reply-To: no-replay' . "\r\n".'X-Mailer: PHP/' . phpversion();
	mail($recipient, $subject, $content, $header);
}

// Email templates
$email_failedlogin_tmpl = '<html><head><title>'.getTXT(501).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(503).' [current_loginfailure] '.getTXT(504).'</h4><p><br>'.getTXT(508).'<br>[additionaltext]</p></body></html>';

$email_successfullylogin_tmpl = '<html><head><title>'.getTXT(507).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(509).'</h4><p><br>'.getTXT(508).'<br>[additionaltext]</p></body></html>';


$email_blockedlogin_tmpl = '<html><head><title>'.getTXT(501).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(503).' [current_loginfailure] '.getTXT(504).'</h4><p>'.getTXT(505).' [blocktime] '.getTXT(506).'</p><p><br>[additionaltext]</p></body></html>';

$email_passhint_tmpl = '<html><head><title>'.getTXT(510).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(511).'<br>'.getTXT(514).'<br> [hint] </h4><br></body></html>';

$email_loginchanged_tmpl = '<html><head><title>'.getTXT(512).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(513).'</h4><br></body></html>';


?>
