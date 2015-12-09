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
	
	$var_accio = $_GET['accio'];
	$var_accio = $_POST['accio'];
	if ($var_accio == "actualitza") {
		foreach ($_POST as $key => $value)
			if($key != "accio") //Prevent the submit button's name and value from being inserted into the db
				{
					$query = "UPDATE resultats SET resultat = '" . $value . "' WHERE resultat_id = " . $key;
					mysqli_query($con,$query);
				}
		echo "Castells actualitzats";
	}
	else {
		$var_diada_id = $_GET['diada_id'];
		$var_diada_id = $_POST['diada_id'];
		if ($var_diada_id=="0000-00-00") {
			$query = "SELECT * FROM resultats WHERE diada_id=(SELECT max(diada_id) FROM resultats) ORDER by ronda";
		}
		else {
			$query = "SELECT * FROM resultats WHERE diada_id='" .$var_diada_id . "'  ORDER by ronda";
		}
		$resultats = mysqli_query($con,$query);
		$reg = mysqli_affected_rows ($con); 
		if (!$resultats) echo "buit";
		else {
			$array_castells = array();
			while($row = mysqli_fetch_assoc($resultats))
			{
			    $var_diada_id = $row['diada_id'];
			    $resultat_id = $row['resultat_id'];
			    $castell = $row['castell_id'];
			    $resultat = $row['resultat'];
			    $array_castells[] = array('resultat_id'=>$resultat_id,'castell'=>$castell,'resultat'=>$resultat);
			}
		
			$query2 = "SELECT * from diades WHERE id_diada='" . $var_diada_id . "'";
			$diada = mysqli_query($con,$query2);
			$row_diada = mysqli_fetch_assoc($diada);
		}
		$var_data_formatada = date("d-m-Y",strtotime($row_diada['data']));
		echo "<div class='inset'><h2>" . htmlentities($row_diada['motiu']) . "</h2><h3>" . htmlentities($row_diada['lloc']) . " (" . $var_data_formatada . ")</h3>";
		echo "<form id='resultats_diada_form' action='' data-theme='b'>";
		foreach ($array_castells as $i => $castell)
		{
			for ($n=0;$n<5;$n++) { $seleccionat[$n] = ""; }
			switch($castell['resultat']) {
				case "": 	$seleccionat[0] = "selected";
						break;
				case "c": 	$seleccionat[1] = "selected";
						break;
				case "d": 	$seleccionat[2] = "selected";
						break;
				case "i": 	$seleccionat[3] = "selected";
						break;
				case "id": 	$seleccionat[4] = "selected";
						break;
				default:	$seleccionat[0] = "selected";
			}
			/*echo "<label for='" . $castell['resultat_id'] . "' class='select'  data-theme='b'>" . $castell['castell'] . "</label>";*/
			echo "<div class='lg-select style1'><span class='lg-placeholder'><i class='lIcon fa fa-sign-out'></i> " . $castell['castell'] . "</span>";
			echo "<select name='" . $castell['resultat_id'] . "' id='" . $castell['resultat_id'] . "' data-native-menu='true' data-theme='b'>
					<option value='' " . $seleccionat[0] . "></option>
					<option value='c' " . $seleccionat[1] . ">Carregat</option>
					<option value='d' " . $seleccionat[2] . ">Descarregat</option>
					<option value='i' " . $seleccionat[3] . ">Intent</option>
					<option value='id' " . $seleccionat[4] . ">Intent desmuntat</option>
				</select></div>";
		}
		if ($reg > 0)
			echo "<button type='submit' onclick='javascript:modifica_resultats();return false;'  data-theme='b'>Actualitza</button></form>";
		$query_diades = "SELECT motiu,id_diada FROM diades WHERE YEAR(data) = YEAR(Now()) ORDER BY data";
		$diades = mysqli_query($con,$query_diades);
		echo "<select name='selector_diada_resultats' id='selector_diada_resultats'>";
		echo "<option value='0000-00-00' selected>Tria diada...</option>";
		while($row_diades = mysqli_fetch_assoc($diades))
		{
			echo "<option value='" . $row_diades['id_diada'] . "'>" . htmlentities($row_diades['motiu']) . "</option>";
		}
		echo "</select></div>"; 
		echo "<script  type='text/javascript' charset='utf-8'>$('#selector_diada_resultats').change(function() {
					var diada_id = $('option:selected', this).attr('value');
					mostra_resultats(diada_id);
				});</script>";
	}
	mysqli_close($con);
?>