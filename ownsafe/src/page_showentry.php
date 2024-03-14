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

	 <!-- ----- show entry ----- -->

		<div data-theme="b" data-role="header" data-position="fixed" data-tap-toggle="false">
			<a data-role="button" data-rel="back" data-theme="a" class="ui-btn ui-icon-arrow-l ui-btn-icon-notext ui-corner-all buttonStyle" tabindex="8" ></a>
			    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		    <a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
		</div>

	  	<div data-role="main" class="ui-content" data-theme="a">
				<form id="show_entry_form" class="ui-body ui-body-a ui-corner-all">
				<p id="show_headline_edit" class="ui-btn-icon-top ui-shadow-icon ui-icon-edit titleText" hidden><?php echo getTXT(294); ?></p>
				<p id="show_headline_show" class="ui-btn-icon-top ui-shadow-icon ui-icon-bullets titleText"><?php echo getTXT(225); ?>&nbsp;<span id="show_counter" class="showCounter"></span></p>

			      <fieldset class="showentry-wrapper">
			     		<input type="hidden" value="" id="show_id" name="show_id"/>
							<input type="hidden" value="" id="show_iv" name="show_iv"/>
							<input type="hidden" value="show" id="show_formkind" name="show_formkind"/>
							<label for="show_title" class="smallerFont"> <?php echo getTXT(214); ?></label>
							<div id="show_title_txt" class="entryText"></div>
							<input type="text" value="" name="show_title" id="show_title" placeholder="<?php echo getTXT(214); ?>" title="<?php echo getTXT(214); ?>" tabindex="1" data-mini="true"/>
													

							<label for="show_username" id="show_username_label" class="smallerFont entryLabelWithButton"><?php echo getTXT(215); ?></label>
							<input type="text" value="" name="show_username" id="show_username" placeholder="<?php echo getTXT(215); ?>" title="<?php echo getTXT(215); ?>" tabindex="2" data-mini="true" maxlength="32"/>
							<div id="show_username_txt" class="entryText"></div>
							<a data-role="button" class="ui-btn ui-btn-inline ui-icon-action ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon copyButton" id="show_copyuser" title="<?php echo getTXT(298); ?>" tabindex="3" data-inline="true" data-mini="true"  value="copy"></a>
							
							<label for="show_password" id="show_password_label" class="smallerFont entryLabelWithButton"><?php echo getTXT(106); ?></label>
							<input type="password" value="" name="show_password" id="show_password" data-inline="true" placeholder="<?php echo getTXT(106); ?>" title="<?php echo getTXT(106); ?>" tabindex="4" data-mini="true"  size="32" maxlength="32"/>
							<div id="show_password_txt" class="entryText"></div>
							<a data-role="button" class="ui-btn ui-btn-inline ui-icon-action ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon copyButton" id="show_copypass" title="<?php echo getTXT(297); ?>" tabindex="5" data-inline="true" data-mini="true" value="copy"></a>
							<a data-role="button" class="ui-btn ui-btn-inline ui-icon-eye ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon showButton" 	id="show_showbutton" title="<?php echo getTXT(290); ?>" tabindex="6" data-inline="true" data-mini="true" value="show"></a>

							<label for="show_passwordconfirm" id="show_passwordconfirm_label" class="smallerFont"><?php echo getTXT(140); ?></label>
							<input type="password" value="" name="show_passwordconfirm" id="show_passwordconfirm"  placeholder="<?php echo getTXT(140); ?>" title="<?php echo getTXT(140); ?>" tabindex="7" data-mini="true" size="32" maxlength="32"/>

							<label for="show_url" id="show_url_label" class="smallerFont"><?php echo getTXT(216); ?></label>
							<input type="url" value="" name="show_url" id="show_url" placeholder="<?php echo getTXT(216); ?>" title="<?php echo getTXT(216); ?>" tabindex="8" data-mini="true" />
							<div id="show_url_txt" class="entryText"><a id="show_url_href" class="show_urlHref" target="_blank"></a></div>
							<a data-role="button" class="ui-btn ui-btn-inline ui-icon-action ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon copyButton" id="show_copyurl" title="<?php echo getTXT(340); ?>" tabindex="9" data-inline="true" data-mini="true"  value="copy"></a>

							<label for="show_note" id="show_note_label" class="smallerFont"><?php echo getTXT(217); ?></label>
							<textarea id="show_note" name="show_note" placeholder="<?php echo getTXT(217); ?>" title="<?php echo getTXT(217); ?>" tabindex="10" data-mini="true"></textarea>
							<div id="show_note_txt" class="entryText"></div>
							<a data-role="button" class="ui-btn ui-btn-inline ui-icon-action ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon copyButton" id="show_copynote" title="<?php echo getTXT(341); ?>" tabindex="11" data-inline="true" data-mini="true"  value="copy"></a>

							<br/>
					 </fieldset>
				</form> 
				<br>
				<div class="center" id="show_clearclipboard" hidden><a data-role="button" data-inline="true" data-mini="true" class="clearclipboardButton" data-theme="a" id="show_clearclipboard_anchor"><?php echo getTXT(133); ?></a></div>

				<div data-role="popup" id="show_fillFields" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(220); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>

				<div data-role="popup" id="show_updateSuccess" class="ui-content" data-overlay-theme="b" data-theme="a">
	 				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(226); ?></h3>
					<!--<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline" id="show_closeUpdateSuccess" ><?php echo getTXT(107); ?></a></div>-->
				</div>
				
				<div data-role="popup" id="show_updateFailed" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
					<h3 id="show_savingFailed"><?php echo getTXT(227); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>		

				<div data-role="popup" id="show_passCheck" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-alert ui-btn-icon-notext ui-btn-left popupErrorIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(221); ?></h3>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>		

				<div data-role="popup" id="show_error" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
					</div>
					<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
				</div>
			
				<div data-role="popup" id="show_sessionError" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
				</div>
			
				<div data-role="popup" id="show_copyPopup" class="ui-content ui-corner-all smallerPopup" data-overlay-theme="b" data-theme="a">
					<a data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
					<h3><?php echo getTXT(126); ?></h3>
					<p class="popupTextBottom" id="popup_copyBrowserTxt"><?php echo getTXT(127); ?></p>
					<p class="popupTextBottom" id="popup_copyAppTxt" hidden><?php echo getTXT(131); ?></p>
					<!--<div class="positionClosePopupButton"><a data-rel="back" id="closePopupButton" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>-->
				</div>
				
				<div data-role="popup" id="show_deletePopupDialog" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
						<h3 class="ui-title"><?php echo getTXT(230); ?></h3>
					     	<p><?php echo getTXT(231); ?></p>
					    <a  data-role="button" data-inline="true" data-mini="true" data-rel="back" data-theme="a"><?php echo getTXT(232); ?></a>
					    <a  data-role="button" data-inline="true" data-mini="true" data-rel="back" data-theme="b" id="show_popup_delete"><?php echo getTXT(229); ?></a>
		    			</div>
				</div>	
		</div>		

		<div id="showfooter" data-position="fixed" data-role="footer" data-theme="b" class="center" data-tap-toggle="false">
					<a data-role="button" data-icon="check" class="bottomButtonStyle" id="show_save"><?php echo getTXT(222); ?></a>
					<a data-role="button" data-theme="reset" data-icon="star" id="show_genpass" class="bottomButtonStyle"><?php echo getTXT(223); ?></a>
					<a data-role="button" data-icon="delete" id="show_delete" class="bottomButtonStyle"><?php echo getTXT(229); ?></a>
					<a data-role="button" data-icon="edit" data-inline="true" id="show_edit" class="bottomButtonStyle"><?php echo getTXT(228); ?></a> 
		</div>


	        	
