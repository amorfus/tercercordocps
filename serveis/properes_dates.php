<?php
	define('INCLUDE_CHECK',true);
	
	require 'connect.php';
	session_name('tzLogin');
	session_set_cookie_params(2*7*24*60*60);
	/*$l = @setlocale(LC_TIME, 'ca_ES.UTF-8', 'ca_ES', 'cat', 'ca', 'catalan', 'ca_ES.ISO_8859-1');*/
	setlocale(LC_ALL, 'ca_ES');
	$con = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con)) {
		echo("Error de conexio: ". mysqli_connect_error());
		exit();
	}
	if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];
	else $consulta = "actuacions";
	
	switch ($consulta) {
		case "actuacions": 	$actuacions_query = mysqli_query($con,"SELECT * from diades where data>='" . date('Y-m-d') . "' order by data,hora") or die (mysqli_error());
						/*echo "<h1 class=\"properes\">Properes Actuacions</h1>";*/
						for ($i=0; $i<3;$i++)
							{
								$row_actuacio = mysqli_fetch_array($actuacions_query);
								$var_diada_id = $row_actuacio['id_diada'];
								$var_data_actuacio = date("d-m-Y",strtotime($row_actuacio['data']));
								$var_motiu_actuacio = $row_actuacio['motiu'];
								
								echo "<div class='inset'><a id='" . $var_data_actuacio . "' href='#' onclick=\"javascript:neteja_properes();carrega_copdull('diada'," . $var_diada_id . ");\">
									<h1 class='properes_dates'>" . htmlentities($var_motiu_actuacio) . "</h1><span><strong>" . htmlentities($row_actuacio['lloc']) . "</strong> el <strong>" . $var_data_actuacio . "</strong> a les <strong>" . $row_actuacio['hora'] . "</strong></span><br>";
								echo "<span>Colles: " . htmlentities($row_actuacio['colles']) . "</span></a>";
								echo "</div><hr>";
							}
						break;
		case "properes_actuacions": 	$actuacions_query = mysqli_query($con,"SELECT * from diades where data>='" . date('Y-m-d') . "' order by data,hora LIMIT 3") or die (mysqli_error());
						/*echo "<h1 class=\"properes\">Properes Actuacions</h1>";*/
						
								while ($row_actuacio = mysqli_fetch_array($actuacions_query)) {
									$var_diada_id = $row_actuacio['id_diada'];
									$var_data_actuacio = date("d-m-Y",strtotime($row_actuacio['data']));
									$var_dia_actuacio = strftime("%d",strtotime($row_actuacio['data']));
									$var_mes_actuacio = htmlentities(ucfirst(strftime("%B",strtotime($row_actuacio['data']))));
									$var_hora_actuacio = date("h:i",strtotime($row_actuacio['hora']));
									$var_motiu_actuacio = $row_actuacio['motiu'];
									
									echo "<a id='" . $var_data_actuacio . "' href='#' onclick=\"javascript:neteja_properes();carrega_copdull('diada'," . $var_diada_id . ");\"><p class='calendar'>" . $var_dia_actuacio . "<em>" . $var_mes_actuacio  . "</em></p>
										<h1 class='properes_dates'>" . htmlentities($var_motiu_actuacio) . "</h1><span>" . htmlentities($row_actuacio['lloc']) . " " . $var_hora_actuacio . "</span><br>";
									echo "<span>Colles: " . htmlentities($row_actuacio['colles']) . "</span></a>";
								}
							
						break;
						
		case "assaigs":		/* de moment no el faig servir */
						/*echo "<h1 class=\"properes\">Propers Assaigs</h1>";*/
						// date('w') returns a string numeral as follows:
						//   '0' Sunday
						//   '1' Monday
						//   '2' Tuesday
						//   '3' Wednesday
						//   '4' Thursday
						//   '5' Friday
						//   '6' Saturday
						function esDimarts($date) {
						    return date('w', strtotime($date)) === '2';
						}
						function esDivendres($date) {
						    return date('w', strtotime($date)) === '5';
						}
						$data_a_tractar = date('Y-m-d');
						$ocurrencies = 0;
						while ($ocurrencies < 3) {
						    if (esDimarts($data_a_tractar)) {
							echo "<div class='inset'><a id='xx' href=''><h1 class='properes_dates'>Assaig Dimarts</h1></a><span>" . $data_a_tractar . "</span>";
							echo "</div><hr>";
							$ocurrencies++;
						    }
						    if (esDivendres($data_a_tractar)) {
							echo "<div class='inset'><a id='xx' href=''><h1 class='properes_dates'>Assaig Divendres</h1></a><span>" . $data_a_tractar . "</span>";
							echo "</div><hr>";
							$ocurrencies++;
						    }
						    $data_a_tractar = date('Y-m-d', strtotime("{$data_a_tractar} + 1 days"));
						}
						break;
		case "activitats":	echo "<h1 class=\"properes\">Properes Activitats</h1><p></p>";
						break;
	}
	mysqli_close($con);
?>