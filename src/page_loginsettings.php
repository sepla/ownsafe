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

?>

<!-- ----- login - settings ----- -->
		<div data-role="panel" class="ui-panel" id="loginSettingsPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
					<?php include('page_loginpanel.php'); ?>
		</div> 

		<div data-role="header" data-theme="b" data-position="fixed" id="indexHeader" data-tap-toggle="false">
        
			<a href="#loginSettingsPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle"></a>			
      <h3><?php echo getTXT(102); ?></h3>
			<button type="submit" form="mainform" class="ui-btn ui-btn-right ui-corner-all buttonStyle" id="submit_button" value="<?php echo getTXT(138); ?>" tabindex="3" ><?php echo getTXT(138); ?></button>
		</div>
		<div data-role="main" class="ui-content" data-theme="a" id="main">
				<form id="index_settings_form" class="ui-body ui-body-a ui-corner-all" data-ajax="true">
					<p id="changepass_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-gear titleText"><?php echo getTXT(203); ?></p>
						<div id="error" class="dbErrorDescription"></div>
						<div id="init">
							<fieldset>
									<p id="serverUrlInput">
										<label for="url" class="smallerFont"><?php echo getTXT(313); ?></label>
										<input type="url" placeholder="Server-URL e.g. http://raspberrypi/ownsafe" id="url" required>
									</p><p>
										<label for="language" class="smallerFont"><?php echo getTXT(312); ?></label>
										<select id="language"><option value="EN">English</option></select>
									</p>
							</fieldset>
					</div>
				</form>
		</div>		

