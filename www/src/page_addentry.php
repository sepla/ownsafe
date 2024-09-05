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


	<!-- ----- add entry ----- -->

		
			<div data-theme="b" data-role="header" data-position="fixed" data-tap-toggle="false">
				<a data-role="button" data-rel="back" data-theme="a" class="ui-btn ui-icon-arrow-l ui-btn-icon-notext ui-corner-all buttonStyle" tabindex="8" ></a>
			    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
			    <a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
			</div>


	  		<div data-role="main" class="ui-content" data-theme="a">

				<form id="add_entry_form" class="ui-body ui-body-a ui-corner-all" data-ajax="true">

				<p id="new_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-plus titleText"><?php echo getTXT(213); ?></p>

			        <fieldset class="showentry-wrapper">

			        	<label for="new_title" class="smallerFont"><?php echo getTXT(214); ?></label>
						<input type="text" value="" name="new_title" id="new_title" data-mini="true" placeholder="<?php echo getTXT(214); ?>" tabindex="1" />
						<label for="new_username" class="smallerFont"><?php echo getTXT(215); ?></label>
						<input type="text" value="" name="new_username" id="new_username" data-mini="true" placeholder="<?php echo getTXT(215); ?>" tabindex="2" />

						<div>
							<label for="new_password" class="smallerFont entryLabelWithButton"><?php echo getTXT(106); ?></label>
							<input type="password" value="" name="new_password" id="new_password" data-inline="true" placeholder="<?php echo getTXT(106); ?>" tabindex="3" data-mini="true" maxlength="32"/>
							<a data-role="button" class="ui-btn ui-btn-inline ui-icon-eye ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon entryButton" id="new_showbutton" title="<?php echo getTXT(290); ?>" tabindex="7" data-inline="true" data-mini="true" value="show"></a>
						</div><div>
							<label for="new_passwordconfirm" id="new_passwordconfirm_label" class="smallerFont"><?php echo getTXT(140); ?></label>
				    		<input type="password" value="" name="new_passwordconfirm" id="new_passwordconfirm" data-mini="true" placeholder="<?php echo getTXT(140); ?>" tabindex="4"  maxlength="32"/>
						</div>
						<div>
							<label for="new_url" id="new_url_label" class="smallerFont"><?php echo getTXT(216); ?></label>
							<input type="text" value="" name="new_url" id="new_url" data-mini="true" placeholder="<?php echo getTXT(216); ?>" tabindex="5" />
						</div><div>
						<label for="new_note" class="smallerFont"><?php echo getTXT(217); ?></label>
						<textarea rows="3" name="new_note" id="new_note" data-mini="true" placeholder="<?php echo getTXT(217); ?>" tabindex="6" ></textarea>
						</div>
					 </fieldset>
				</form> 
				<br>
				<div data-role="popup" id="new_addingSuccess" class="ui-content" data-overlay-theme="b" data-theme="a">
	 				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(218); ?></h3>
					<!--<div class="positionClosePopupButton"><a data-rel="back" id="new_closeAddingSuccess" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>-->
				</div>
				
				<div data-role="popup" id="new_addingFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(219); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>
				
				<div data-role="popup" id="new_fillFields" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(220); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>
				
				<div data-role="popup" id="new_passCheck" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(221); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>
			
				<div data-role="popup" id="new_error" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
					</div>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
	    		</div>
				
				<div data-role="popup" id="new_sessionError" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
				</div>
				
				<div data-role="popup" id="session_error" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>

	    	</div>
	    	<div id="addfooter" data-position="fixed" data-role="footer" data-theme="b" class="center" data-tap-toggle="false">
						
					<a  data-role="button" data-theme="reset" data-icon="cloud" id="new_save" class="bottomButtonStyle"><?php echo getTXT(222); ?></a> 
					<a  data-role="button" data-theme="reset" data-icon="star" id="new_genpass" class="bottomButtonStyle"><?php echo getTXT(223); ?></a>
					<a  data-role="button" data-theme="reset" data-icon="refresh" data-ajax="false" id="new_reset" class="bottomButtonStyle"><?php echo getTXT(224); ?></a>
				
			</div>
	 


