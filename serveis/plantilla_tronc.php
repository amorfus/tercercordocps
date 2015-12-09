<?php
	define('INCLUDE_CHECK',true);
	$var_tronc = $_POST['tronc'];
	$cadena1 = explode("d",$var_tronc);
	$estructura = $cadena1[0];
	$pisos_total = $cadena1[1];
	if ($estructura == 'p') $estructura = 1;
	else $pisos_total = $pisos_total - 3;
	for ($rengles = 1; $rengles <= $estructura; $rengles++) {
		echo "<ul id='rengla_sortable" . $rengles . "' class='bandarra_tronc connectedSortable'>";
		for ($pisos = $pisos_total; $pisos > 0 ; $pisos--) {
			/*echo "<li class='ratlla_pis'></li>";*/
			echo "<li class='ui-widget-content ui-corner-tr' id='r" . $rengles . "p" . $pisos . "'>";
			echo "</li>";
		}
		echo "<li id='dades_rengla" . $rengles . "'></li>";
		echo "</ul>";
	}
?>