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

?>

<!-- ----- sign up ----- -->	
    	<div data-role="panel" class="ui-panel" id="signupPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
				<?php include('page_loginpanel.php'); ?>
		</div> 
		
		<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
		<a href="#signupPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
		<h3><?php echo getTXT(102); ?></h3>
		<a data-role="button" data-theme="b" class="ui-btn ui-btn-right ui-corner-all buttonStyle" name="su_submit" id="su_submit" value="<?php echo getTXT(138); ?>" tabindex="4" ><?php echo getTXT(138); ?></a>
		</div>
	 
		<div data-role="main" class="ui-content" data-theme="a">
			<form id="signup-user" class="ui-body ui-body-a ui-corner-all" data-ajax="true" autocomplete="off">

			<p class="ui-btn-icon-top ui-icon-user titleText"><?php echo getTXT(139); ?></p>

		        <fieldset>
		        	<label for="su_username" class="smallerFont"><?php echo getTXT(105); ?></label>
					<input type="text" value="" name="su_username" id="su_username"  placeholder="<?php echo getTXT(105); ?>" tabindex="1" data-mini="true" autocomplete="off"/>
					<label for="su_password" class="smallerFont"><?php echo getTXT(106); ?></label>
					<input type="password" value="" name="su_password" id="su_password" placeholder="<?php echo getTXT(106); ?>" tabindex="2" data-mini="true" autocomplete="off"/>
					<label for="su_passwordconfirm" class="smallerFont"><?php echo getTXT(140); ?></label>
					<input type="password" value="" name="su_passwordconfirm" id="su_passwordconfirm" placeholder="<?php echo getTXT(140); ?>" tabindex="3" data-mini="true"/>
		        </fieldset>
			</form> 

			<div data-role="popup" id="su_signupSuccess" class="ui-content" data-overlay-theme="b" data-theme="a">
 				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(142); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>		

			<div data-role="popup" id="su_signupFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(143); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>	

			<div data-role="popup" id="su_signupFailed_uexist" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<div name="su_error_content" id="su_error_content">
					<h3><?php echo getTXT(144); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(145); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="su_signupFailed_eexist" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<div name="su_error_content" id="su_error_content">
					<h3><?php echo getTXT(146); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(147); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="su_fillFields" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(117); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="su_passCheck" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(148); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>	

			<div data-role="popup" id="su_passSecurityCheck" class="ui-content smallerPopup" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(149); ?></h3>
				<p class="popupTextBottom"><?php echo getTXT(150); ?></p>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>		

			<div data-role="popup" id="su_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<div name="error_content" id="error_content">
					<h3><?php echo getTXT(120); ?></h3>
					<p><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>	

			<div data-role="popup" id="su_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-alert ui-btn-icon-notext ui-btn-right popupErrorIcon"><?php echo getTXT(107); ?></a>
				<div name="error_content" id="error_content">
					<h3><?php echo getTXT(120); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>

			<div data-role="popup" id="su_emailFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left  popupInfoIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(151); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			<p id="su_insecurelogin" name="su_insecurelogin" class="warningDescription" hidden><?php echo getTXT(135); ?><br><?php echo getTXT(136); ?></p>
    </div>
		
