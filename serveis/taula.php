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
	
	$sql = "SELECT DISTINCT diada FROM assistencia where diada > '2014-01-01' ORDER BY diada";
	$res = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_array($res)) {
	    $dates[] = $row[0];
	}
	/***********************************
	* Table headings                   *
	************************************/
	$emptyRow = array_fill_keys($dates,'');
	// format dates
	foreach ($dates as $k=>$v) {
	    $dates[$k] = date('d-M-Y', strtotime($v));
	}
	$heads = "<table border='1'>\n";
	/*$format_taula = "</th><th class='petit '" . $data_posterior . ">";*/
	$heads .= "<tr><th>Nom</th><th class='petit'>" . join('</th><th class="petit">', $dates) . "</th></tr>\n";

	/***********************************
	* Main data                        *
	************************************/
	/*$query = "SELECT id,nom_pinya FROM cens where (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1') order by nom_pinya";*/
	$sql = "SELECT a.id,a.diada,a.nom_pinya,a.previst,c.id FROM assistencia a, cens c WHERE (c.data_baixa = '0000-00-00' AND (c.estat = 'A' OR c.estat = 'MA' or c.estat = 'O') AND c.troncs = '1' AND a.id=c.id AND YEAR(a.diada) = '2014') ORDER BY a.nom_pinya";
	$res = mysqli_query($con,$sql);
	$curname='';
	$tdata = '';
	while (list($id, $d, $sn, $s, $cens_id) = mysqli_fetch_array($res)) {
	    if ($curname != $sn) {
		if ($curname) {
		    $tdata .= "<tr><td>$curname</td><td>" . join('</td><td>', $rowdata). "</td></tr>\n";
		}
		$rowdata = $emptyRow;
		$curname = $sn;
	    }
	    $rowdata[$d] = $s;
	}
	$tdata .= "<tr><td>$curname</td><td>" . join('</td><td>', $rowdata). "</td></tr>\n";
	$tdata .= "</table\n";
	?>
	<html>
	<head>
	<style type="text/css">
	td {
	    text-align: center;
	}
	table {
	    border-collapse:collapse;
	}
	th.petit{
		font-size: 0.3em;
		}
	</style>
	</head>
	<body>
	    <?php
		echo $heads;
		echo $tdata;
	    ?>
	</body>
	</html>