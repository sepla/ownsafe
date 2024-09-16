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
<h3 class="center"><?php echo getTXT(200); ?></h3>
<div id="menu_login_buttons" class="menu_login_buttons">
<a data-role="button" data-rel="close" id="menu_login" class="ui-btn ui-icon-home ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_login menuButtonStyle"><?php echo getTXT(103); ?></a>
<a data-role="button" data-rel="close" id="menu_signup" class="ui-btn ui-icon-user ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_signup menuButtonStyle"><?php echo getTXT(101); ?></a>
<a data-role="button" data-rel="close" id="menu_login_settings" class="ui-btn ui-icon-gear ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_login_settings menuButtonStyle"><?php echo getTXT(203); ?></a>
<a data-role="button" data-rel="close" id="menu_login_install" class="ui-btn ui-icon-arrow-d ui-mini ui-btn-icon-left ui-corner-all ui-btn-inline menu_login_install menuButtonStyle"><?php echo getTXT(311); ?></a>
</div>
<br>
<div id="menu_login_footer">
		<p class="menuFooter menu_footer_text" id="menu_footer_text"></p>
		<p class="menuFooter" id="licence_footer_text">Copyright &copy; 2015<br>
		<a href="#" onclick="window.open('http://www.gnu.org/licenses/', '_system');" class="ui-btn ui-mini ui-corner-all ui-btn-inline smallerFont">GNU General Public License</a></p>
		<p class="menuFooter" id="consolew"></p>
</div>
