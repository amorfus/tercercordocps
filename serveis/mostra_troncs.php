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
	
	$avui = date("Y-m-d");
	
	if (isset ($_POST['tipus']) && $_POST['tipus'] == "diada") {
		/*$var_data_catalana = date("d-m-Y",strtotime($var_data));*/
		$var_id_diada = $_POST['id_data'];
		$castells = mysqli_query($con,"SELECT * FROM resultats WHERE diada_id = '" . $var_id_diada . "' ORDER BY ronda") or die (mysql_error());
		$var_cnt = mysqli_affected_rows($con);
		$array_tronc = array();
		$llista_castells = "";
		echo "<h1>Troncs Diada " . $var_id_diada . "</h1>";
		while ($row_castell = mysqli_fetch_array($castells)) {
			$var_tronc_id = $row_castell['resultat_id'];
			$query = "SELECT * FROM troncs WHERE (tronc_id = '" . $var_tronc_id . "' AND diada_id = '" . $var_id_diada . "') ORDER BY posicio_tronc";
			$tronc = mysqli_query($con,$query);
			
			unset($array_tronc);
			while($bandarra = mysqli_fetch_array($tronc)) {
				$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
				if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }
				$query2 = "SELECT altura,pes from cens WHERE id='" . $bandarra[id] . "'";
				$result = mysqli_query($con,$query2) or exit(mysql_error());
				$mides = mysqli_fetch_array($result);
				$var_altura = $mides['altura'];
				$var_pes = $mides['pes'];

				$array_tronc[$bandarra[posicio_tronc]]= array('id'=>$bandarra[id],'nom_pinya'=>$bandarra[nom_pinya],'posicio'=>$bandarra[posicio_tronc],'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes,'ocupat'=>'ocupat');
			}
			
			/** dibuixa tronc **/
			$var_tronc = $row_castell['castell_id'];
			$llista_castells = $llista_castells . " " . $var_tronc;
			$cadena1 = explode("d",$var_tronc);
			$estructura = $cadena1[0];
			if (substr($var_tronc, -1) == 'a') $estructura++;
			$pisos_total = $cadena1[1];
			if ($estructura == "p") {$amplada_tronc = 60; $estructura = 1; }
			else { $amplada_tronc = intval($estructura) * 60; $pisos_total = $pisos_total - 2; }
			/*echo "<div class='tronc' id='" . $var_tronc_id . "' style='width:" . $amplada_tronc . "px'>";*/
			echo "<div class='tronc' id='" . $var_tronc_id . "'>";
			if (substr($row_castell['castell_id'],0,5)!="Pinya") echo "<h1>" . $row_castell['castell_id'] . "</h1>";
			
			for ($rengles = 1; $rengles <= $estructura; $rengles++) {
				$altura_rengla = 0;
				$pes_rengla = 0;
				echo "<ul id='rengla_sortable" . $rengles . "' class='bandarra_tronc connectedSortable'>";
				for ($pisos = $pisos_total; $pisos > 0 ; $pisos--) {
					$posicio_actual = "r" . $rengles . "p" . $pisos;
					if ($array_tronc[$posicio_actual][altura] == 0) $te_altura = "no-te-altura";
					else $te_altura = "";
					$altura_rengla = $altura_rengla + $array_tronc[$posicio_actual][altura];
					$pes_rengla = $pes_rengla + $array_tronc[$posicio_actual][pes];
					$altura = (intval($array_tronc[$posicio_actual][altura]) * 0.5);
					/*echo "<li class='ui-widget-content ui-corner-tr " . $array_tronc[$posicio_actual][ocupat] . " " . $te_altura . " ' id='" . $var_tronc_id . "_r" . $rengles . "p" . $pisos . "' style='height:" . $altura . "px;'>";*/
					echo "<li class='ui-widget-content ui-corner-tr " . $array_tronc[$posicio_actual][ocupat] . " " . $te_altura . " ' id='" . $var_tronc_id . "_r" . $rengles . "p" . $pisos . "' style='background: url(\"" . $array_tronc[$posicio_actual][foto] . "\") no-repeat center center;width:55px;height:" . $altura . "px;'>";
					echo "<h2 class='nom_pinya'>" . htmlentities($array_tronc[$posicio_actual][nom_pinya]) . "</h2>";
					echo "<span style='height:" . $altura . "px;display:block;' /></span><h2 class='altura'>" . $array_tronc[$posicio_actual][altura] . "</h2>";
					echo "</li>";
				}
				echo "<li id='dades_rengla" . $rengles . "' class='dades_rengla'><span>" . $altura_rengla . " cm<br>" . $pes_rengla . " kg</span></li>";
				echo "</ul>";
			}
			echo "</div>";
		}
		echo "<div class='que_tirem'>" . $llista_castells . "</div>";
	}
	else { /* no es diada */
		$var_data = $_POST['id_data'];
		/*$timestamp = time();
		$dia_setmana = date('D', $timestamp);
		if($dia_setmana === 'Tue' or $dia_setmana === 'Fri') {
			$var_data = date("Y-m-d");
		}
		else {
			$properDimarts= strtotime("first Tuesday");
			$properDivendres= strtotime("first Friday");
			$proper_dia = ($properDimarts > $properDivendres ? $properDivendres : $properDimarts);
			$var_data = date("Y-m-d",$proper_dia);
			}*/
		$var_data_catalana = date("d-m-Y",strtotime($var_data));
		echo "<h6>Troncs Assaig " . $var_data_catalana . "</h6>";
		$assaig = mysqli_query($con,"SELECT * FROM guions_assaig where data = '" . $var_data . "' ORDER BY position") or die (mysql_error());
		$var_cnt = mysqli_affected_rows($con);
		
		$array_tronc = array();
		while ($row_prova = mysqli_fetch_array($assaig)) {
			$var_tronc_id = $row_prova['id'];
			
			$query = "SELECT * FROM troncs WHERE tronc_id = '" . $var_tronc_id . "' ORDER BY posicio_tronc";
			$tronc = mysqli_query($con,$query);
			
			unset($array_tronc);
			while($bandarra = mysqli_fetch_array($tronc)) {
				$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
				if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }
				$query2 = "SELECT altura,pes from cens WHERE id='" . $bandarra[id] . "'";
				$result = mysqli_query($con,$query2) or exit(mysql_error());
				$mides = mysqli_fetch_array($result);
				$var_altura = $mides['altura'];
				$var_pes = $mides['pes'];

				$array_tronc[$bandarra[posicio_tronc]]= array('id'=>$bandarra[id],'nom_pinya'=>$bandarra[nom_pinya],'posicio'=>$bandarra[posicio_tronc],'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes,'ocupat'=>'ocupat');
			}
			
			/** dibuixa tronc **/
			$var_tronc = $row_prova['prova'];
			$cadena1 = explode("d",$var_tronc);
			$estructura = $cadena1[0];
			if (substr($var_tronc, -1) == 'a') $estructura++;
			$pisos_total = $cadena1[1];
			if ($estructura == "p") {$amplada_tronc = 60; $estructura = 1; }
			else { $amplada_tronc = intval($estructura) * 60; $pisos_total = $pisos_total - 3; }
			echo "<div class='tronc' id='" . $var_tronc_id . "' style='width:" . $amplada_tronc . "px'>";
			if (substr($row_prova['prova'],0,5)!="Pinya") echo "<h1>" . $row_prova['prova'] . "</h1>";
			
			for ($rengles = 1; $rengles <= $estructura; $rengles++) {
				$altura_rengla = 0;
				$pes_rengla = 0;
				echo "<ul id='rengla_sortable" . $rengles . "' class='bandarra_tronc connectedSortable'>";
				for ($pisos = $pisos_total; $pisos > 0 ; $pisos--) {
					$posicio_actual = "r" . $rengles . "p" . $pisos;
					if ($array_tronc[$posicio_actual][altura] == 0) $te_altura = "no-te-altura";
					else $te_altura = "";
					$altura_rengla = $altura_rengla + $array_tronc[$posicio_actual][altura];
					$pes_rengla = $pes_rengla + $array_tronc[$posicio_actual][pes];
					$altura = (intval($array_tronc[$posicio_actual][altura]) * 0.5);
					/*echo "<li class='ui-widget-content ui-corner-tr " . $array_tronc[$posicio_actual][ocupat] . " " . $te_altura . " ' id='" . $var_tronc_id . "_r" . $rengles . "p" . $pisos . "' style='height:" . $altura . "px;'>";*/
					echo "<li class='ui-widget-content ui-corner-tr " . $array_tronc[$posicio_actual][ocupat] . " " . $te_altura . " ' id='" . $var_tronc_id . "_r" . $rengles . "p" . $pisos . "' style='background: url(\"" . $array_tronc[$posicio_actual][foto] . "\") no-repeat center center;width:55px;height:" . $altura . "px;'>";
					echo "<h2 class='nom_pinya'>" . htmlentities($array_tronc[$posicio_actual][nom_pinya]) . "</h2>";
					echo "<span style='height:" . $altura . "px;display:block;' /></span><h2 class='altura'>" . $array_tronc[$posicio_actual][altura] . "</h2>";
					echo "</li>";
				}
				echo "<li id='dades_rengla" . $rengles . "' class='dades_rengla'><span>" . $altura_rengla . " cm<br>" . $pes_rengla . " kg</span></li>";
				echo "</ul>";
			}
			echo "</div>";
		}
	}
	mysqli_close($con);
?>