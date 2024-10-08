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

<!-- ----- about/help ----- -->

 		<div data-role="panel" class="ui-panel" id="abouthelpPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
	 				<?php include('page_contentpanel.php'); ?>
	 	</div> 
 
				
	<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
		<a href="#abouthelpPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
	    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
	</div>

		<div data-role="main" class="ui-content" data-theme="a">
			<div id="about_page" class="ui-body ui-body-a ui-corner-all">
				<p id="changepass_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-info titleText"><?php echo getTXT(277); ?></p>
				<div>
				<p><?php echo getTXT(278); ?></p>
				<h4><?php echo getTXT(279); ?></h4>
				<p><?php echo getTXT(280); ?></p>
				<h4><?php echo getTXT(281); ?></h4>
				<p><?php echo getTXT(282); ?></p>
				<h4><?php echo getTXT(283); ?></h4>
				<p><?php echo getTXT(284); ?></p>
				<h4><?php echo getTXT(285); ?></h4>
				<p>T<?php echo getTXT(286); ?></p>
				<h4><?php echo getTXT(287); ?></h4>
				<p><?php echo getTXT(288); ?></p>
				<h4><?php echo getTXT(289); ?></h4>
				<p>Sebastian Plaza<br>
				&#x6F;&#x77;&#x6E;&#x73;&#x61;&#x66;&#x65;&#x40;&#x73;&#x70;&#x6C;&#x61;&#x7A;&#x61;&#x2E;&#x64;&#x65;</p>
				<p>Copyright &copy; 2018 Sebastian Plaza<br>
				<p><?php echo getTXT(400); ?></p>
				<p><?php echo getTXT(401); ?></p>
				<p><?php echo getTXT(402); ?></p>
				<a href="#" onclick="window.open('http://www.gnu.org/licenses/', '_system');" class="ui-btn ui-mini ui-corner-all ui-btn-inline">GNU General Public License</a>
					
			</div>
				
			
			<div data-role="popup" id="abouthelp_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
				</div>
			</div>
		
    </div>

