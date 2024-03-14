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
		
		<div data-role="panel" class="ui-panel" id="loginInstallPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
				<?php include('page_loginpanel.php'); ?>
		</div>

		<div data-role="header" data-theme="b" data-position="fixed" id="installHeader" data-tap-toggle="false">
			<a href="#loginInstallPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle"></a>
			<h3><?php echo getTXT(102); ?></h3>
		</div>
		<div data-role="main" class="ui-content" data-theme="a" id="main">
			<div id="install_page" class="ui-body ui-body-a ui-corner-all">
				<p id="changepass_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-arrow-d titleText">Installation</p>
					<h4>System requirements for your own server (e.g. Raspberry Pi):</h4>
					<p>To run this application you need a system with following installed and useable componets (LAMP- or XAMPP-Environmant)</p>
					<ul>
						<li>Linux / Solaris / Windows / Mac OS X</li>
						<li>Apache web server</li>
            <li>PHP 5.2 or higher (mysqli module required)</li>
						<li>MySql database (or compatible like MariaDB)</li>
						<li>Optional: Sendmail/Posfix for sending mails</li>
					</ul>
                <br>
					<h4>Server installation</h4>
					<ul>
							<li>DOWNLOAD the server application: <a href="#" onclick="window.open('https://github.com/splaza76/ownsafe', '_system');" class="ui-btn ui-mini ui-corner-all ui-btn-inline">Github.com</a></li>
						<li>Unzip the files and copy the ownsafe folder to the HOME-directory of the Apache server (mostly /var/www/html)</li>
						<li>Edit the configuration file (config.php in config directory): Enter on "DB" your values for the MySql database address, username, password and database name.<br>The application creates the necessary database tables automaticly</li>
						<li>For testing server functionality call the application test site in your browser, http://your-server-url/ownsafe/test.php</li>
				</ul>	
    		    <p>In case of problems set the owner and group of the ownsafe folder (including all files) to www-data or change the access rights</p>
        <br>
				<hr>
        <br>
				</div>
                <p>Other languages: <a href="INSTALL.TXT" type="button" target="_blank" class="ui-btn ui-mini ui-corner-all ui-btn-inline">INSTALL.TXT</a></p>
			</div>
		</div>

		<div data-position="fixed" data-role="footer" data-theme="b" data-tap-toggle="false" id="installFooter">
			<h5 class="center" id="footer_init_text"></h5>
		</div>
