<?php
if (isset($_SESSION['uid'])) {
	$qry = "SELECT session,ip,lastaction,logouttime FROM ".$_SESSION['db_userTable']." WHERE userid=".$_SESSION['uid'];

	$res 		= $mysqli->query($qry);
	$num_row 	= $res->num_rows;
	$row 		= $res->fetch_assoc();

	$lastaction = new DateTime($row['lastaction']);
	$now = new DateTime();
	//Differenz in Sekunden
	$diff = round($now->format('U') - $lastaction->format('U'));		
	$logouttime=$row['logouttime'];
	// Logout-Zaehler aus den Benutzer-Einstellungen
	if (!$logouttime || $logouttime<30 || $logouttime>300) $logouttime=30;
	
	//$filestring = "S1:".$row['session']."\nS2:".session_id()."\nIP1:".$row['ip']."\nIP2:".$_SERVER['REMOTE_ADDR']."\nDIF:".$diff." ".$_SERVER['HTTP_REFERER'];
		
	if( $num_row != 1  || $row['session']!=session_id() || $row['ip']!=$_SERVER['REMOTE_ADDR'] || $diff>($logouttime+30)) {
		$_SESSION = array();
		session_destroy();
		$output = array('status' => false, 'message' => 'InvalidSession');	
		echo json_encode($output);
		exit;
	} else {
		$qry = "UPDATE ".$_SESSION['db_userTable']." SET lastaction='".date('Y-m-d H:i:s')."' WHERE userid=".$_SESSION['uid'];
		$res = $mysqli->query($qry);
	}
} else {
	$_SESSION = array();
	session_destroy();
	header("Location: ./");
	exit;
} 
?>
