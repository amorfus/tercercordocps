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
	echo "<div id='previsio_mensual'>";
	$avui = date("Y-m-d");
	
	
	echo "<div class='inset'>";
	
	/* agafo en un array els ids de la gent de troncs */
	$query1 = "SELECT id,nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY nom_pinya";
	$result = mysqli_query($con,$query1);
	$var_num_total = mysqli_affected_rows($con);
	$arr_id_troncs = array();
	while($cens_troncs = mysqli_fetch_array($result))
	{
		$arr_id_troncs[$cens_troncs['id']] = $cens_troncs['nom_pinya'];
	}
	$arr_troncs = join(',',array_keys($arr_id_troncs));

	/* creo un array [assaigs] amb les dates dels propers  dimarts i divendres en dos mesos */ 
	$d = new DateTime('-2 days');
	$inc = new DateInterval('P1D');
	$dates = array();
	$dades_data =array();
	$dies_assaig = array(2,5);
	for ($i=0; $i<60; ++$i) {
		$d = $d->add($inc);
		if (in_array($d->format('w'), $dies_assaig)) {
			$v = $d->format('Y-m-d');
			$t = $d->format('Y-m-d D');
			$dates[] = $v;
			$dades_data[$v] = "assaig";
		}
	}
	/* hi afegeixo les properes 6 diades i reordeno */
	/*$sql = "SELECT DISTINCT diada FROM assistencia where diada > '2014-01-01' ORDER BY diada";*/
	$sql = "SELECT DISTINCT id_diada, data, motiu, lloc FROM diades where data >= '" . $avui . "' ORDER BY data LIMIT 6";
	$res = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_array($res)) {
	    $dates[] = $row[1];
	    $dades_data[$row[1]] = htmlentities($row['motiu']) . "<span>" . htmlentities($row['lloc']) . "</span>";
	}
	asort($dates);
	$arr_ids = join(',',array_keys($arr_id_troncs));
	echo "<ul class='cbp_tmtimeline'>";
	/*$arr_dates = join(",",$dates);*/
	foreach ($dates as $k=>$v) {
		/*$sql = "SELECT a.id,a.diada,a.previst FROM assistencia a WHERE (a.id IN ($arr_ids) AND a.diada IN ('".implode("','",$dates)."')) ORDER BY a.diada";*/
		$sql = "SELECT a.id,a.diada,a.previst FROM assistencia a WHERE (a.id IN ($arr_ids) AND a.diada = '$dates[$k]') ORDER BY a.id";
		$res = mysqli_query($con,$sql);
		$var_cnt = mysqli_affected_rows($con);
		/*if ($var_cnt > 0) {*/
			/*$sql = "SELECT a.id,a.diada,a.nom_pinya,a.previst,c.id FROM assistencia a, cens c WHERE (c.data_baixa = '0000-00-00' AND (c.estat = 'A' OR c.estat = 'MA' or c.estat = 'O') AND c.troncs = '1' AND a.id=c.id AND YEAR(a.diada) = '2014') ORDER BY a.nom_pinya";*/
			
			$data_actual = $dates[$k];
			$troncs_assistents = array();
			$troncs_baixes = array();
			$cad_troncs_assistents = '';
			while (list($id, $d, $s) = mysqli_fetch_array($res)) {
				if ($data_actual != $d) {
					$cad_troncs_assistents = implode($troncs_assistents,', ');
					$cad_troncs_baixes = implode($troncs_baixes,', ');
					echo "<li class='" . $dades_data[$data_actual] . "'><time class='cbp_tmtime' datetime='" . $data_actual . "'><span>" . date("j/n/Y",strtotime($data_actual)) . "</span> <span>12:00</span></time>";
					echo "<div class='cbp_tmicon cbp_tmicon-phone'><i class='lIcon fa fa-calendar'></i></div>";
					echo "<div class='cbp_tmlabel'><h2>". htmlentities($dades_data[$data_actual]) ."</h2><p>" . htmlentities($cad_troncs_assistents). "</p>";
					echo "<p class='baixes'>" . htmlentities($cad_troncs_baixes) . "</p></div></li>";
					$data_actual = $d;
					unset($troncs_assistents);
					unset($troncs_baixes);
					$troncs_assistents = array();
					if ($s == '1')
						$troncs_assistents[] = $arr_id_troncs[$id];
					else
						$troncs_baixes[] = $arr_id_troncs[$id];
				}
				else {
					if ($s == '1')
						$troncs_assistents[] = $arr_id_troncs[$id];
					else
						$troncs_baixes[] = $arr_id_troncs[$id];
				}  
			}
			$cad_troncs_assistents = implode($troncs_assistents,', ');
			$cad_troncs_baixes = implode($troncs_baixes,', ');
			echo "<li class='" . $dades_data[$data_actual] . "'><time class='cbp_tmtime' datetime='" . $data_actual . "'><span>" . date("j/n/Y",strtotime($data_actual)) . "</span> <span>12:00</span></time>";
			echo "<div class='cbp_tmicon cbp_tmicon-phone'><i class='lIcon fa fa-calendar'></i></div>";
			echo "<div class='cbp_tmlabel'><h2>". $dades_data[$data_actual] ."</h2><p>" . htmlentities($cad_troncs_assistents). "</p>";
			echo "<p class='baixes'>" . htmlentities($cad_troncs_baixes) . "</p></div></li>";
					
			
		/*}
		else {
			echo "Sense dades.";
		}*/
	}
	echo "</ul>";
	echo "</div>";


	echo "</div>"; /* id=full_troncs */
	mysqli_close($con);
?>