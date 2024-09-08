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

if (isset($_REQUEST['period'])) $protocollength = $_REQUEST['period'];
else $protocollength = 1;
?>

<!-- -----loginprotocol ----- -->

 		<div data-role="panel" class="ui-panel" id="loginprotocolPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
	 				<?php include('page_contentpanel.php'); ?>
	 	</div> 
 
				
	<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
		<a href="#loginprotocolPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
	   <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
		<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
	</div>

	<div data-role="main" class="ui-content" data-theme="a">
		
			<div id="loginprotocol_page" class="ui-body ui-body-a ui-corner-all">
				<p id="new_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-bullets titleText"><?php echo getTXT(320); ?></p>
				<table class="loginprotocoltable">
					
				 <?php 
					$days = (30*$protocollength);
		        	$qry = "SELECT * FROM ".$_SESSION['db_loginTable']." WHERE userid='".$_SESSION['uid']."' AND date > (NOW() - INTERVAL ".$days." DAY) ORDER BY id DESC";
					$res 		= $mysqli->query($qry);
					$num_row 	= $res->num_rows;
					
					echo '<tr><th>'.getTXT(325).'<br>-<br>'.getTXT(322).'</th><th>'.getTXT(323).'<br>-<br>'.getTXT(324).'</th><th>'.getTXT(327).'<br>-<br>'.getTXT(326).'</th></tr>';
					if( $num_row != 0 ) {
						
						while ($row = $res->fetch_assoc()) {
							$date 			= $row['date'];
							$date			= "<nobr>".str_replace(" ","</nobr><br><nobr>",$date)."</nobr>";
							$ip				= $row['ip'];
							$useragent		= $row['useragent'];
							$status			= $row['status'];
							$attempt		= $row['attempt'];
							$logincounter	= $row['logincounter'];

							if ($status=="OK") 							$status='<span class="protocol_ok">'.$status.'</span>';
							else if ($status=="FAILED") 				$status='<span class="protocol_failed">'.$status.'</span>';
							else if ($status=="BLOCKED") 				$status='<span class="protocol_blocked">'.$status.'</span>';
							else if (stripos($status,"ERROR")!==FALSE) 	$status='<span class="protocol_error">'.$status.'</span>';

							echo '<tr><td>'.$status.'<br>-<br>'.$date.'</td><td>'.$ip.'<br>-<br>'.$useragent.'</td><td>'.$logincounter.'<br>-<br>'.$attempt.'</td></tr>';
							
						}
						
						
						
					} else echo '<span>'.getTXT(209).'</span>';
			    ?>
				</table>
			</div>

			<div data-role="popup" id="loginprotocol_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
				<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
				<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
				<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
	    			<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
				</div>
			</div>
			
       	</div>


