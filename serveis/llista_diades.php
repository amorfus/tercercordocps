<?php
	define('INCLUDE_CHECK',true);

	require 'connect.php';
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);
	/*$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');*/
	setlocale(LC_ALL, 'ca_ES');

	if (isset($_POST['tipus'])) $var_tipus = $_POST['tipus'];
	$avui = date("Y-m-d");
	$trobada_propera = 0;
	$query_diades = "SELECT * FROM diades WHERE YEAR(data) = YEAR(Now()) ORDER BY data";
	$result = mysqli_query($con,$query_diades);
	
	echo "<!--<div class='inset'>--><ul id='llista_diades'>";
	/*echo "<ul data-nativedroid-plugin='cards' class='nativeDroidCards' id='llista_diades'>";*/
	$mes_actual = "Desembre";
	$diada_passada = "";
	/*$v_avui = DateTime::createFromFormat('dmy', $_GET['date']);*/
	$v_avui = new DateTime('now');

	if ($var_tipus == 'compacte') {
		while($row = mysqli_fetch_array($result)) {
			$var_diada_id = $row['id_diada'];
			$var_data_actuacio = date("d-m-Y",strtotime($row['data']));
			$var_dia_actuacio = strftime("%d",strtotime($row['data']));
			$var_mes_actuacio = htmlspecialchars(ucfirst(strftime("%B",strtotime($row['data']))));
			$var_hora_actuacio = date("h:i",strtotime($row['hora']));
			$var_motiu_actuacio = htmlspecialchars($row['motiu']);
			$diff = date_diff (date_create($row['data']) , date_create($avui));
			if ($diff->format('%M') == "00" && $trobada_propera == 0) {
				echo "<div id='propera_diada'></div>";
				$trobada_propera = 1;
			}
			$v_data_actuacio = new DateTime($row['data']);
			$interval = date_diff($v_avui, $v_data_actuacio);
			$interval = intval($interval->format('%r%a'));
			if ($interval < 0) $diada_passada = "diada_passada";
			else $diada_passada = "";
			if ($var_mes_actuacio != $mes_actual) {
				/*echo "<li nav class='mes_llista_diades' data-role='list-divider'>" . $var_mes_actuacio . "</li>";*/
				$nou_mes="nou_mes_compacte";
				$mes_actual = $var_mes_actuacio;
			}
			else $nou_mes= "no_nou_mes";
			echo "<li class='compacte " . $nou_mes . " " . $diada_passada . "'><nav  class='tercercordo-properes'><a id='" . $var_data_actuacio . "' class='opcio_menu' href='#' onclick=\"javascript:localStorage.setItem('seccio_actual','copdull');carrega_copdull('diada'," . $var_diada_id . ");\"><p class='calendar'><span class='motiu_diada'>" . htmlspecialchars($var_motiu_actuacio) . "</span>" . $var_dia_actuacio . "<em>" . $var_mes_actuacio  . "</em></p>";
			echo "</a></nav></li>";
		
			/*echo "<li>
				<div class='diada' name='" . $row['id_diada'] . "'>" . htmlspecialchars($row['motiu']) . "<br>" . htmlspecialchars($row['lloc']) . "<br>";
			echo htmlspecialchars($row['data']) . " " . htmlspecialchars($row['hora']);
			echo (strlen($row['colles']) > 0? "  (" . htmlspecialchars($row['colles']) . ")" : "");
			echo "<span>";
			echo "</span>";
			echo "</div></li>";*/
		} // while
	}
	else {
		while($row = mysqli_fetch_array($result)) {
			$var_diada_id = $row['id_diada'];
			$var_data_actuacio = date("d-m-Y",strtotime($row['data']));
			$var_dia_actuacio = strftime("%d",strtotime($row['data']));
			$var_mes_actuacio = htmlspecialchars(ucfirst(strftime("%B",strtotime($row['data']))));
			$var_hora_actuacio = date("h:i",strtotime($row['hora']));
			$var_motiu_actuacio = htmlspecialchars($row['motiu']);
			$diff = date_diff (date_create($row['data']) , date_create($avui));
			if ($diff->format('%M') == "00" && $trobada_propera == 0) {
				echo "<div id='propera_diada'></div>";
				$trobada_propera = 1;
			}
			$v_data_actuacio = new DateTime($row['data']);
			$interval = date_diff($v_avui, $v_data_actuacio);
			$interval = intval($interval->format('%r%a'));
			if ($interval < 0) $diada_passada = "diada_passada";
			else $diada_passada = "";
			if ($var_mes_actuacio != $mes_actual) {
				/*echo "<li nav class='mes_llista_diades' data-role='list-divider'>" . $var_mes_actuacio . "</li>";*/
				$nou_mes="nou_mes";
				$mes_actual = $var_mes_actuacio;
			}
			else $nou_mes= "no_nou_mes";
            $mes_actual = htmlentities($mes_actual);
			echo "<li class='wow animated bounceInUp " . $nou_mes . " " . $diada_passada . "'><nav  class='tercercordo-properes'><span class='" . $nou_mes . "'>". $mes_actual . "</span><a id='" . $var_data_actuacio . "' class='opcio_menu' href='#' onclick=\"javascript:localStorage.setItem('seccio_actual','copdull');carrega_copdull('diada'," . $var_diada_id . ");\"><p class='calendar'>" . $var_dia_actuacio . "<em>" . $var_mes_actuacio  . "</em></p>
				<h1 class='properes_dates'>" . $var_motiu_actuacio . "</h1><span>" . htmlspecialchars($row['lloc']) . " " . $var_hora_actuacio . "</span><br>";
			echo "<span>Colles: " . htmlspecialchars($row['colles']) . "</span></a></nav></li>";
		
			/*echo "<li>
				<div class='diada' name='" . $row['id_diada'] . "'>" . htmlspecialchars($row['motiu']) . "<br>" . htmlspecialchars($row['lloc']) . "<br>";
			echo htmlspecialchars($row['data']) . " " . htmlspecialchars($row['hora']);
			echo (strlen($row['colles']) > 0? "  (" . htmlspecialchars($row['colles']) . ")" : "");
			echo "<span>";
			echo "</span>";
			echo "</div></li>";*/
		} // while
	}

	echo "</ul><!--</div>-->";
	if ($trobada_propera == 0) {
		echo "<div id='propera_diada'></div>";
	}
	mysqli_close($con);
?>
</form>