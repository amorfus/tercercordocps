<?php
	define('INCLUDE_CHECK',true);
	
	require 'connect.php';
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);

	$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');
	
	$con_tronc = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con)) {
		echo("Error de conexio: ". mysqli_connect_error());
		exit();
	}
	
	$var_tronc_id = urldecode($_GET['tronc_id']);
	if (strpos($var_tronc,'de') == false) {
	   $nom_alt = str_replace("d","de",$var_tronc);
	}
	else {
		$nom_alt =str_replace("de","d",$var_tronc);
	}
	$query = "SELECT * FROM troncs WHERE tronc_id = ' " . $var_tronc_id . "' ORDER BY posicio_tronc";
	$tronc = mysqli_query($con_tronc,$query);
	$estructura = "";
		while($bandarra = mysqli_fetch_array($tronc))
		{
			if ($estructura == "") {
				$cadena1 = explode("d",$bandarra['tronc']);
				$estructura = $cadena1[0];
				$pisos = $cadena1[1];
				echo "estructura: " . $estructura . " || pisos: " . $pisos;
			}
			/*if ($bandarra[tronc_id] != 0) {
				if ($bandarra[tronc_id] == $var_tronc_id) {
					$var_id = $bandarra[id];
					$var_posicio = $bandarra[posicio_tronc];
					$var_nom = $bandarra[nom_pinya];
					$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
					if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }
					$query2 = "SELECT altura,pes from cens WHERE nom_pinya='" . $var_nom . "'";
					$result = mysql_query($query2) or exit(mysql_error());
					$mides = mysql_fetch_array($result);
					$var_altura = $mides['altura'];
					$var_pes = $mides['pes'];
					
					$parell []= array('id'=>$var_id,'nom'=>$var_nom,'posicio'=>$var_posicio,'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes);
					$data= $data . $parell;
				}
			}
			else {
				$var_id = $bandarra[id];
				$var_posicio = $bandarra[posicio_tronc];
				$var_nom = $bandarra[nom_pinya];
				$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
				if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }
				$query2 = "SELECT altura,pes from cens WHERE nom_pinya='" . $var_nom . "'";
				$result = mysql_query($query2) or exit(mysql_error());
				$mides = mysql_fetch_array($result);
				$var_altura = $mides['altura'];
				$var_pes = $mides['pes'];
				
				$parell []= array('id'=>$var_id,'nom'=>$var_nom,'posicio'=>$var_posicio,'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes);
				$data= $data . $parell;
			}*/
		}
		/*echo json_encode($parell);*/
	/*}
	else
		echo "Buit.";*/
	
	$cadena1 = "r3p2";
	$cadena2 = explode("p",$cadena1);
	$pis = $cadena2[1];
	$cadena3 = explode("r",$cadena2[0]);
	$rengle = $cadena3[1];
	echo "pis: " . $pis . " || rengle: " . $rengle;
	
	
	
	/*<ul id="sortable1" class="connectedSortable">
<li class="ui-state-default">Item 1</li>
<li class="ui-state-default">Item 2</li>
<li class="ui-state-default">Item 3</li>
<li class="ui-state-default">Item 4</li>
<li class="ui-state-default">Item 5</li>
</ul>
<ul id="sortable2" class="connectedSortable">
<li class="ui-state-highlight">Item 1</li>
<li class="ui-state-highlight">Item 2</li>
<li class="ui-state-highlight">Item 3</li>
<li class="ui-state-highlight">Item 4</li>
<li class="ui-state-highlight">Item 5</li>
</ul>*/
	
	
	
	
	mysqli_close($con_tronc);
?>