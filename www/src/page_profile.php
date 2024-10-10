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



 <!-- ----- edit profile ----- -->

		<div data-role="panel" class="ui-panel" id="profilePanel" data-theme="b" data-display="overlay" data-position-fixed="true">
					<?php include('page_contentpanel.php'); ?>	
	 	</div> 
	
	<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
		<a href="#profilePanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
	</div>
	 
		<div data-role="main" class="ui-content" data-theme="a">
			
			<div id="profile_page" class="ui-body ui-body-a ui-corner-all">
				<p id="profile_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-user titleText"><?php echo getTXT(233); ?></p>
				<p name="profile_username" id="profile_username" class="usernameProfile"></p>
					 <fieldset>

						<label for="profile_email" class="smallerFont"><?php echo getTXT(235); ?></label>
						<input type="email" value="" name="profile_email" id="profile_email" data-mini="true" placeholder="<?php echo getTXT(235); ?>" tabindex="3" />
						<p class="description"><?php echo getTXT(236); ?></p>
						<?php if (isset($_SESSION['mailHost']) && $_SESSION['mailHost']=="hostUrl") 
			                echo '<p id="profile_noemailhost" class="warningDescription">'.getTXT(154).'<br></p>';
			            ?>    
						<label for="profile_hint" class="smallerFont"><?php echo getTXT(237); ?></label>
						<textarea name="profile_hint" id="profile_hint" data-mini="true" placeholder="<?php echo getTXT(237); ?>" rows="2" tabindex="4"></textarea>
						<p class="description"><?php echo getTXT(238); ?></p>

					</fieldset>
			</div> 

			<div data-role="popup" id="profile_updateSuccess" class="ui-content" data-overlay-theme="b" data-theme="a">
	 			<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(239); ?></h3>
				<!--<div class="positionClosePopupButton"><a data-rel="back" id="profile_closeUpdateSuccess" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>-->
			</div>		

			<div data-role="popup" id="profile_updateFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
				<h3 id="profile_savingFailed"><?php echo getTXT(240); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>		

			<div data-role="popup" id="profile_nothing" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(241); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>

			<div data-role="popup" id="profile_emailFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(151); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>

			<div data-role="popup" id="profile_emailFailed_eexist" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon'><?php echo getTXT(107); ?></a>
				<div name='profile_error_content' id='profile_error_content'>
					<h3><?php echo getTXT(146); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(242); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>

			<div data-role="popup" id="profile_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
				<div name='error_content' id='error_content'>
					<h3><?php echo getTXT(120); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>	
			
			<div data-role="popup" id="profile_sessionError" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
			</div>
			
			<div data-role="popup" id="profile_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
				</div>
			</div>

		</div>

			<div data-role="popup" id="profile_deleteProfileDialog" class="ui-content smallerPopup" data-overlay-theme="b" data-theme="a" data-dismissible="false">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
					<h3><?php echo getTXT(244); ?><br><?php echo getTXT(245); ?><br><?php echo getTXT(246); ?></h3>
					<hr/>
					
					<input type="checkbox" name="profile_okdelete" id="profile_okdelete"/>
					<label for="profile_okdelete"><?php echo getTXT(256); ?></label>
					<br>
					<a  data-role="button" data-inline="true" data-mini="true" data-rel="back" data-theme="a"><?php echo getTXT(232); ?></a>
					<a  data-role="button" data-inline="true" data-mini="true" data-rel="back" data-theme="a" id="profile_popup_delete" class="disabled"><?php echo getTXT(257); ?></a>
				</div>
			</div>		

		<div id="profilefooter" data-position="fixed" data-role="footer" class="center" data-theme="b" data-tap-toggle="false" >
				<a data-role="button" data-theme="reset" data-icon="check" id="profile_save" class="bottomButtonStyle"><?php echo getTXT(222); ?></a>
				<a data-role="button" data-theme="reset" href="#profile_deleteProfileDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="bottomButtonStyle" data-icon="delete" id="profile_delete"><?php echo getTXT(243); ?></a>
		</div>

	 


