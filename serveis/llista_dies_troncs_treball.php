/* llista els dies que hi ha troncs de treball desats */
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

	$assaig = mysqli_query($con,"SELECT * FROM guions_assaig where ((data <= '" . $data_assaig . "' OR data >= '" . $data_assaig . "') AND data <>'0000-00-00') GROUP BY data ORDER BY data DESC LIMIT 5") or die (mysql_error());
	echo "<select name='selector_troncs_treball' id='selector_troncs_treball'>";
	echo "<option value='0000-00-00' selected>Tria assaig...</option>";
	while ($row_assaigs = mysqli_fetch_array($assaig)) {
		$var_data_catalana_2 = date("d-m-Y",strtotime($row_assaigs['data']));
		echo "<option value='" . $row_assaigs['data'] . "'>" . $var_data_catalana_2 . "</option>";
	}
	echo "</select>";
	echo "<a href='#' data-role='button' data-inline='true' onclick=\"javascript:data_guio =jQuery('#selector_troncs_treball option:selected').val(); if(data_guio != '0000-00-00'){mostra_guio_popup(data_guio,'false');}\"><i class='lIcon fa fa-eye'></i></a>";
	/*echo "<a href='#' data-role='button' data-inline='true' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" . $avui . "','no');\" data-theme='b'><i class='lIcon fa fa-copy'></i></a>";
	echo "<a href='#' data-role='button' data-inline='true' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" .$avui . "','si');\"><i class='lIcon fa fa-user'></i></a>";*/
?>