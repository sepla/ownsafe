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

if ($dbErrorString) {
	echo '<script type="text/javascript">
						sessionStorage.setItem("OwnSafeServerUrl","");
						localStorage.clear();
						$("#logout").load(sessionStorage.getItem("OwnSafeServerUrl")+"/src/logout.php",function(){
	   			     $("#logout").trigger("create");
            $.mobile.changePage("#logout");
						});
				</script>
	';
}
 	echo '<script type="text/javascript">
 	 		var logoutTxt = "'.getTXT(205).'";
 	 		var loggedinTxt = "'.$_SESSION['uname'].' '.getTXT(319).'";
	</script>';
?>


		<div data-role="panel" class="ui-panel" id="contentPanel" data-theme="b" data-display="overlay" data-position-fixed="true">
 				<?php include('page_contentpanel.php'); ?>
	 	</div> 


		<div data-theme="b" data-role="header" data-position="fixed" data-tap-toggle="false">
					<a href="#contentPanel" data-role="button" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all buttonStyle" title="<?php echo getTXT(200); ?>"></a>
			    <h3><?php if (isset($_SESSION['uname']) && $_SESSION['uid'] != null) echo $_SESSION['uname']; ?></h3>
					<a data-role="button" data-inline="true" class="ui-btn ui-icon-lock ui-btn-icon-right ui-corner-all ui-corner-all buttonStyle logout logoutbutton" title="<?php echo getTXT(205); ?>">&nbsp;&nbsp;&nbsp;</a>
		</div>

	 	 <div data-role="main" class="ui-content" data-theme="a" >
	 	  		<table class="smallerTable" width="100%">
				<tr>
					<td class="lastLoginArea">
					  	<div class="lastLogin" id="content_lastlogin_label"><?php echo getTXT(206); ?></div>
					  	<div class="lastLogin" id="content_lastlogin">&nbsp;</div>
					</td>
					<td class="lastFailedLoginArea">
						<div class="lastFailedLogin" id="content_lastfailedlogin_label"><?php echo getTXT(207); ?></div>
						<span class="lastFailedLogin" id="content_lastfailedlogin">&nbsp;</span>
						<span class="failedLogin" id="content_loginfailure_label1" hidden>-</span>
						<span class="failedLogin" id="content_loginfailure" hidden></span>
						<span class="failedLogin" id="content_loginfailure_label2" hidden> <?php echo getTXT(208); ?></span>
					</td>
				</tr>
				</table>
			
			    <?php 
		        	
		        	$qry = "SELECT id,title,iv FROM ".$_SESSION['db_recordsTable']." WHERE userid='".$_SESSION['uid']."' ORDER BY title ASC";
					$res 		= $mysqli->query($qry);
					$num_row 	= $res->num_rows;

		  		?>

			  	<form class="ui-filterable">
					<input id="myFilter" data-type="search" placeholder="<?php echo getTXT(291)." $num_row ".getTXT(292); ?>" tabindex="1" >
			    </form>
			    <ul id="ullist" data-role="listview" data-autodividers="true" data-sort="true" data-inset="true" data-filter="true" data-input="#myFilter" hidden>
		    
				<?php
					if( $num_row != 0 ) {
						
						while ($row = $res->fetch_assoc()) {
							
							echo '<li><a  id="'.$row['id'].'" ivvalue="'.$row['iv'].'" class="entry">'.$row['title'].'</a></li>';
						}
						echo '</ul>';
						
					} else echo '<span>'.getTXT(209).'</span>';
			    ?>
				</ul>
				<p id="waitForDecode" class="center"><img src="js/jquery.mobile-1.4.5/images/ajax-loader2.gif"></p>
				
				 <div data-role="popup" id="content_eiswitch" data-overlay-theme="b" data-theme="a" data-dismissible="false" >
					<a  data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-left"><?php echo getTXT(107); ?></a>
					<p id="switch_headline" class="ui-btn-icon-top ui-shadow-icon ui-icon-action titleText"><?php echo getTXT(212); ?></p>
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content">
						<a data-role="button" data-inline="true" data-theme="b" id="switch_export"><?php echo getTXT(303); ?></a>
						<a data-role="button" data-inline="true" data-theme="b" id="switch_import"><?php echo getTXT(302); ?></a>
					</div>
				</div>
			 	
			 	<div data-role="popup" id="general_error" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(121); ?></p>
					</div>
				</div>
			 	
			 	<div data-role="popup" id="session_error" class="ui-content" data-overlay-theme="b" data-theme="a">
					<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-notext ui-btn-left popupErrorIcon'><?php echo getTXT(107); ?></a>
					<div name='error_content' id='error_content'>
						<h3><?php echo getTXT(120); ?></h3>
						<p class="popupTextBottom"><?php echo getTXT(152); ?></p>
					</div>
				</div>
			</div>

	     	<div data-position="fixed" id="contentfooter" class="center" data-role="footer" data-theme="b" data-tap-toggle="false">
	        		
						<a id="button_addentry" data-role="button" data-theme="reset" data-icon="plus" class="bottomButtonStyle"><?php echo getTXT(210); ?></a> 
						<a id="button_top" data-role="button" data-icon="arrow-u" data-ajax="false" class="bottomButtonStyle"><?php echo getTXT(211); ?></a>

			</div>
			


