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
	

    
		
	/*echo "<ul class='columna_proves'>";*/
	
	/*echo "<li id='" . $tipus_actual . "'><h3 class='columna_proves'><i class='lIcon fa fa-list-alt'></i> CASTELLS</h3>";*/
	if ($var_data == "0000-00-00" or $var_es_diada == "s") $query = "select * from proves where tipus = 'troncs' order by prova";
	else $query = "select * from proves where tipus != '' order by tipus DESC , prova ASC";
	$proves = mysql_query($query,$con_llista);
	if ($var_es_diada == "s") $funcio = "afegir_castell_diada('" . $var_id_diada . "'";
	else $funcio = "afegir_prova_guio('" . $var_data . "'";
	echo "<div class='row around-xs'>";
    while($prova = mysql_fetch_array($proves))
		{
			if ($prova['tipus'] != $tipus_actual) {
				$tipus_actual = $prova['tipus'];
				/*echo "<li id='" . $tipus_actual . "'><h3 class='columna_proves'><i class='lIcon fa fa-list-alt'></i> " . $tipus_actual . "</h3>";*/
			}
			/*echo ("<div class='col-xs-auto'><li><i class='zmdi zmdi-assignment zmd-2x'></i><a href='#'  id='" . $prova['prova'] . "' class = 'prova ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button waves-effect waves-button'  data-ajax='false' onclick=\"javascript:" . $funcio . ",'". $prova['prova'] ."','". $prova['tipus'] . "');return false;\"> ". $prova['prova'] . "</a></li></div>");*/
            echo ("<div class='col-xs-auto'><a href='#'  id='" . $prova['prova'] . "' class = 'prova ui-bottom-sheet-link ui-btn ui-btn-inline ui-btn-raised clr-primary waves-effect waves-button'  data-ajax='false' onclick=\"javascript:" . $funcio . ",'". $prova['prova'] ."','". $prova['tipus'] . "');return false;\"> ". $prova['prova'] . "</a></div>");
			$cont++;
		}
	echo "</div>";
	mysql_close($con_llista);
?>