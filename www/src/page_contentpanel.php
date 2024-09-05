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
?>
<h3 class="center"><?php echo getTXT(102); ?></h3>
<div id="menu_content_buttons">
	<a data-role="button" data-rel="close" id="menu_content" class="ui-btn ui-icon-home ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_content menuButtonStyle"><?php echo getTXT(314); ?></a>
	<a data-role="button" data-rel="close" id="menu_profile" class="ui-btn ui-icon-user ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_profile menuButtonStyle"><?php echo getTXT(201); ?></a>
	<a data-role="button" data-rel="close" id="menu_changepass" class="ui-btn ui-icon-star ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_changepass menuButtonStyle"><?php echo getTXT(202); ?></a>
	<a data-role="button" data-rel="close" id="menu_settings" class="ui-btn ui-icon-gear ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_settings menuButtonStyle"><?php echo getTXT(203); ?></a>
	<a data-role="button" data-rel="close" id="menu_exportimport" class="ui-btn ui-icon-action ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_exportimport menuButtonStyle"><?php echo getTXT(212); ?></a>
	<a data-role="button" data-rel="close" id="menu_loginprotocol" class="ui-btn ui-icon-bullets ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_loginprotocol menuButtonStyle"><?php echo getTXT(321); ?></a>
	<a data-role="button" data-rel="close" id="menu_abouthelp" class="ui-btn ui-icon-info ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_abouthelp menuButtonStyle"><?php echo getTXT(204); ?></a>
	<br>
	<a data-role="button" data-rel="close" class="ui-btn ui-icon-lock ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menuButtonStyle logoutbutton"><?php echo getTXT(205); ?></a>
</div>
<p class="menuFooter" id="consolew">Session: <?php echo session_id(); ?></p>
<br>
<div id="menu_content_footer">
<!--	<p class="menuFooter" id="menu_footer_text"></p>
	<p class="menuFooter" id="licence_footer_text">Copyright &copy; 2015<br>
	<a href="#" onclick="window.open('http://www.gnu.org/licenses/', '_system');" class="ui-btn ui-mini ui-corner-all ui-btn-inline smallerFont">GNU General Public License</a></p>
-->
</div>						
