<?php
	define('INCLUDE_CHECK',true);
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);
	$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');
	
	require 'connect.php';
	$meth = $_SERVER['REQUEST_METHOD'];
	if($meth == 'GET') {
		$id = $_GET['id'];
		$camp = $_GET['camp'];
		$valor = $_GET['valor'];
	}
	else if($meth == 'POST') {
		$id = $_POST['id'];
		$camp = $_POST['camp'];
		$valor = $_POST['valor'];
	}

	if ($camp == "borrar") {
		$query = "DELETE FROM cens WHERE id='" . $id . "'";
	}
	else {
		$query = "UPDATE cens SET `" . $camp ."`='" . $valor . "' WHERE id='" . $id . "'";
	}

	$con_cens = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con)) {
		echo("Error de conexio: ". mysqli_connect_error());
		exit();
	}
	
	mysqli_query($con_cens,$query);
	mysqli_close($con_cens);
?>