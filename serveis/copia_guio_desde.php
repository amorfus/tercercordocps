<?php
define('INCLUDE_CHECK',true);

require 'connect.php';
session_name('tzLogin');
session_set_cookie_params(2*7*24*60*60);

$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');

$con_guio = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
if (mysqli_connect_errno($con_guio)) {
	echo("Error de conexio: ". mysqli_connect_error());
	exit();
}
$var_data_desde = $_POST["data_desde"];
//$var_data_desde = date("Y-m-d",strtotime($var_data_desde_formatada));
$var_data_oncopiar = $_POST["data_oncopiar"];
$var_copiar_troncs = $_POST["copiar_troncs"];
if ( $var_data_desde != "0000-00-00") {
	$query = "INSERT INTO guions_assaig (position,prova,text,data, tipus) (SELECT position,prova, text, '" . $var_data_oncopiar . "', tipus FROM guions_assaig  WHERE data = '" . $var_data_desde . "' ORDER BY position ASC)";
	$inserta = mysqli_query($con_guio,$query);
	echo "Guio copiat.";
	if ($var_copiar_troncs == "si") {
		$query = "SELECT g1.id as id_orig,g2.id as id_dest,g2.position as posicio,g2.prova as prova,g2.data as data FROM guions_assaig g1 left join guions_assaig g2 on (g1.position = g2.position) where g1.data='" . $var_data_desde . "' and g2.data = '" . $var_data_oncopiar . "' and g1.tipus = 'troncs'";
		/*$query = "SELECT id,prova FROM guions_assaig  WHERE data = '" . $var_data_oncopiar . "' ORDER BY position ASC";*/
		$result = mysqli_query($con_guio,$query);
		while ( $row_result = mysqli_fetch_array($result) ) {
			$query2 = "INSERT INTO troncs (diada_id,data,tronc_id,tronc,nom_pinya,id,posicio_tronc) (SELECT '999999','" . $var_data_oncopiar ."','" . $row_result[id_dest] ."','" . $row_result[prova] ."', nom_pinya,id,posicio_tronc FROM `troncs` WHERE tronc_id = '" . $row_result[id_orig] ."' ORDER BY posicio_tronc ASC)";
			$inserta2 = mysqli_query($con_guio,$query2);
		}
		echo "Troncs copiats de " . $var_data_desde . " a " . $var_data_oncopiar;
	}
}
else echo "Heu de seleccionar un assaig.";
mysqli_close($con_guio);
?>