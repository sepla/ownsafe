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

if (isset($_SESSION['uid'])) {
	
		$uid = $_SESSION['uid'];		  
		$qry = "UPDATE ".$_SESSION['db_userTable']." SET session = 'logout_".date("Y-m-d H:i:s:").microtime()."', status='loggedout' WHERE userid = '".$uid."'";
		$res = $mysqli->query($qry);
		$output = array('status' => true, 'message' => 'ok');
		
} else $output = array('status' => false, 'message' => 'uiderror');

if ($_SESSION['language']) $language = $_SESSION['language'];

session_destroy();
session_unset();
$_SESSION = array();

session_start();
$_SESSION['language'] = $language;

?>
		<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
				<h3>OwnSafe</h3>
		</div>
		<div data-role="main" class="ui-content" data-theme="a" id="main">
				<h5 class="center">OwnSafe - logging out...</h5>
				<script type="text/javascript">
						sessionStorage.setItem("k",Math.random());
						var cc = sessionStorage.getItem("clearClipboard");
						sessionStorage.clear();
						try {clearInterval(counter);}
						catch (err) {}
						if (cc==="1") sessionStorage.setItem("clearClipboard","1");
				</script>
		</div>
		<div data-role="footer" data-position="fixed" data-theme="b" data-tap-toggle="false">
				<h5 class="center" id="footer_login_text">&nbsp;</h5>
		</div>


