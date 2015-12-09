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
	
	$var_accio = $_POST['accio'];
	$var_grup = $_POST['grup'];
	if (isset($_POST['id_diada']))
		$var_id_diada = $_POST['id_diada'];
	else
		$var_id_diada = $_POST['id_esdeveniment'];
	$var_tipus = $_POST['tipus'];
	
		if ($var_accio == "actualitza") {
			if (empty($_POST['data']) and ($var_id_diada != "0000-00-00")) {
				echo "Error, manquen dades.";
				exit;
			}
			else {
				$var_data = $_POST['data'];
				$var_id_diada = $_POST['id_esdeveniment'];
			}
			if ($var_id_diada != "0000-00-00") {
				$query = "SELECT * from assistencia WHERE id_esdeveniment = '" . $var_id_diada . "'";
				/*$query = "SELECT * from assistencia WHERE previst <> '' AND id_esdeveniment = '" . $var_id_diada . "'";*/
			}
			else {
				$query = "SELECT * from assistencia WHERE diada = '" . $var_data . "'";
				/*$query = "SELECT * from assistencia WHERE previst <> '' AND diada = '" . $var_data . "'";*/
			}
			$assistents = mysqli_query($con,$query);
			$var_num_total = mysqli_affected_rows($con);
			$arr_id_ass = array();
			while ($row = mysqli_fetch_array($assistents))
			{
				$id_barr = $row['id'];
				$arr_id_ass[$id_barr] = $row['previst'];
			}
			asort($arr_id_ass);
			/*$arr_ass = new array();
			if ($var_num_total > 0) $arr_ass = join(',',$arr_id_ass);*/
			foreach ($_POST as $key => $value)
				if($key != "accio" and $key != "id_esdeveniment" and $key != "tipus" and $key != "data") //Prevent the submit button's name and value from being inserted into the db
					{
						switch ($value) {
							case "on": $valor = '1';
									break;
							case "off": $valor = '0';
									break;
						}
						$expl = explode("-",$key);
						$id_bandarra = $expl[1];
						$arr_post[$id_bandarra] = $valor;
					}
			asort($arr_post);
			$arr_baixes = array_diff_key($arr_id_ass,$arr_post);
			if (!$arr_baixes) $arr_baixes = array_diff($arr_post,$arr_id_ass);
			foreach($arr_baixes as $k => $v) {
			  $arr_baixes[$k] = 0;
			}
			$arr_post = $arr_post + $arr_baixes;
			foreach ($arr_post as $key => $value) {
				if ($var_id_diada != "0000-00-00") $query = "INSERT INTO assistencia (id,diada,id_esdeveniment,tipus,previst) VALUES ('" . $key . "','" . $var_data . "','" . $var_id_diada . "','" . $var_tipus . "','" . $value . "') ON DUPLICATE KEY UPDATE previst = " . $value;
				else $query = "INSERT INTO assistencia (id,diada,previst,tipus) VALUES ('" . $key . "','" . $var_data . "','" . $value . "','" . $var_tipus . "') ON DUPLICATE KEY UPDATE previst = " . $value; /* si és un assaig no guardo id_esdeveniment i ho deixo a 0 */
				
				/*INSERT INTO assistencia (id,diada,previst) VALUES ('28','2014-03-18','0') ON DUPLICATE KEY UPDATE previst = '0';*/
				mysqli_query($con,$query);
				
			}
		}
		else { /**** mostra l'assistencia d'avui *****/
            $var_texte_select_troncs = "";
            echo "<div id='assistencia_grup'>" . $var_grup . "</div>";
			if ($var_grup == "pinyes") {
                $query1 = "SELECT id,nom_pinya,nom,cognom_1,novato,estat,agulles,baixos,contraforts,cordo,crosses,laterals,mans,comodins FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '0'  AND canalla = '0') ORDER BY nom_pinya ASC, nom ASC";
                $result = mysqli_query($con,$query1);
                $var_num_total = mysqli_affected_rows($con);
                $arr_id_pinyes = array();
                $arr_posicions_pinyes = array();
                while($cens = mysqli_fetch_array($result))
                {
                    if ($cens['nom_pinya'] == '') $arr_id_pinyes[$cens['id']] = $cens['nom'] . " " . $cens['cognom_1'];
                    else $arr_id_pinyes[$cens['id']] = $cens['nom_pinya'];
                    if ($cens['agulles'] == 1) $arr_posicions_pinyes['agulles'][] = $cens['id'];
                    if ($cens['baixos'] == 1) $arr_posicions_pinyes['baixos'][] = $cens['id'];
                    if ($cens['contraforts'] == 1) $arr_posicions_pinyes['contraforts'][] = $cens['id'];
                    if ($cens['cordo'] == 1) $arr_posicions_pinyes['cordo'][] = $cens['id'];
                    if ($cens['crosses'] == 1) $arr_posicions_pinyes['crosses'][] = $cens['id'];
                    if ($cens['laterals'] == 1) $arr_posicions_pinyes['laterals'][] = $cens['id'];
                    if ($cens['mans'] == 1) $arr_posicions_pinyes['mans'][] = $cens['id'];
                    if ($cens['comodins'] == 1) $arr_posicions_pinyes['comodins'][] = $cens['id'];

                }
                
                 $var_motiu="";
                /* si $var_id_diada es 0000-00-00 vol dir que vinc per data (assaig) */
                if ($var_id_diada == "0000-00-00") {
                    if (empty($_POST["data"])) $var_data = date("Y-m-d");
                    else $var_data = $_POST["data"];
                    $query = "SELECT * FROM assistencia WHERE diada='" . $var_data . "' " . $var_texte_select_troncs . " order by nom_pinya";
                }
                else {
                    $row = mysqli_fetch_array(mysqli_query($con,"SELECT data,motiu from diades WHERE id_diada='" . $var_id_diada . "'"));
                    $var_data = $row['data'];
                    $var_motiu = $row['motiu'];
                    $query = "SELECT * FROM assistencia WHERE id_esdeveniment='" . $var_id_diada . "' " . $var_texte_select_troncs . " order by nom_pinya";
                }

                $assistents = mysqli_query($con,$query);
                $reg = mysqli_affected_rows ($con); 
                $array_assist = array();
                $array_ids = array();
                while($row = mysqli_fetch_array($assistents))
                {
                    $id_assist = $row['id']; /* no se si id_assist es pot eliminar */
                    $array_assist[$id_assist] = $row['previst'];
                    $array_ids[$id_assist] = $row['nom_pinya'];
                }

                $var_data_formatada = " <span>" . date("j-n-Y",strtotime($var_data)) . "</span>";
                echo "<div id='calendari_assistencia'></div>";
                echo "<form id='assistencia_modificable_form'>";
                echo "<fieldset   data-type='horizontal'  data-mini='true' id='assistencia'>
                        <div id='titol_pagina'>Assist&egrave;ncia " . ucfirst($var_grup) . "  <strong>" . htmlentities($var_motiu) . "</strong> " . $var_data_formatada . "</div>";
                
                /** prova de tabs, borrar **/
                
                /*echo "<div id='tabs_pos'><ul><li><a href='#fragment-1'><span>One</span></a></li><li><a href='#fragment-2'><span>Two</span></a></li><li><a href='#fragment-3'><span>Three</span></a></li></ul>";
                echo "<div id='fragment-1'><p>First tab is active by default:</p><pre><code>$( '#tabs' ).tabs(); </code></pre></div>";
                echo "<div id='fragment-2'>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</div>";
                echo "<div id='fragment-3'>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</div>";
                echo "</div>";
                
                
                echo "<div id='tabs_posicions'><ul data-role='nd2tabs' data-swipe='true' class='nd2Tabs'>";
                foreach($arr_posicions_pinyes as $key => $value) {
                    echo "<li class='nd2Tabs-nav-item waves-effect waves-button waves-light' data-tab=". $key .">" . $key ."</li>";
                }
                echo "</ul></div>";*/
                
                /** fi prova de tabs borrar **/

                foreach($arr_posicions_pinyes as $posicio => $bandarres) {
                    /*echo "<div data-role='nd2tab' data-tab='" . $posicio . "' class='nd2Tabs-content-tab'>";*/
                    echo "<div data-role='collapsible' data-collapsed='true' class='assistencia_pinyes_collapsible'><h4>" . $posicio . "</h4>";
                    echo "<ul class='assistencia' data-role='listview' data-inset='false' data-icon='false' data-autodividers='true'><div class='row_assistencia'>";
                    foreach($bandarres as $clau => $id_bandarra) {
                        /*echo "<li>" . $arr_id_pinyes[$id_bandarra] . "</li>";*/
                        if ($array_assist[$id_bandarra] == "1") { $var_ve = "checked"; $var_checkbox = "seleccionada"; }
                        else { $var_ve = ""; $var_checkbox = "no_seleccionada"; }
                        $foto = "images/gent/thumbs/" . $id_bandarra . ".png";
                        if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $id_bandarra . ".png")) { $foto = "images/gent/thumbs/ningu.png"; }
                        echo "<li class='wow animated bounceIn " . $var_checkbox . "' style='background: url(\"" . $foto . "\") no-repeat center center;' data-wow-duration='0.2s' data-wow-delay='0s'><input class='assist_chk' type='checkbox' name='checkbox-" . $id_bandarra . "' id='checkbox-" . $id_bandarra . "' " . $var_ve . ">
                        <label for='checkbox-" . $id_bandarra . "'>" . htmlentities($arr_id_pinyes[$id_bandarra]) . "</label></li>";
                        
                    }
                    echo "</div></ul></div>";
                    /*echo "</div>";*/
                }   

                echo "</fieldset></form>";
                
                
				/*echo "<div id='sorter'>
				    <ul data-role='listview'>
					<li><span>A</span></li>
					<li><span>B</span></li>
					<li><span>C</span></li>
					<li><span>D</span></li>
					<li><span>E</span></li>
					<li><span>F</span></li>
					<li><span>G</span></li>
					<li><span>H</span></li>
					<li><span>I</span></li>
					<li><span>J</span></li>
					<li><span>K</span></li>
					<li><span>L</span></li>
					<li><span>M</span></li>
					<li><span>N</span></li>
					<li><span>O</span></li>
					<li><span>P</span></li>
					<li><span>Q</span></li>
					<li><span>R</span></li>
					<li><span>S</span></li>
					<li><span>T</span></li>
					<li><span>U</span></li>
					<li><span>V</span></li>
					<li><span>W</span></li>
					<li><span>X</span></li>
					<li><span>Y</span></li>
					<li><span>Z</span></li>
				    </ul>
				</div><!-- /sorter -->";*/
                
			}
            else { /* veure assistencia per troncs o canalla */
                
                if ($var_grup == "troncs") $query1 = "SELECT id,nom_pinya,pis8,pis7,altura FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY pis8 DESC, altura DESC, nom_pinya ASC";
                else $query1 = "SELECT id,nom_pinya,altura FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND canalla = '1') ORDER BY nom_pinya ASC";
                
                /* agafo en un array els ids de la gent de troncs */
                $result = mysqli_query($con,$query1);
                $var_num_total = mysqli_affected_rows($con);
                $arr_id_troncs = array();
                $arr_pis_troncs = array();
                while($cens_troncs = mysqli_fetch_array($result))
                {
                    $arr_id_troncs[$cens_troncs['id']] = $cens_troncs['nom_pinya'];
                    if ($cens_troncs['pis8'] == '') 
                        $arr_pis_troncs[$cens_troncs['id']] = $cens_troncs['pis7'];
                    else
                        $arr_pis_troncs[$cens_troncs['id']] = $cens_troncs['pis8'];
                }
                $arr_troncs = join(',',array_keys($arr_id_troncs));
                $var_texte_select_troncs = "and id IN ($arr_troncs)";

                $var_motiu="";
                /* si $var_id_diada es 0000-00-00 vol dir que vinc per data (assaig) */
                if ($var_id_diada == "0000-00-00") {
                    if (empty($_POST["data"])) $var_data = date("Y-m-d");
                    else $var_data = $_POST["data"];
                    $query = "SELECT * FROM assistencia WHERE diada='" . $var_data . "' " . $var_texte_select_troncs . " order by nom_pinya";
                }
                else {
                    $row = mysqli_fetch_array(mysqli_query($con,"SELECT data,motiu from diades WHERE id_diada='" . $var_id_diada . "'"));
                    $var_data = $row['data'];
                    $var_motiu = $row['motiu'];
                    $query = "SELECT * FROM assistencia WHERE id_esdeveniment='" . $var_id_diada . "' " . $var_texte_select_troncs . " order by nom_pinya";
                }

                $assistents = mysqli_query($con,$query);
                $reg = mysqli_affected_rows ($con); 
                $array_assist = array();
                $array_ids = array();
                while($row = mysqli_fetch_array($assistents))
                {
                    $id_assist = $row['id']; /* no se si id_assist es pot eliminar */
                    $array_assist[$id_assist] = $row['previst'];
                    $array_ids[$id_assist] = $row['nom_pinya'];
                }

                $var_data_formatada = " <span>" . date("j-n-Y",strtotime($var_data)) . "</span>";
                echo "<div id='calendari_assistencia'></div>";
                echo "<form id='assistencia_modificable_form'>";
                echo "<fieldset   data-type='horizontal'  data-mini='true' id='assistencia'>
                        <div id='titol_pagina'>Assist&egrave;ncia " . ucfirst($var_grup) . "  <strong>" . htmlentities($var_motiu) . "</strong> " . $var_data_formatada . "</div>";

                /*if ($var_grup == "pinyes") echo "<ul data-role='listview' data-autodividers='true' id='sortedList' class='assistencia'><div class='row'>";
                else*/ echo "<ul data-role='listview' data-autodividers='false' id='sortedList' class='assistencia'><div class='row_assistencia'>";

                $pis_actual = '0';
                foreach($arr_id_troncs as $key => $value) {
                    if ($var_grup == "troncs") { /* si es troncs llavors pinto el num de pis */
                        if ($arr_pis_troncs[$key] != $pis_actual) {
                            if ($pis_actual <> '0') echo "</div><div class='row_assistencia'>";
                            switch ($arr_pis_troncs[$key]) {
                                case '5': 	echo "<li class='pis_assist'><h1>5</h1></li>";
                                            break;
                                case '4': 	echo "<li class='pis_assist'><h1>4</h1></li>";
                                            break;
                                case '3': 	echo "<li class='pis_assist'><h1>3</h1></li>";
                                            break;
                                case '2': 	echo "<li class='pis_assist'><h1>2</h1></li>";
                                            break;
                                case '1': 	echo "<li class='pis_assist'><h1>1</h1></li>";
                                            break;
                            }
                            $pis_actual = $arr_pis_troncs[$key]; 
                        }
                    }
                    if ($array_assist[$key] == "1") { $var_ve = "checked"; $var_checkbox = "seleccionada"; }
                    else { $var_ve = ""; $var_checkbox = "no_seleccionada"; }
                    /*$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $key . ".png";*/
                    /*if (!file_exists ("http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $key . ".png")) { $foto = "images/gent/thumbs/ningu.png"; }*/
                    $foto = "images/gent/thumbs/" . $key . ".png";
                    if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $key . ".png")) { $foto = "images/gent/thumbs/ningu.png"; }

                    if ($var_grup == "troncs") echo "<div class='col-xs-auto'>";
                    echo "<li class='wow animated bounceIn " . $var_checkbox . "' style='background: url(\"" . $foto . "\") no-repeat center center;' data-wow-duration='0.2s' data-wow-delay='0s'><input class='assist_chk' type='checkbox' name='checkbox-" . $key . "' id='checkbox-" . $key . "' " . $var_ve . ">
                        <label for='checkbox-" . $key . "'>" . htmlentities($value) . "</label></li>";
                    if ($var_grup == "troncs") echo "</div>";
                }

                echo "</div></ul></fieldset></form>";

                /* faig un selector amb totes les diades de l'any */
                /*$query_diades = "SELECT motiu,id_diada FROM diades WHERE YEAR(data) = YEAR(Now()) ORDER BY data";
                $diades = mysqli_query($con,$query_diades);
                echo "<select name='selector_diada_assistencia' id='selector_diada_assistencia'>";
                echo "<option value='0000-00-00' selected>Tria diada...</option>";
                while($row_diades = mysqli_fetch_assoc($diades))
                {
                    echo "<option value='" . $row_diades['data'] . "' id_diada='" . $row_diades['id_diada'] . "'>" . htmlentities($row_diades['motiu']) . "</option>";
                }
                echo "</select>"; 
                echo "<script  type='text/javascript' charset='utf-8'>$('#selector_diada_assistencia').change(function() {
                            var var_data = $('option:selected', this).attr('value');
                            var var_id_diada = $('option:selected', this).attr('id_diada');
                            mostra_assistencia_modif('" . $var_grup . "',var_id_diada,var_data);
                    });</script>";
                */
                /* creo un array [assaigs] amb les dates dels propers  dimarts i divendres en un mes */ 
                /*$d = new DateTime();
                $inc = new DateInterval('P1D');
                $dates = array();
                $dies_assaig = array(2,5);
                for ($i=0; $i<30; ++$i) {
                    $d = $d->add($inc);
                    if (in_array($d->format('w'), $dies_assaig)) {
                        $v = $d->format('Y-m-d');
                        $t = $d->format('Y-m-d D');
                        $dates[] = $v;
                    }
                }
                echo "<select name='selector_data_assistencia' id='selector_data_assistencia'>";
                echo "<option value='0000-00-00' selected>Tria assaig...</option>";
                echo "<option value='" . date("Y-m-d") . "' id_diada='0000-00-00'>Avui</option>"; 
                foreach($dates as $key_data_assaig)
                {
                    $var_data_catalana = date("d-m-Y",strtotime($key_data_assaig));
                    echo "<option value='" . $key_data_assaig . "' id_diada='0000-00-00'>" . $var_data_catalana . "</option>";
                }
                echo "</select>";
                echo "<script  type='text/javascript' charset='utf-8'>$('#selector_data_assistencia').change(function() {
                            var var_data = $('option:selected', this).attr('value');
                            var var_id_diada = $('option:selected', this).attr('id_diada');
                            mostra_assistencia_modif('" . $var_grup . "',var_id_diada,var_data);
                    });</script>";
                echo "</div>";*/ /* inset */
            } /* final veure assistencia troncs o canalla */
		} /* fi consulta */
	
	mysqli_close($con);
?>