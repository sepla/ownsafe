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
					<h4>Installation</h4>
					<p>* Standard docker installation *</p>
					<ul>
						<li>Clone the git repository: <a href="#" onclick="window.open('https://github.com/sepla/ownsafe.git', '_system');">https://github.com/sepla/ownsafe.git</a></li>
						<li>Go into the created "ownsafe" folder and run: docker-compose -f ./docker/docker-compose.yml up --build -d</li>
					</ul>
          <br>
					<p>* Portainer installation *</p>
					<ul>
						<li>Create a new Stack<br>Name: ownsafe<br>Use repository with URL: <a href="#" onclick="window.open('https://github.com/sepla/ownsafe.git', '_system');" >https://github.com/sepla/ownsafe.git</a><br>Compose path: docker/docker-compose.yml</li>
						<li>Deploy the stack</li>
					</ul>
          <br>
				<p>* Email configuration *</p>
					<ul>
						<li>Go to the "config"-folder at the mount path of created "ownsafe_www-data" docker volume (e.g./var/lib/docker/volumes/ownsafe_www-data/_data)</li>
						<li>Edit the config.php file and edit the following lines:<br><pre>
    $mail->Host       = 'hostUrl';<br>
    $mail->Username   = 'username';<br>
    $mail->Password   = 'Pass1234';<br>
    $mail->Port       = 465;<br>
    $mailFrom         = 'ownsafe@local.host';<br>	
    $mailNoReply      = 'no-reply@local.host';</pre></li>
					</ul>
          <br>
         <p>* SSL/TLS Installation *</p>
					<ul>
						<li>Create a private key "ownsafe.key" and certificate "ownsafe.crt"</li>
						<li>Go to the "ssl"-folder at the mount path of "ownsafe_nginx-conf" docker volume (e.g. /var/lib/docker/volumes/ownsafe_nginx-conf/_data)</li>
						<li>Copy the created .key and .crt file into the "ssl"-folder</li>
						<li>Edit the nginx.conf file on "ownsafe_nginx-conf"-Volume and activate the following values:<pre>
    listen 443 ssl;<br>
    listen [::]:443 ssl;<br>
    ssl_certificate /etc/nginx/conf.d/ssl/ownsafe.crt;<br>
    ssl_certificate_key /etc/nginx/conf.d/ssl/ownsafe.key;<br>
    include /etc/nginx/conf.d/ssl/ssl-params.conf;</li>
						<li>Restart the container, https works on port 444</pre></li>
					</ul>
          <br> 
           <p>*** DB Import/Export *</p>
					<ul>
						<li>Import data (from sql file): docker exec -i Ownsafe_DB mysql -uownsafe -pownsafe ownsafe < ownsafe.sql</li>
						<li>Export data (to sql file):   docker exec -i Ownsafe_DB /usr/bin/mysqldump -uownsafe -pownsafe --skip-extended-insert --skip-comments ownsafe > ownsafe.sql</li>
					</ul>
          <br> 
		</div>

		<div data-position="fixed" data-role="footer" data-theme="b" data-tap-toggle="false" id="installFooter">
			<h5 class="center" id="footer_init_text"></h5>
		</div>
