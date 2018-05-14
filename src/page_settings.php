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

<!-- ----- settings ----- -->

<div data-role="panel" class="ui-panel" id="settingsPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
	 				<?php include('page_contentpanel.php'); ?>
</div> 

				
	<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
		
			<a href="#settingsPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
	</div>
	 
		<div data-role="main" class="ui-content" data-theme="a">
			<div id="settings_page" class="ui-body ui-body-a ui-corner-all">
			
			<p id="settings_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-gear titleText"><?php echo getTXT(203); ?></p>
			
		        <fieldset>
		           	
					<label for="settings_logout_slider" class="smallerFont"><?php echo getTXT(258); ?></label>
					<input type="range" name="settings_logout_slider" id="settings_logout_slider" class="slider" value="99" min="030" max="300" />
					
					<label for="settings_passgen_slider" class="smallerFont"><?php echo getTXT(259); ?></label>
					<input type="range" name="settings_passgen_slider" id="settings_passgen_slider" class="slider" value="16" min="06" max="32" />

					<label for="settings_loginprotocol_slider" class="smallerFont"><?php echo getTXT(328); ?></label>
					<input type="range" name="settings_loginprotocol_slider" id="settings_loginprotocol_slider" class="slider" value="1" min="1" max="6" />
					<br>
					<label for="login_email" class="smallerFont"><?php echo getTXT(260); ?></label>
					<input type="checkbox" id="login_email" name="login_email">

					<label for="failedlogin_email" class="smallerFont"><?php echo getTXT(299); ?></label>
					<input type="checkbox" id="failedlogin_email" name="failedlogin_email">
			
					<br>
		        </fieldset>
				</div> 

			<div data-role="popup" id="settings_updateSuccess" class="ui-content" data-overlay-theme="b" data-theme="a">
	 			<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(261); ?></h3>
				<p class="popupTextBottom"><?php echo getTXT(132); ?></p>
				<div class="positionClosePopupButton"><a data-rel="back" id="settings_closeUpdateSuccess" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="settings_updateFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3 id="settings_savingFailed"><?php echo getTXT(262); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="settings_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<div name="error_content" id="error_content">
					<h3><?php echo getTXT(120); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
      		</div>
			
			<div data-role="popup" id="settings_sessionError" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
			</div>
			
			<div data-role="popup" id="settings_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
				</div>
			</div>
			
		</div>	
       	
    <div id="settingsfooter" data-position="fixed" data-role="footer" data-theme="b" class="center" data-tap-toggle="false" >
				<a  data-role="button" data-theme="reset" data-icon="check" class="bottomButtonStyle" id="settings_save"><?php echo getTXT(222); ?></a> 
		</div>
    

