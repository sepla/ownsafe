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

if ($dbErrorString){	
echo '<script>
						console.log("'.$dbErrorString.'");
            $("#error").html("<p><div>Connection error:</div>'.$dbErrorString.'</p><p>Test connection: <a onclick=\"window.location.assign(sessionStorage.getItem(\'OwnSafeServerUrl\')+\'/test.php\')\" style=\"cursor:pointer;\">TEST</a></p>");
    </script>';
}

if ($_SESSION['show_nohttps_message'] == "1") echo '<script>sessionStorage.setItem("showNoHttpsMessage",1);</script>';
echo '<script>sessionStorage.setItem("keySizeValue", "'.$_SESSION['keySizeValue'].'");sessionStorage.setItem("iterationsValue", "'.$_SESSION['iterationsValue'].'");</script>';

?> 

<!-- ----- login - login ----- -->
    <div data-role="panel" class="ui-panel" id="loginPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
			<?php include('page_loginpanel.php'); ?>
		</div> 

		<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
			<a href="#loginPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
			<h3><?php echo getTXT(102); ?></h3>
			<a data-theme="b" class="ui-btn ui-btn-right ui-corner-all buttonStyle" name="log_submit" id="log_submit" value="<?php echo getTXT(103); ?>" data-role="button"  tabindex="3" ><?php echo getTXT(103); ?></a>
		</div>
		

		<div data-role="main" class="ui-content" data-theme="a" id="main">
		<div id="error" class="dbErrorDescription"></div>
			<form id="check-user" class="ui-body ui-body-a ui-corner-all" data-ajax="true">
					<p class="ui-btn-icon-top ui-icon-user titleText"><?php echo getTXT(104); ?></p>
					<fieldset>
						<label for="log_username" class="smallerFont"><?php echo getTXT(105); ?></label>
							<input type="text" value="" name="log_username" id="log_username" placeholder="<?php echo getTXT(105); ?>" tabindex="1" autofocus>
							<label for="log_password" class="smallerFont"><?php echo getTXT(106); ?></label>
							<input type="password" value="" name="log_password" id="log_password" placeholder="<?php echo getTXT(106); ?>" tabindex="2">
					</fieldset>
			</form> 
				
			<div data-role="popup" id="log_loginFailed" class="ui-content" data-overlay-theme="b"  data-theme="a">
				<a class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(108); ?></h3>
				<p class="popupTextBottom"><?php echo getTXT(109); ?></p>
				<a href="#" class="emailHintPopupButton ui-btn ui-mini ui-corner-all ui-btn-inline" name="log_sendhint" id="log_sendhint"><?php echo getTXT(110); ?></a>
				<div class="positionClosePopupButton">
						<a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a>
				</div>
			</div>
								
			<div data-role="popup" id="log_loginFailed_email" class="ui-content" data-overlay-theme="b" data-dismissible="false" data-theme="a">
				<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left "><?php echo getTXT(107); ?></a>
				<label for="log_sendhint_email" class="smallerFont"><?php echo getTXT(111); ?></label>
				<input type="email" name="log_sendhint_email" id="log_sendhint_email" placeholder="<?php echo getTXT(111); ?>" />
				<div class="positionClosePopupButton">
					<a data-role="button" class="ui-btn ui-corner-all ui-shadow"  data-inline="true" data-mini="true" id="log_sendhint_sendbutton" title="<?php echo getTXT(112); ?>"><?php echo getTXT(112); ?></a>
				</div>
			</div>		
			
			<div data-role="popup" id="log_loginFailed_nohint" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left smallerPopup popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(113); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>

			<div data-role="popup" id="log_loginFailed_noemail" class="ui-content" data-overlay-theme="b"  data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(114); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>

			<div data-role="popup" id="log_loginFailed_emailok" class="ui-content" data-overlay-theme="b"  data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-check ui-btn-icon-notext ui-btn-left smallerPopup  popupOkIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(115); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_loginFailed_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(116); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_fillFields" class="ui-content" data-overlay-theme="b"  data-theme="a">
				<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon "><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(117); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_logoutHint" class="ui-content smallerPopup" data-overlay-theme="b"  data-theme="a">
				<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(118); ?></h3>
				<p class="popupTextBottom"><?php echo getTXT(119); ?></p>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<div name="error_content" id="error_content">
					<h3><?php echo getTXT(120); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_emailFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(151); ?></h3>
						<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_loginBlocked" data-overlay-theme="b" data-theme="a" class="ui-corner-all smallerPopup">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
	    		<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
						<h3 class="ui-title"><?php echo getTXT(122); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(123); ?><br><?php echo getTXT(124); ?>&nbsp;<span id="log_blockedtime"></span>&nbsp;<?php echo getTXT(125); ?></p>
		      </div>
		      <div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="log_keygen" class="ui-content ui-corner-all smallerPopup" data-overlay-theme="b"  data-theme="a">
				<p class="waitForKeyGeneration"><?php echo getTXT(330); ?></p>
				<div id="log_keygen_progress" class="smallerFont"><?php echo getTXT(331); ?></div>
			</div>
			

			<p id="login_clearclipboard" 	class="center" hidden><a data-role="button" data-inline="true" data-mini="true" class="clearclipboardButton" data-theme="a"><?php echo getTXT(133); ?></a></p>
			<p id="log_logoutmessage" 		class="description" hidden><?php echo getTXT(134); ?></p>
			<p id="log_insecurelogin" 		class="warningDescription" hidden><?php echo getTXT(135); ?><br><?php echo getTXT(136); ?></p>
			<p id="log_exportfileshint" 	class="description" hidden><?php echo getTXT(315); ?></p>
			<p id="log_settings" class="center">
				<!--<a data-role="button" class="ui-btn ui-mini ui-icon-gear ui-btn-icon-notext ui-btn-inline ui-shadow ui-corner-all ui-nodisc-icon ui-alt-icon buttonStyle" id="login_settings_button" title="<?php echo getTXT(203); ?>" tabindex="6" data-inline="true" data-mini="true"></a>-->
			</p>
		</div>

