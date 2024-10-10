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

if (isset($_REQUEST['usr'])) $username = $_REQUEST['usr']; else $username = null;
if (isset($_REQUEST['pwd'])) $password = hash ("sha256", $_REQUEST['pwd']); else $password = null;

$qry = "SELECT *,AES_DECRYPT(password, SHA2('".$_REQUEST['pwd']."',512)) as password,AES_DECRYPT(pepper, SHA2('".$_REQUEST['pwd']."',512)) as pepper FROM ".$_SESSION['db_userTable']." WHERE username='".$username."' AND AES_DECRYPT(password, SHA2('".$_REQUEST['pwd']."',512))='".$password."'";

$res = $mysqli->query($qry);

if ($res !== false) {
	$num_row 	= $res->num_rows;
	
	if( $num_row == 1 ) {
		$row 		= $res->fetch_assoc();
		$_SESSION['uid'] = $row['userid'];
		$_SESSION['uname'] = $row['username'];
		$logincounter = $row['logincounter'];
		$logincounter += 1;
		$loginfailure = $row['loginfailure'];
		$lastlogin = $row['lastlogin'];
		$lastfailedlogin = $row['lastfailedlogin'];
		$session = $row['session'];
		$status = $row['status'];
		$email = $row['email'];

		if ($loginfailure>19) $blocktime = 240;
		else if ($loginfailure>9) $blocktime = 60;
		else $blocktime = 15;
		
		if ($lastlogin== null || !$lastlogin) $lastlogin = "01.01.70 01:00";
        if ($lastfailedlogin== null || !$lastfailedlogin) $lastfailedlogin = "01.01.7>

		if ($status != "blocked") {

			if (!$lastlogin || $lastlogin == null) $lastlogin = "01.01.70 01:00";
			$lastlogin = date("d.m.y H:i", strtotime($lastlogin));
			if ($lastlogin == "01.01.70 01:00") $lastlogin = date("d.m.y H:i");
			$lastfailedlogin = date("d.m.y H:i", strtotime($lastfailedlogin));
			if ($lastfailedlogin == "01.01.70 01:00") $lastfailedlogin = "00.00.00 00:00";
			$status = "loggedin";
				
			$qry = "UPDATE ".$_SESSION['db_userTable']." SET session='".session_id()."', lastlogin='".date('Y-m-d H:i:s')."', logincounter='".$logincounter."', loginfailure='0', status='".$status."', ip='".getUserIP()."', lastaction='".date('Y-m-d H:i:s')."' WHERE userid='".$row['userid']."'";
			$res = $mysqli->query($qry);
			
			$output = array('status' => true, 'message' => 'OK', 
				'uname' => $row['username'], 
				'userid' => $row['userid'], 
				'upepper' => $row['pepper'], 
				'ulogouttime' => $row['logouttime'],
				'uloginnotify' => $row['loginnotify'],
				'upassgenlength' => $row['passgenlength'],
				'uprotocollength' => $row['protocollength'],
				'uloginfailure' => $loginfailure,
				'ulaststatus' => $row['status'],
				'usession' => $session,
				'ulastlogin' => $lastlogin,
				'ulastfailedlogin' => $lastfailedlogin);
				
		} else {

			$starttime = strtotime($lastfailedlogin);
      $endtime = strtotime(date("Y-m-d H:i:s"));
			$timediff = intval(($endtime - $starttime)/60);

		  $lastlogin = date("d.m.y H:i", strtotime($lastlogin));
			if ($lastlogin == "01.01.70 01:00") $lastlogin = date("d.m.y H:i");
					    
		    if ($timediff>$blocktime) {
					$status = "loggedin";
		  		$qry = "UPDATE ".$_SESSION['db_userTable']." SET session='".session_id()."', lastlogin='".date('Y-m-d H:i:s')."', logincounter='".$logincounter."', loginfailure='0', status='".$status."', ip='".getUserIP()."', lastaction='".date('Y-m-d H:i:s')."' WHERE userid='".$row['userid']."'";
					$res = $mysqli->query($qry);
				
					$output = array('status' => true, 'message' => 'OK', 
					'uname' => $row['username'], 
					'userid' => $row['userid'], 
					'upepper' => $row['pepper'], 
					'ulogouttime' => $row['logouttime'],
					'uloginnotify' => $row['loginnotify'],
					'upassgenlength' => $row['passgenlength'],
					'uprotocollength' => $row['protocollength'],
					'uloginfailure' => $loginfailure,
					'ulaststatus' => $row['status'],
					'usession' => $session,
					'ulastlogin' => $lastlogin,
					'ulastfailedlogin' => $lastfailedlogin);

			} else {
				$status = "blocked";
				$output = array('status' => true, 'message' => $status, 'timeleft' => ($blocktime-$timediff));
			}
		}

			if ($row['loginnotify']==1 || $row['loginnotify']==3) $loginemail="true";
			else $loginemail="false";


		if ($loginemail=="true")  {

						$subject = getTXT(507);
						$content = str_replace("[username]", $username, $email_successfullylogin_tmpl);
						$additionaltext = date('Y-m-d H:i:s').' - '.getUserIP().' - '.$_SERVER['HTTP_USER_AGENT'].' - '.getProtocolStatus($status).' - '.($loginfailure+1).' - '.$logincounter;
						$content = str_replace("[additionaltext]", $additionaltext, $content);
					
					sendMail($email,$subject,$content);
			}

		// update loginprotocoll
		$qry = "INSERT INTO ".$_SESSION['db_loginTable'].  " (userid, date, ip, useragent, status, attempt, logincounter) VALUES ('".$row['userid']."', '".date('Y-m-d H:i:s')."', '".getUserIP()."', '".$_SERVER['HTTP_USER_AGENT']."','".getProtocolStatus($status)."', '".($loginfailure+1)."', '".$logincounter."' )";
		$res = $mysqli->query($qry);

	} else {

		$qry = "SELECT userid,username,loginfailure,logincounter,email,loginnotify,status,lastfailedlogin FROM ".$_SESSION['db_userTable']." WHERE username='".$username."'";

		$res 		= $mysqli->query($qry);
		$num_row 	= $res->num_rows;

		
		if( $num_row == 1 ) {
			$row 		= $res->fetch_assoc();
			$_SESSION['uid'] = $row['userid'];
			$_SESSION['uname'] = $row['username'];
			$loginfailure = $row['loginfailure'];
			$current_loginfailure = ($loginfailure+1);
			$email = $row['email'];
			$loginnotify = $row['loginnotify'];
			$status = $row['status'];
			$newstatus = $status;
			$logincounter = $row['logincounter'];
			$lastfailedlogin = $row['lastfailedlogin'];
			
			if 		($current_loginfailure>19) 	$blocktime = 240;
			else if ($current_loginfailure>9) 	$blocktime = 60;
			else 								$blocktime = 15;
		
		
			if (!$lastfailedlogin) $lastfailedlogin = "00.00.00 00:00";
			
			$starttime = strtotime($lastfailedlogin);
			$endtime = strtotime(date("Y-m-d H:i:s"));
			$timediff = intval(($endtime - $starttime)/60);
			
			$lastfailedlogin = date("d.m.y H:i", strtotime($lastfailedlogin));
			if ($lastfailedlogin == "01.01.70 01:00") $lastfailedlogin = "00.00.00 00:00";

			if ($current_loginfailure > 4 && $status!="blocked") {
				$newstatus = "blocked";
				$qry = "UPDATE ".$_SESSION['db_userTable']." SET status='".$newstatus."' WHERE userid='".$row['userid']."'";
				$res = $mysqli->query($qry);
			
			} else if ($current_loginfailure <= 4) {
				$newstatus ="loginfailed";
			} 

			if ($newstatus == "blocked") {
				
				if ($timediff>$blocktime) {
					$qry = "UPDATE ".$_SESSION['db_userTable']." SET loginfailure='".$current_loginfailure."', lastfailedlogin='".date('Y-m-d H:i:s')."', status='".$newstatus."' WHERE userid='".$row['userid']."'";
					$res = $mysqli->query($qry);
			
					$output = array('status' => true, 'message' => 'login failed');
				
				} else {
					$output = array('status' => true, 'message' => 'blocked', 'timeleft' => ($blocktime-$timediff));
				}
	
			} else {
			
				$qry = "UPDATE ".$_SESSION['db_userTable']." SET loginfailure='".$current_loginfailure."', lastfailedlogin='".date('Y-m-d H:i:s')."', status='".$newstatus."' WHERE userid='".$row['userid']."'";
				$res = $mysqli->query($qry);
				
				$output = array('status' => true, 'message' => 'login failed');
			}

			if ($row['loginnotify']==2 || $row['loginnotify']==3) $loginfailemail="true";
			else $loginfailemail="false";

			if ( ($loginfailemail=="true" && $email!="" && $status!="blocked") || (($timediff>$blocktime !== false) && $status=="blocked") || ($status!="blocked" && $newstatus=="blocked") )  {

					$subject = getTXT(501);
					if ($newstatus!="blocked") {
						$content = str_replace("[username]", $username, $email_failedlogin_tmpl);
						$content = str_replace("[current_loginfailure]", $current_loginfailure, $content);
						$additionaltext = date('Y-m-d H:i:s').' - '.getUserIP().' - '.$_SERVER['HTTP_USER_AGENT'].' - '.getProtocolStatus($status).' - '.($loginfailure+1).' - '.$logincounter;
						$content = str_replace("[additionaltext]", $additionaltext, $content);

						
					} else {
						$content = str_replace("[username]", $username, $email_blockedlogin_tmpl);
						$content = str_replace("[current_loginfailure]", $current_loginfailure, $content);
						$content = str_replace("[blocktime]", $blocktime, $content);
						$additionaltext = date('Y-m-d H:i:s').' - '.getUserIP().' - '.$_SERVER['HTTP_USER_AGENT'].' - '.getProtocolStatus($status).' - '.($loginfailure+1).' - '.$logincounter;
						$content = str_replace("[additionaltext]", $additionaltext, $content);

					}	
					
					sendMail($email,$subject,$content);
			}

			// update loginprotocoll
			$qry = "INSERT INTO ".$_SESSION['db_loginTable']." (userid, date, ip, useragent, status, attempt, logincounter) VALUES ('".$row['userid']."', '".date('Y-m-d H:i:s')."', '".getUserIP()."', '".$_SERVER['HTTP_USER_AGENT']."','".getProtocolStatus($newstatus)."', '".$current_loginfailure."', '".$logincounter."')";
			$res = $mysqli->query($qry);

		} else {
			
			$output = array('status' => true, 'message' => 'login failed');
		}
		session_destroy();
	}

} else $output = array('status' => false, 'message' => 'db_error');

$qry = "DELETE FROM ".$_SESSION['db_loginTable']." WHERE date < (NOW() - INTERVAL 183 DAY)";
$res = $mysqli->query($qry);

echo json_encode($output);


function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function getProtocolStatus($status) {
	switch ($status) {
    case "loggedin":
        $status="OK";
        break;
    case "loginfailed":
        $status="FAILED";
        break;
    case "blocked":
        $status="BLOCKED";
        break;
	}
	return $status;	
}

?>
