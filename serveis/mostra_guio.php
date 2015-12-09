<?php
	define('INCLUDE_CHECK',true);
	require 'connect.php';
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);
	$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');
	$con = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con)) {
		echo("Error de conexio: ". mysqli_connect_error());
		exit();
	}
	$data_assaig = $_POST['data_assaig'];
	$popup = $_POST['popup'];
	$var_data_catalana = date("d-m-Y",strtotime($data_assaig));
	$assaig = mysqli_query($con,"SELECT * FROM guions_assaig where data = '" . $data_assaig . "' ORDER BY position") or die (mysql_error());
	
	if($popup == 'true'){
		while ($row_prova = mysqli_fetch_array($assaig)) {
			$proves []= array('prova'=>$row_prova['prova'],'text'=>$row_prova['text']);
		}
		echo json_encode($proves);
	}
	else{
        /*echo "<a href='#' data-rel='back' data-role='button' class='ui-btn-right icona-tancar'><i class='zmdi zmdi-close'></i></a>";*/
        echo "<div id='calendari_guio'></div>";
		echo "<ul data-role='listview' data-inset='true' id='guio_assaig' class='ui-panel-inner'>
			<li data-role='list-divider'><h3>ASSAIG " . $var_data_catalana . "</h3><a href='#' class='ui-btn-right waves-effect waves-button' data-ajax='false' onclick='javascript:$(\"#calendari_guio\").datepicker({dateFormat: \"yy-mm-dd\",onSelect: function(selected,evnt) { mostra_guio(selected);}});'><i class='zmdi zmdi-calendar zmdi-hc-2x'></i></a></li>";
		while ($row_prova = mysqli_fetch_array($assaig)) {
			echo "<li data-mini='true' class='prova_guio'><h3>" . $row_prova['prova'] ." <span>" . htmlentities($row_prova['text']) . "</span></h3></li>";
		}
		echo "</ul>";
	}
	mysqli_close($con);
?>
