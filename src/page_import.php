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


<!-- ----- import ----- -->


 		<div data-role="panel" class="ui-panel" id="importPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
	 				<?php include('page_contentpanel.php'); ?>
	 	</div> 
				
	<div data-role="header" data-theme="b" data-position="fixed">
		<a href="#importPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
	    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>

	</div>
	 
		<div data-role="main" class="ui-content" data-theme="a" data-tap-toggle="false">
			
			<div id="import_page" class="ui-body ui-body-a ui-corner-all">
				<p id="import_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(300); ?></p>
				<br>
				<fieldset>
					<div id="browser_file_chooser">
			   			<label for="import_file" class="smallerFont"><?php echo getTXT(301); ?></label>
						<input type="file" name="import_file" id="import_file" accept=".csv" placeholder="<?php echo getTXT(301); ?>" tabindex="1" data-mini="true" data-clear-btn="true" />
					</div>
					<div id="app_file_chooser" >
						<label for="app_file_chooser_container" class="smallerFont"><?php echo getTXT(301); ?></label>	
						<div id="app_file_chooser_container">
							<div>
								<input type="text" name="import_file_text" id="import_file_text" placeholder="<?php echo getTXT(318); ?>" tabindex="2" data-mini="true" data-inline="true"/>
							</div>
							<input type="button" class="importSelectFileButton" name="import_file_button" id="import_file_button" placeholder="<?php echo getTXT(317); ?>" tabindex="1" data-mini="true" data-clear-btn="true"  data-inline="true" value="<?php echo getTXT(317); ?>"/>
						</div>
					</div>
					<br class="clearFloat">
					<p id="import_progress" class="description">&nbsp;</p>
				</fieldset>
			</div>

			<div data-role="popup" id="import_success" class="ui-content smallerPopup" data-overlay-theme="b" data-theme="a">
	 			<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(305); ?></h3>
				<p class="popupTextBottom"><?php echo getTXT(307); ?>&nbsp;<span id="import_count"></span><br>
							<?php echo getTXT(308); ?>&nbsp;<span id="import_countsuccessfull"></span><br>
							<?php echo getTXT(309); ?>&nbsp;<span id="import_counterror"></span><br>
							<span id="import_errormessage"></span></p>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>		
			
			<div data-role="popup" id="import_fullPath" class="ui-content smallerPopup" data-overlay-theme="b" data-theme="a">
	 			<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-check ui-btn-icon-notext ui-btn-left popupOkIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(296); ?></h3>
				<p class="popupTextBottom" id="import_fileName"></p>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="import_error" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
				<div name='error_content' id='error_content'>
					<h3><?php echo getTXT(120); ?></h3>
					<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
				</div>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="import_sessionError" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
			</div>
			
			<div data-role="popup" id="import_fillFields" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(117); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="import_csvhint" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon"><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(316); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>
			
			<div data-role="popup" id="import_noentries" class="ui-content" data-overlay-theme="b" data-theme="a">
				<a data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-info ui-btn-icon-notext ui-btn-left popupInfoIcon'><?php echo getTXT(107); ?></a>
				<h3><?php echo getTXT(276); ?></h3>
				<div class="positionClosePopupButton"><a data-rel="back" class="closePopupButton ui-btn ui-mini ui-corner-all ui-btn-inline"><?php echo getTXT(107); ?></a></div>
			</div>	
			
			<div data-role="popup" id="import_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
				</div>
			</div>
			
    </div>

		<div id="importfooter" data-position="fixed" data-role="footer" data-theme="b" class="center" data-tap-toggle="false">
				<a data-role="button" data-theme="reset" data-icon="action" id="import_import" class="bottomButtonStyle"><?php echo getTXT(302); ?></a> 
		</div>
				
		</div>
    

