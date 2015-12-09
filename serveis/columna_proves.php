<?php
	define('INCLUDE_CHECK',true);

	require 'connect.php';
	$var_data = $_POST['data'];
	$var_id_diada = $_POST['id_diada'];
	$var_es_diada = $_POST['es_diada'];
	$con_llista = mysql_connect( $db_host, $db_user, $db_pass);
	if(!$con_llista)
	    die('Could not connect: ' . mysql_error() );
	 
	mysql_select_db($db_database, $con_llista);
	$cont = 0;
	$var_num_seleccionats = 0;
	//$var_tipus = array("pinyes","troncs");
	$tipus_actual = "troncs";
	
	echo "<ul class='columna_proves'>";
	/*echo "<li id='" . $tipus_actual . "'><h3 class='columna_proves'><i class='lIcon fa fa-list-alt'></i> CASTELLS <a href='#' data-rel='close'><i class='lIcon fa fa-times'></i></a></h3><ul class='columna_proves'>";*/
	echo "<li id='" . $tipus_actual . "'><h3 class='columna_proves'><i class='lIcon fa fa-list-alt'></i> CASTELLS</h3><ul class='columna_proves'>";
	if ($var_data == "0000-00-00" or $var_es_diada == "s") $query = "select * from proves where tipus = 'troncs' order by prova";
	else $query = "select * from proves where tipus != '' order by tipus DESC , prova ASC";
	$proves = mysql_query($query,$con_llista);
	if ($var_es_diada == "s") $funcio = "afegir_castell_diada('" . $var_id_diada . "'";
	else $funcio = "afegir_prova_guio('" . $var_data . "'";
	while($prova = mysql_fetch_array($proves))
		{
			if ($prova['tipus'] != $tipus_actual) {
				$tipus_actual = $prova['tipus'];
				echo "</ul><li id='" . $tipus_actual . "'><h3 class='columna_proves'><i class='lIcon fa fa-list-alt'></i> " . $tipus_actual . "</h3><ul class='columna_proves'>";
			}
			echo ("<li><i class='lIcon fa fa-list-alt'></i><a href='#'  id='" . $prova['prova'] . "' class = 'prova' onclick=\"javascript:" . $funcio . ",'". $prova['prova'] ."','". $prova['tipus'] . "');return false;\"> ". $prova['prova'] . "</a></li>");
			$cont++;
		}
	echo "</ul>";
	mysql_close($con_llista);
?>