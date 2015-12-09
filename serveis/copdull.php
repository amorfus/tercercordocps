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
	
	$avui = date("Y-m-d");
	/*echo "<div data-role='popup' id='guio_popup'></div>";*/
   

	echo "<ul data-nativedroid-plugin='cards' class='nativeDroidCards'>"; /* quan acabi d'arreglar totes les cards, aixo va fora (el ul) */
	if (isset ($_POST['tipus']) && $_POST['tipus'] == "assaig") {
		$timestamp = time();
		$dia_setmana = date('D', $timestamp);
		if($dia_setmana === 'Tue' or $dia_setmana === 'Fri') {
			$data_assaig = date("Y-m-d");
		}
		else {
			$properDimarts= strtotime("first Tuesday");
			$properDivendres= strtotime("first Friday");
			$proper_dia = ($properDimarts > $properDivendres ? $properDivendres : $properDimarts);
			$data_assaig = date("Y-m-d",$proper_dia);
			}
		$var_data_catalana = date("d-m-Y",strtotime($data_assaig));
        echo "<div class='nd2-card wow animated zoomInDown'><div class='card-media'><img src='./images/targes/foto_assaig_3d8.png'></div><div class='card-supporting-text'>";
		echo "<div id='id_diada_assaig' style='display:none'>" . $data_assaig . "</div>";
		$assaig = mysqli_query($con,"SELECT * FROM guions_assaig where data = '" . $data_assaig . "' ORDER BY position") or die (mysql_error());
		$var_cnt = mysqli_affected_rows($con);
		
		/* guio */
		/*echo "<li data-cards-type='text' class='proves'>";*/
		echo "<h3><i class='zmdi zmdi-file-text'></i> Gui&oacute; (" . $var_data_catalana . ") ";
		echo "<div id='llista_castells'></div>";		
		/*while ($row_prova = mysqli_fetch_array($assaig)) {*/
			/*echo "<h1 class='prova'><a href='#' onclick=\"javascript:mostra_tronc_sortable('" . $row_prova['id'] . "','" . $row_prova['prova'] . "');\"><!--<i class='icon-eye-open'></i></a><span>" . htmlentities($row_prova['text']) . "<i class='icon-pencil'></i></span>-->" . $row_prova['prova'] ."</h1>";*/
		/*}*/

		/*echo "<a href='#' onclick=\"javascript:mostra_troncs('assaig','0','" . $data_assaig . "');\"> VEURE TOTS</a>";*/
		echo "</h3>";
		
		if ($var_cnt == 0) { /* no hi ha guio creat per al proper assaig */
			echo "<div class='message error'><h3><i class='zmdi zmdi-block'></i>Manca Gui&oacute;</h3></div>";
			echo "<p id='manca_guio'>&nbsp;</p>";
			echo "<h6>Crear-lo a partir d'un altre assaig?</h6>";
			$assaig = mysqli_query($con,"SELECT * FROM guions_assaig where ((data <= '" . $data_assaig . "' OR data >= '" . $data_assaig . "') AND data <>'0000-00-00') GROUP BY data ORDER BY data DESC LIMIT 5") or die (mysql_error());
			echo "<select name='selector_assaig_copiar' id='selector_assaig_copiar'>";
			echo "<option value='0000-00-00' selected>Tria assaig...</option>";
			while ($row_assaigs = mysqli_fetch_array($assaig)) {
				$var_data_catalana_2 = date("d-m-Y",strtotime($row_assaigs['data']));
				echo "<option value='" . $row_assaigs['data'] . "'>" . $var_data_catalana_2 . "</option>";
			}
			echo "</select>";
			echo "<a href='#bottomsheetlist_auxiliar' class='ui-btn ui-btn-inline ui-btn-raised waves-effect waves-button waves-effect waves-button clr-primary ui-btn-fab col-xs-auto'  onclick=\"javascript:data_guio=jQuery('#selector_assaig_copiar option:selected').val(); if(data_guio != '0000-00-00'){mostra_guio_popup(data_guio,'false');}\"><i class='zmdi zmdi-eye'></i></a>";
			echo "<a href='#' class='ui-btn ui-btn-inline ui-btn-raised waves-effect waves-button waves-effect waves-button clr-primary ui-btn-fab col-xs-auto'  onclick=\"javascript:data_guio=jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" . $avui . "','no');\"><i class='zmdi zmdi-copy'></i></a>";
			echo "<a href='#' class='ui-btn ui-btn-inline ui-btn-raised waves-effect waves-button waves-effect waves-button clr-primary ui-btn-fab col-xs-auto'  onclick=\"javascript:data_guio=jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" .$avui . "','si');\"><i class='zmdi zmdi-accounts-alt'></i></a>";
		
			/*echo "<ul class='toolbar cf'>
			<li class='toolbar_li'><a href='#' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); if(data_guio != '0000-00-00'){mostra_guio_popup(data_guio);}\"><i class='icon-eye-open'></i></a></li>
			<li class='toolbar_li'><a href='#' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" . $data_assaig . "','no');\"><i class='icon-copy'></i></a></li>
			<li class='toolbar_li'><a href='#' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" .$data_assaig . "','si');\"><i class='icon-user'></i></a></li>
			</ul>";*/
			/*echo "<button id='boto_mostra_guio' class='icon-eye-open' onclick=\"javascript:var data_guio =jQuery('#selector_assaig_copiar option:selected').val(); mostra_guio_popup(data_guio);\"> Veure</button>";
			echo "<button id='boto_copia_guio' class='icon-copy' onclick=\"javascript:var data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" . $data_assaig . "','no');\"> C&ograve;pia</button>";
			echo "<button id='boto_copia_guio_troncs' class='icon-user' onclick=\"javascript:var data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" .$data_assaig . "','si');\"> C&ograve;pia Troncs</button>";*/
			/*echo "<script  type='text/javascript' charset='utf-8'>$('#selector_assaig_copiar').change(function() {
						var data_ass = $(this).attr('value');
						alert(data_ass);
					});</script>";*/
		}
		/*echo "</li>";*/
        echo "</div></div>"; /* fi primera card */
		/*echo "</ul><a href='#'><i class='icon-file-alt'></i> Modificar Gui&oacute;</a></li>";*/
		
		/* Assistencia prevista troncs */
        
        echo "<div class='nd2-card wow animated zoomInDown'>
            <div class='card-title has-supporting-text'>
                <h3 class='card-primary-title'>Assist&egrave;ncia Troncs</h3>";
        
		/*echo "<li data-cards-type='text'>
				<h1><i class='lIcon fa fa-group'></i> Assist&egrave;ncia Troncs</h1>";*/
		$query1 = "SELECT id,nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY nom,nom_pinya";
		$result = mysqli_query($con,$query1);
		while ($gent_troncs = mysqli_fetch_array($result)) {
			$array_gent_troncs[$gent_troncs['id']] =  trim($gent_troncs['nom_pinya']);
		}
		$troncs_ids = join(',',array_keys($array_gent_troncs));  
		$query1 = "SELECT a.id,a.diada FROM assistencia a WHERE (a.diada = '" . $data_assaig . "')  AND a.previst= 1 AND a.id IN ($troncs_ids) ORDER BY a.nom_pinya";
		$result = mysqli_query($con,$query1);
		$var_num_total_ass = mysqli_affected_rows($con);
		$arr_assistents = array();
        
        echo "<h5 class='card-subtitle'>Assist&egrave;ncia prevista <strong>" . $var_num_total_ass . "</strong></h5></div>";
        echo "<div class='card-supporting-text has-action has-title'>";
        
		/*echo "<h2>Assist&egrave;ncia prevista <strong>" . $var_num_total_ass . "</strong></h2>";*/
		while($assistencies = mysqli_fetch_array($result))
		{
			$arr_assistents[$assistencies['id']] = $array_gent_troncs[$assistencies['id']]; 
		}
		if ($var_num_total_ass > 0) {
			$texte = join(', ',$arr_assistents);
		}
		else {$texte = "";}
		echo "<div style='padding-left: 10px;'><strong>" . htmlentities($texte) . "</strong></div><br>";
		$arr_baixes = array();
		$arr_baixes = array_diff($array_gent_troncs,$arr_assistents);
		$var_num_total = count($arr_baixes);
		if($var_num_total > 0 ) {
			$texte = join(', ',$arr_baixes);
		}
		else { $texte = ""; }
        
		$texte = join(', ',$arr_baixes);
        
        echo "<h5 class='card-subtitle'>Baixes previstes <strong>" . $var_num_total . "</strong></h5></div>";
        /*echo "<h2>Baixes previstes <strong>" . $var_num_total . "</strong></h2>";*/
		echo "<div class='card-supporting-text has-action has-title'>";
        echo "<div style='padding-left: 10px;'><strong>" . htmlentities($texte) . "</strong></div></li>";		
		 echo "</div>";
        echo "   <!--<div class='card-action'>
                <div class='row between-xs'>
                    <div class='col-xs-12'>
                        <div class='box'>
                            <a href='#' class='ui-btn ui-btn-inline'>Applause</a>
                            <a href='#' class='ui-btn ui-btn-inline'>Boooh</a>
                        </div>
                    </div>
                </div>
            </div>-->

        </div>";
        
        
		/* Assistencia assaig*/
		/* cal retocar select*/
		$gent_query = "select nom_pinya from assistencia where `id_esdeveniment` = '" . $var_id_diada . "' and `tipus` = 'd' and `previst` = 1";
		$gent_apuntada = mysqli_query($con,$gent_query);
		$baixes_query = "select nom_pinya from assistencia where `id_esdeveniment` = '" . $var_id_diada . "' and `tipus` = 'd' and `previst` = 0";
		$baixes = mysqli_query($con,$baixes_query);
		/*echo "<li data-cards-type='sports'>";*/
		
		$num_gent_apuntada = mysqli_num_rows($gent_apuntada);
		$num_baixes = mysqli_num_rows($baixes);
		
		/*echo "<div class='card-media'><img src='/img/examples/card_bg_2.jpg'><div class='card-media-overlay with-background'>";
		echo "<div class='card-title has-supporting-text'><h3 class='card-primary-title'>Assist&egrave;ncia</h3>";
		echo "<h5 class='card-subtitle'>Previst a " . date("d/m/Y") . "</h5></div>";
		echo "<div class='card-action'><div class='row between-xs'><div class='col-xs-12'><div class='box'>";
		echo "<a href='#' class='ui-btn ui-btn-inline'>Camises<span>" . $num_gent_apuntada . "</span></a><a href='#' class='ui-btn ui-btn-inline'>Baixes<span>" . $num_baixes . "</span></a>";
		echo "</div></div></div></div></div></div>";*/
		
        echo "<div class='nd2-card wow animated zoomInDown'>


					<div class='card-media'>
						<img src='/images/fons_cap.png'>

						<div class='card-media-overlay with-background'>

							<div class='card-title has-supporting-text'>
								<h3 class='card-primary-title'>Assist&egrave;ncia</h3>
								<h5 class='card-subtitle'>Previst a " . date("d/m/Y") . "</h5>
							</div>

							<div class='card-action'>
								<div class='row between-xs'>
									<div class='col-xs-12'>
										<div class='box'>
											<a href='#' class='ui-btn ui-btn-inline'>Camises <span>" . $num_gent_apuntada . "</span></a>
											<a href='#' class='ui-btn ui-btn-inline'>Baixes <span>" . $num_baixes . "</span></a>
										</div>
									</div>
								</div>
							</div>

						</div>

					</div>


				</div>";
        
        
        
        
        
        
		/*echo "<h1><i class='icon-group'></i> Assist&egrave;ncia</h1>";
		echo "<h1>Camises<span>" . $num_gent_apuntada . "</span></h1>";
		echo "<h1>Baixes<span>" . $num_baixes . "</span></h1>";
		echo "<h2>Previst a " . date("d/m/Y") . "</h2></li>";*/
		
		/* previsio temps */
		$json_string = file_get_contents("http://api.wunderground.com/api/d2cb9934cdd4caeb/forecast/q/41.39479,2.1487679.json"); /*41.3727444,2.1596103*/
		$parsed_json = json_decode($json_string, true);
		$previsio = $parsed_json['forecast']['txt_forecast']['forecastday'];
		$previsio_diaria = $parsed_json['forecast']['simpleforecast']['forecastday'];
        
         echo "<div class='nd2-card wow animated zoomInDown'>


					<div class='card-media'>
						<img src='/images/fons_cap.png'>

						<div class='card-media-overlay with-background'>

							<div class='card-title has-supporting-text'>
								<h3 class='card-primary-title'>Assaig</h3>
								<h5 class='card-subtitle'>" . $var_data_catalana . "</h5>
							</div>";

							
											
										

				
        
		/*echo "<li data-cards-type='weather'>*/
		/*<h1>Assaig</h1>
		<h2>" . $var_data_catalana . "</h2>";*/

		foreach($previsio_diaria as $key => $value)
		{
			if ($value['period'] == '1') {
				switch ($value['conditions']) {
					case "Partly Cloudy": $value['conditions'] = "Ennuvolat";
								break;
					case "Sunny": $value['conditions'] = "Assolellat";
								break;
					case "Chance of Rain": $value['conditions'] = "Possibilitats de Pluja";
								break;
				}
                echo "<div class='card-action'>
								<div class='row between-xs'>
									<div class='col-xs-12'>
										<div class='box'>";
				echo "<ul class='detail'>
						<li><img src='" . $value['icon_url'] . "' />
							<span>" . $value['conditions'] . "</span>
						</li>
						<li>". $value['high']['celsius']  . "&deg;</li>
					</ul>";
			}
			else {
				if ($value['period'] == '2') {
					echo "<ul class='week'>";
				}
				switch ($value['date']['weekday_short']) {
					case "Mon" : 	$value['date']['weekday_short'] = "Dl";
								break;
					case "Tue" : 	$value['date']['weekday_short'] = "Dm";
								break;
					case "Wed" : 	$value['date']['weekday_short'] = "Dc";
								break;
					case "Thu" : 	$value['date']['weekday_short'] = "Dj";
								break;
					case "Fri" : 	$value['date']['weekday_short'] = "Dv";
								break;
					case "Sat" : 	$value['date']['weekday_short'] = "Dt";
								break;
					case "Sun" : 	$value['date']['weekday_short'] = "Dg";
								break;
				}
				echo "<li>
							<span>" . $value['date']['weekday_short'] . "</span>
							<img src='" . $value['icon_url'] . "' />
							<strong>" . $value['high']['celsius'] ."&deg;</strong>
							<span>". $value['low']['celsius'] ."&deg;</span>
					</li>";
				if ($value['period'] == '4') {
					echo "</ul>";
				}
			}
		}
        echo "</div>
									</div>
								</div>
							</div>";
        
		echo "</li>";
        echo"		</div>

					</div>


				</div>";
	}
	else {   /* diada */ /***********************************************************************************************/
	
		if (isset($_POST['id_data']) && ($_POST['id_data'] != 0)) {
			$var_id_diada = $_POST['id_data'];
			$diada = mysqli_query($con,"SELECT * FROM diades where id_diada = " . $var_id_diada) or die (mysql_error());
		}
		else {
			$diada = mysqli_query($con,"SELECT * FROM diades where data >= '" . $avui . "' ORDER BY data LIMIT 1") or die (mysql_error());
		}
		$row_diada = mysqli_fetch_array($diada);
		$var_id_diada = $row_diada['id_diada'];
		$var_data_diada = $row_diada['data'];
		$var_data_formatada = date("d-m-Y",strtotime($row_diada['data']));
        echo "<div class='nd2-card wow animated zoomInDown'><div class='card-media'><img src='./images/targes/pinya_4d8.jpg'></div><div class='card-supporting-text'>";
		echo "<div id='motiu_diada_assaig' style='display:none'>" . htmlentities($row_diada['motiu']) . " <span>(" . $var_data_formatada . ")</span></div>";
		echo "<div id='id_diada_assaig' style='display:none'>" . $var_id_diada . "</div>";
		/* Assistència */
		/*$gent_query = "select nom_pinya from assistencia where `id_esdeveniment` = '" . $var_id_diada . "' and `tipus` = 'd' and `previst` = 1";*/
		$gent_query = "SELECT a.id FROM assistencia a WHERE (a.diada = '" . $row_diada['data'] . "' OR a.id_esdeveniment = " . $var_id_diada . ")  AND a.previst= 1";
		
		$gent_apuntada = mysqli_query($con,$gent_query);
		$baixes_query = "select nom_pinya from assistencia where `id_esdeveniment` = '" . $var_id_diada . "' and `tipus` = 'd' and `previst` = 0";
		$baixes = mysqli_query($con,$baixes_query);
		echo "<li data-cards-type='sports'>";
		$num_gent_apuntada = mysqli_num_rows($gent_apuntada);
		$num_baixes = mysqli_num_rows($baixes);
		echo "<h1><i class='icon-group'></i> Assist&egrave;ncia <span>" . htmlentities($row_diada['motiu']) . "</span></h1>";
		echo "<h1>Camises<span>" . $num_gent_apuntada . "</span></h1>";
		echo "<h1>Baixes<span>" . $num_baixes . "</span></h1>";
		echo "<h2>Previst a " . date("d/m/Y") . "</h2></li>";
		
		/* Assistencia prevista troncs */
		echo "<li data-cards-type='text'>
				<h1><i class='icon-group'></i> Assist&egrave;ncia Troncs</h1>";
		$query1 = "SELECT id,nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0')";
		$result = mysqli_query($con,$query1);
		while ($gent_troncs = mysqli_fetch_array($result)) {
			$array_gent_troncs[$gent_troncs['id']] =  trim($gent_troncs['nom_pinya']);
		}
		$troncs_ids = join(',',array_keys($array_gent_troncs));  
		$query1 = "SELECT a.id,a.diada FROM assistencia a WHERE (a.diada = '" . $row_diada['data'] . "' OR a.id_esdeveniment = " . $var_id_diada . ")  AND a.previst= 1 AND a.id IN ($troncs_ids) ORDER BY a.nom_pinya";
		$result = mysqli_query($con,$query1);
		$var_num_total_ass = mysqli_affected_rows($con);
		$arr_assistents = array();
		echo "<h2>Assist&egrave;ncia prevista <strong>" . $var_num_total_ass . "</strong></h2>";
		while($assistencies = mysqli_fetch_array($result))
		{
			$arr_assistents[$assistencies['id']] = $array_gent_troncs[$assistencies['id']];
		}
		if ($var_num_total_ass > 0) {
			$texte = join(', ',$arr_assistents);
		}
		else {$texte = "";}
		echo "<div style='padding-left: 10px;'><strong>" . htmlentities($texte) . "</strong></div><br>";
		$arr_baixes = array();
		$arr_baixes = array_diff($array_gent_troncs,$arr_assistents); 
		$var_num_total = count($arr_baixes);
		if($var_num_total > 0 ) {
			$texte = join(', ',$arr_baixes);
		}
		else { $texte = ""; }
		echo "<h2>Baixes previstes <strong>" . $var_num_total . "</strong></h2>";
		echo "<div style='padding-left: 10px;'><strong>" . htmlentities($texte) . "</strong></div></li>";
		
		
		/*$query1 = "SELECT a.nom_pinya,a.diada FROM assistencia a WHERE (a.diada = '" . $row_diada['data'] . "' OR a.id_esdeveniment = " . $var_id_diada . ")  AND a.previst= 1 AND a.nom_pinya IN (SELECT nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY nom_pinya)";
		$result = mysqli_query($con,$query1);
		$var_num_total_ass = mysqli_affected_rows($con);
		$arr_assistents = array();
		echo "<h2>Assist&egrave;ncia prevista <strong>" . $var_num_total_ass . "</strong></h2>";
		while($assistencies = mysqli_fetch_array($result))
		{
			$arr_assistents[] = htmlentities(trim($assistencies['nom_pinya'])); 
		}
		$texte = join(', ',$arr_assistents);
		echo "<div style='padding-left: 10px;'><strong>" . $texte . "</strong></div><br>";
		
		$query1 = "SELECT a.nom_pinya,a.diada FROM assistencia a WHERE (a.diada = '" . $row_diada['data'] . "' OR a.id_esdeveniment = " . $var_id_diada . ")  AND a.previst= 0 AND a.nom_pinya IN (SELECT nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY nom_pinya)";
		$result = mysqli_query($con,$query1);
		$var_num_total = mysqli_affected_rows($con);
		$arr_baixes = array();
		echo "<h2>Baixes previstes  <strong>" . ($var_num_total) . "</strong></h2>";
		while($cens_troncs = mysqli_fetch_array($result))
		{
			$arr_baixes[] = htmlentities(trim($cens_troncs['nom_pinya'])); 
		}
		$texte = join(', ',$arr_baixes);
		echo "<div style='padding-left: 10px;'><strong>" . $texte . "</strong></div></li>";*/
		
		/******************** CASTELLS ***************************/
		/*$var_resultats = mysqli_query($con,"SELECT * from resultats where diada_id='". $var_id_diada ."' ORDER BY ronda") or die (mysql_error());*/
		echo "<li data-cards-type='publictransport' class='llista_castells'><h1>Castells <span class='que_tirem'></span><a href='#' onclick='modifica_castells_diada(" . $var_id_diada . ");'><i class='lIcon fa fa-pencil'></i></a></h1>";
		echo "<div id='llista_castells'>";
		/*if (mysqli_affected_rows($con) > 0) echo "<a href='#' onclick=\"javascript:mostra_troncs('diada','". $var_id_diada ."','". $var_data_diada ."');\"><i class='lIcon fa fa-eye'></i> Veure TRONCS </a>";
		echo "<ul id='llista_castells_diada' class='touch' data-role='listview' data-icon='true' data-split-icon='delete'>";
		while ($row_resultats = mysqli_fetch_array($var_resultats)) {
			echo "<li><i class='lIcon fa fa-arrow-right'></i><p class='topic'>" . $row_resultats['castell_id'] . "</p>";
			echo "<a href='#' class='ui-btn'><i class='lIcon fa fa-asterisk'></i></a><a href='#' class='delete'>Borrar</a></li>";
		}
		echo "</ul>":*/
		echo "</div></li>";
		
		/* Previsio meteo */
		$altitut = $row_diada['altitut'];
		$latitut = $row_diada['latitut'];
		$msg = "";
		if ($altitut == "" or $latitut == "") {
			$msg= "Manquen coordenades, agafant refer&egrave;ncia local.";
			$altitut = '41.372029';
			$latitut = '2.168868';
		}
		$json_string = file_get_contents("http://api.wunderground.com/api/d2cb9934cdd4caeb/forecast/q/" . $altitut . "," . $latitut . ".json"); /*41.3727444,2.1596103*/
		$parsed_json = json_decode($json_string, true);
		$previsio = $parsed_json['forecast']['txt_forecast']['forecastday'];
		$previsio_diaria = $parsed_json['forecast']['simpleforecast']['forecastday'];

		echo "<li data-cards-type='weather'>
		<h1>" . htmlentities($row_diada['lloc']) . "</h1>
		<h2><strong>" . $msg . "</strong></h2>";
		foreach($previsio_diaria as $key => $value)
		{
			if ($value['period'] == '1') {
				switch ($value['conditions']) {
					case "Partly Cloudy": $value['conditions'] = "Ennuvolat";
									break;
					case "Sunny": $value['conditions'] = "Assolellat";
									break;
					case "Chance of Rain": $value['conditions'] = "Possibilitats de Pluja";
								break;
				}
				echo "<ul class='detail'>
						<li><img src='" . $value['icon_url'] . "' />
							<span>" . $value['conditions'] . "</span>
						</li>
						<li>". $value['high']['celsius']  . "&deg;</li>
					</ul>";
			}
			else {
				if ($value['period'] == '2') {
					echo "<ul class='week'>";
				}
				switch ($value['date']['weekday_short']) {
					case "Mon" : 	$value['date']['weekday_short'] = "Dl";
								break;
					case "Tue" : 	$value['date']['weekday_short'] = "Dm";
								break;
					case "Wed" : 	$value['date']['weekday_short'] = "Dc";
								break;
					case "Thu" : 	$value['date']['weekday_short'] = "Dj";
								break;
					case "Fri" : 	$value['date']['weekday_short'] = "Dv";
								break;
					case "Sat" : 	$value['date']['weekday_short'] = "Dt";
								break;
					case "Sun" : 	$value['date']['weekday_short'] = "Dg";
								break;
				}
				echo "<li>
							<span>" . $value['date']['weekday_short'] . "</span>
							<img src='" . $value['icon_url'] . "' />
							<strong>" . $value['high']['celsius'] ."&deg;</strong>
							<span>". $value['low']['celsius'] ."&deg;</span>
					</li>";
				if ($value['period'] == '4') {
					echo "</ul>";
				}
			}
		}
		echo "</li>";
		
		/* Com arribar */
		echo "<li data-cards-type='traffic' data-cards-traffic-route='{\"from\":\"41.3718185,2.1675806\",\"to\":\"" . $row_diada['altitut'] . "," . $row_diada['latitut'] . "\",\"type\" : \"coords\"}'>
				<h1>Diada de <strong>" . htmlentities($row_diada['motiu'])  . "</strong></h1>
				<h2>" . htmlentities($row_diada['lloc'])  . "</h2>
				<div class='map'></div>
				<a href='#'><i class='icon-screenshot'></i>Ves-hi</a>
			</li>";
	}
	echo "</ul>";
	mysqli_close($con);
?>