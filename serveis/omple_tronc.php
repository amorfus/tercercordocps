<?php
	define('INCLUDE_CHECK',true);
	
	require 'connect.php';
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);

	$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');
	
	$con_tronc = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con_tronc)) {
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
	
	$var_accio = urldecode($_POST['accio']);
	if ($var_accio == "swap") {
		$var_tronc_id = urldecode($_POST['tronc_id']);
		if (strpos($var_tronc,'de') == false) {
		   $nom_alt = str_replace("d","de",$var_tronc);
		}
		else {
			$nom_alt =str_replace("de","d",$var_tronc);
		}
		$var_pos1 = urldecode($_POST['pos1']);
		$var_pos2 = urldecode($_POST['pos2']);
		$update = "UPDATE troncs AS troncs1 JOIN troncs AS troncs2 ON 
			((troncs1.tronc_id = '" . $var_tronc_id . "' AND troncs1.posicio_tronc = '" . $var_pos1 . "') AND (troncs2.tronc_id = '" . $var_tronc_id . "' AND troncs2.posicio_tronc = '" . $var_pos2 . "'))
			SET troncs1.posicio_tronc = troncs2.posicio_tronc, troncs2.posicio_tronc = troncs1.posicio_tronc;";
		/*echo $update;*/
		$result = mysqli_query($con_tronc,$update);
	}
	else {
	/*UPDATE
		rules AS rule1
		JOIN rules AS rule2 ON
		( rule1.rule_id = 1 AND rule2.rule_id = 4 )
		SET
		rule1.priority = rule2.priority,
		rule2.priority = rule1.priority; */
	
		while($bandarra = mysqli_fetch_array($tronc)) {
				/*if ($bandarra[tronc_id] != 0) {*/
					/*if ($bandarra[tronc_id] == $var_tronc_id) {*/
						$var_id = $bandarra[id];
						$var_posicio = $bandarra[posicio_tronc];
						$var_nom = $bandarra[nom_pinya];
						$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
						if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }
						$query2 = "SELECT altura,pes from cens WHERE nom_pinya='" . $var_nom . "'";
						$result = mysqli_query($con_tronc,$query2) or exit(mysql_error());
						$mides = mysqli_fetch_array($result);
						$var_altura = $mides['altura'];
						$var_pes = $mides['pes'];
						
						$parell []= array('id'=>$var_id,'nom'=>$var_nom,'posicio'=>$var_posicio,'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes);
						$data= $data . $parell;
					/*}*/
				/*}
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
		echo json_encode($parell);
	}
	mysqli_close($con_tronc);
?>