<?php
#header('Access-Control-Allow-Origin: *');
#header('Access-Control-Allow-Methods: GET, POST');
#header("Access-Control-Allow-Headers: X-Requested-With");
#header('Access-Control-Allow-Credentials: true');
#header('Access-Control-Allow-Headers: DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range');

// Copyright (C) 2015 Sebastian Plaza
// This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License along with this program. 
// If not, see http://www.gnu.org/licenses/. 


// mySQL SB
$db_server 		= 'ownsafe_db:3306';
$db_user 		= 'ownsafe';
$db_password 	= 'ownsafe';
$db_name		= 'ownsafe';

// Set to false if you do not want to be notified about unsecure connection
$show_nohttps_message = true;

// Key setting (ATTENTION: the higher the iterations value, the higher the key generation time. It depends on device performance)
$keySize 		= "512/32";
$iterations 	= "21";

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __ROOT__.'/PHPMailer/src/Exception.php';
require __ROOT__.'/PHPMailer/src/PHPMailer.php';
require __ROOT__.'/PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                   //Send using SMTP
    $mail->Host       = 'hostUrl';                     //Set the SMTP server to send through
    $mail->Username   = 'username';                    //SMTP username
    $mail->Password   = 'Pass1234';                    //SMTP password
    $mail->SMTPAuth   = true;                          //Enable SMTP authentication
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   //Enable implicit TLS encryption
    $mail->Port       = 465;                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mailFrom         = 'from@mymail';	
    $mailNoReply      = 'no-reply@mymail.com';
	#$mail->SMTPDebug = SMTP::DEBUG_SERVER;            //Enable verbose debug output
	
	// Validierung
	if (filter_var($mailFrom,    FILTER_VALIDATE_EMAIL)) $mail->setFrom($mailFrom, 'OwnSafe');
    if (filter_var($mailNoReply, FILTER_VALIDATE_EMAIL)) $mail->addReplyTo($mailNoReply, 'Information');
    if ($mail->Host=="hostUrl")  $_SESSION['mailHost'] = $mail->Host;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}



// ---------------------------------------------------------------------------
// ---------------------------------------------------------------------------
// Normally you don't need to do any changes below this line
// ---------------------------------------------------------------------------
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



$dbErrorString=FALSE;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
	// Create DB connection
	$mysqli = new mysqli($_SESSION['db_server'],$_SESSION['db_user'],$_SESSION['db_password']);
	// Check DB connection
	if ($mysqli->connect_error) {
		$dbErrorString .= "DB connection failed: " . $mysqli->connect_error . "<br>";
		echo $dbErrorString;
	} else if (!$mysqli->select_db ($_SESSION['db_name'])) {
		$sql = "CREATE DATABASE IF NOT EXISTS ".$_SESSION['db_name'];
		if ($mysqli->query($sql) === TRUE) {
			$dbErrorString=FALSE;
		} else {
			$dbErrorString .= "Unable to select DB ".$_SESSION['db_name']."<br>";
			echo $dbErrorString;
		}
	}
} catch (mysqli_sql_exception $e) {
	$dbErrorString .= "Unable to connect to DB server ".$_SESSION['db_server']."<br>";
	$dbErrorString .= "<br>".$e->getMessage()."<br>";
	echo $dbErrorString;
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

// POSTFIX Email headers
//function sendMail($recipient,$subject,$content) {
//	$server = $_SERVER['SERVER_NAME'];
//	$sender = "ownsafe@$server";
//	$header  = 'MIME-Version: 1.0'."\r\n";
//	$header .= 'Content-type: text/html; charset=utf-8'."\r\n";
//	$header .= 'From: '.$sender."\r\n".'Reply-To: no-replay' . "\r\n".'X-Mailer: PHP/' . phpversion();
//	mail($recipient, $subject, $content, $header);
//}

// Email headers
function sendMail($recipient,$subject,$content) {
	global $mail,$mysqli;
	if ($mail->Host!="hostUrl") { 
	    try {
		    if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
			    $mail->isHTML(true);               //Set email format to HTML
			    $mail->addAddress($recipient);     //Add a recipient, Name is optional	
	       	    $mail->Subject = $subject;
			    $mail->Body    = $content;
			    $mail->AltBody = $content;
			    $mail->send();
		    }
	    } catch (Exception $ex) {
		    $qry = "SELECT userid FROM ".$_SESSION['db_userTable']." WHERE email='".$recipient."'";
		    $res = $mysqli->query($qry);
		    if ($res !== false) {
			    $num_row 	= $res->num_rows;
			    if( $num_row == 1 ) {
				    $row 		= $res->fetch_assoc();
				    $userid = $row['userid'];
				    logError($userid,"MAIL ERROR",$ex);
			    }
		    } 
	    }
	}
}

// Email templates
$email_failedlogin_tmpl = '<html><head><title>'.getTXT(501).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(503).' [current_loginfailure] '.getTXT(504).'</h4><p><br>'.getTXT(508).'<br>[additionaltext]</p></body></html>';

$email_successfullylogin_tmpl = '<html><head><title>'.getTXT(507).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(509).'</h4><p><br>'.getTXT(508).'<br>[additionaltext]</p></body></html>';


$email_blockedlogin_tmpl = '<html><head><title>'.getTXT(501).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(503).' [current_loginfailure] '.getTXT(504).'</h4><p>'.getTXT(505).' [blocktime] '.getTXT(506).'</p><p><br>[additionaltext]</p></body></html>';

$email_passhint_tmpl = '<html><head><title>'.getTXT(510).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(511).'<br>'.getTXT(514).'<br> [hint] </h4><br></body></html>';

$email_loginchanged_tmpl = '<html><head><title>'.getTXT(512).'</title></head><body><p>'.getTXT(500).' [username]</p><p>'.getTXT(502).'<p><h4>'.getTXT(513).'</h4><br></body></html>';


function logError($userid, $status, $errorMessage) {
global $mysqli;
	$errorMessage = str_replace(["\r\n", "\n", "\r"],'',$errorMessage);
		$qry = "INSERT INTO ".$_SESSION['db_loginTable']."(userid, date, ip, useragent, status, attempt, logincounter) VALUES ('".$userid."', '".date('Y-m-d H:i:s')."', '".getUserIP()."', '".substr($errorMessage, 0, 255)."','".$status."', '0', '0' )";
		$res = $mysqli->query($qry);
}

?>
