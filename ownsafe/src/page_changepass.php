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

<!-- ----- change pass ----- -->

		<div data-role="panel" class="ui-panel" id="changepassPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
	 				<?php include('page_contentpanel.php'); ?>
	 	</div> 
				
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="#changepassPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
	    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>

	</div>
	 
		<div data-role="main" class="ui-content" data-theme="a" data-tap-toggle="false">
			<form id="changepass_form" class="ui-body ui-body-a ui-corner-all" data-ajax="true">
			
			<p id="changepass_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-star titleText"><?php echo getTXT(263); ?></p>

		      <fieldset>
					<label for="changepass_username" class="smallerFont"><?php echo getTXT(215); ?></label>
					<input type="text" value="" name="changepass_username" 
					id="changepass_username"  placeholder="<?php echo getTXT(215); ?>" tabindex="1" data-mini="true" />
         	<label for="changepass_curpassword" class="smallerFont"><?php echo getTXT(293); ?></label>
					<input type="password" value="" name="changepass_curpassword" 
					id="changepass_curpassword"  placeholder="<?php echo getTXT(293); ?>" tabindex="2" data-mini="true" />
					<p></p>
					<label for="changepass_newpassword" class="smallerFont"><?php echo getTXT(264); ?></label>
					<input type="password" value="" name="changepass_newpassword" 
					id="changepass_newpassword"  placeholder="<?php echo getTXT(264); ?>" tabindex="3" data-mini="true" />
					<label for="changepass_newpasswordconfirm" class="smallerFont"><?php echo getTXT(265); ?></label>
					<input type="password" value="" 
					name="changepass_newpasswordconfirm" id="changepass_newpasswordconfirm" placeholder="<?php echo getTXT(265); ?>" tabindex="4" data-mini="true" />
					<p class="description"><?php echo getTXT(295); ?></p>
		        </fieldset>
			</form> 

			<div data-role="popup" id="changepass_updateSuccess" class="ui-content" data-overlay-theme="b" data-theme="a">
	 			<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(266); ?></h3>
				<!--<div class="positionClosePopupButton"><a data-rel="back" id="changepass_closeUpdateSuccess" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>-->
			</div>		

			<div data-role="popup" id="changepass_updateFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3 id="changepass_savingFailed"><?php echo getTXT(267); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_changeFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(267); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
				<div name='error_content' id='error_content'>
					<h3><?php echo getTXT(120); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_sessionError" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
			</div>
			
			<div data-role="popup" id="changepass_fillFields" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(117); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_currentPassFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon'><?php echo getTXT(107); ?></a>
				<div name='error_content' id='error_content'>
					<h3><?php echo getTXT(268); ?></h3>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_passCheck" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(148); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_oldnewCheck" class="ui-content smallerPopup" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(269); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_passSecurityCheck" class="ui-content smallerPopup" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(149); ?></h3>
				<p class="popupTextBottom"><?php echo getTXT(150); ?></p>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>	
						
			<div data-role="popup" id="changepass_uexist" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<div name="su_error_content" id="su_error_content">
					<h3><?php echo getTXT(144); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(145); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="changepass_wait" class="ui-content" data-overlay-theme="b" data-theme="a">
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(153); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(295); ?></p>
					</div>
			</div>
			
			<div data-role="popup" id="changepass_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
				</div>
			</div>
			
  	</div>

    <div id="changepassfooter" data-position="fixed" data-role="footer" class="center" data-theme="b" data-tap-toggle="false">
	        	        		
    	<a  data-role="button" data-theme="reset" data-icon="check" id="changepass_save" class="bottomButtonStyle"><?php echo getTXT(222); ?></a> 
			<a  data-role="button" data-theme="reset" data-icon="refresh" data-ajax="false" id="changepass_reset" class="bottomButtonStyle"><?php echo getTXT(224); ?></a>
		</div>
