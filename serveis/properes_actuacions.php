<?php
	define('INCLUDE_CHECK',true);

	require 'connect.php';
	 
	 $con = mysql_connect( $db_host, $db_user, $db_pass);
	 if(!$con)
	   die('Could not connect: ' . mysql_error() );
	mysql_select_db($db_database, $con);
	$actuacions_query = mysql_query("SELECT * from diades where data>='" . date('Y-m-d') . "' order by data,hora") or die (mysql_error());
	echo "<h1 class=\"properes\">Properes Actuacions</h1><p></p>";
	for ($i=0; $i<3;$i++)
		{
			$row_actuacio = mysql_fetch_array($actuacions_query);
			$var_diada_id = $row_actuacio['id_diada'];
			$var_data_actuacio = date("d-m-Y",strtotime($row_actuacio['data']));
			$var_motiu_actuacio = $row_actuacio['motiu'];
			
			echo "<a id='" . $var_data_actuacio . "' href='/prog_pinyes/autoritzat.php?actuacio_select=" . $row_actuacio['data'] . "&diada_id=" . $var_diada_id . "'>
				<h2>" . $var_motiu_actuacio . "</h2></a><span>" . $row_actuacio['lloc'] . " el " . $var_data_actuacio . " a les " . $row_actuacio['hora'] . "</span><br>";
			echo "<br><span>Colles: " . $row_actuacio['colles'] . "</span>";
			echo "<hr>";
		}
?>