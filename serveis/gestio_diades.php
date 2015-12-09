<?php 
define('INCLUDE_CHECK',true);

require 'connect.php';
if (isset($_REQUEST['accio'])) $accio = $_REQUEST['accio'];
else $accio="";

$con_diades = mysql_connect( $db_host, $db_user, $db_pass);
if(!$con_diades)
   die('Could not connect: ' . mysql_error() );
 
mysql_select_db($db_database, $con_diades);

switch ($accio) {
	case "esborra":
		$id_diada = $_POST['id_diada'];
		mysql_query("DELETE FROM diades WHERE id_diada = '" . $id_diada . "'");
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			echo("No es pot esborrar!");
	break;
	case "actualitza_resultat":
		$resultat_id = $_POST['resultat_id'];
		$resultat = $_POST['resultat'];
		mysql_query("UPDATE resultats SET resultat = '" . $resultat . "' WHERE resultat_id = '" . $resultat_id . "'");
	break;
	case "nova":
		$data1 = str_replace('/', '-', $_POST['data_diada']);
	    	$data_formatada = date('Y-m-d', strtotime($data1));
		$query_nova = "INSERT INTO 
					diades (data,hora,motiu,lloc,colles) 
					VALUES ('" . $data_formatada . "','" . $_POST['hora_diada'] . "','" . $_POST['motiu'] . "','" . $_POST['lloc'] . "','" . $_POST['colles'] . "')";
	    	mysql_query($query_nova) or die(mysql_error());
		echo "Diada afegida al calendari.";
	break;
	default:
		
}

/*
if ($accio == "esborra") {
	$id_diada = $_POST['id_diada'];
	mysql_query("DELETE FROM diades WHERE id_diada = '" . $id_diada . "'");
	if(mysql_affected_rows($GLOBALS['link'])!=1)
		echo("No es pot esborrar!");
}
if ($accio == "actualitza_resultat") {
	$resultat_id = $_POST['resultat_id'];
	$resultat = $_POST['resultat'];
	mysql_query("UPDATE resultats SET resultat = '" . $resultat . "' WHERE resultat_id = '" . $resultat_id . "'");
}
if ($accio == "nova") {
    $data1 = str_replace('/', '-', $_POST['data_diada']);
    $data_formatada = date('Y-m-d', strtotime($data1));
	$query_nova = "INSERT INTO diades (data,hora,motiu,lloc,colles) VALUES ('" . $data_formatada . "','" . $_POST['hora_diada'] . "','" . $_POST['motiu'] . "','" . $_POST['lloc'] . "','" . $_POST['colles'] . "')";
    mysql_query($query_nova) or die(mysql_error());
	echo "Diada afegida al calendari.";
}
*/
mysql_close($con_diades);
?>
