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
	echo "<div id='full_troncs'>";

	/*Guio d'assaig*/
	/* la següent SELECT busca les proves de guió d'avui o si no en troba, del proper dia d'assaig del que trobi proves */
	$avui = date("Y-m-d");
	
	/*$var_troncs_guio_qry = "(SELECT * FROM guions_assaig WHERE data='" . $avui . "' ORDER BY position) 
	UNION ALL 
	(SELECT * FROM guions_assaig WHERE 
	    (SELECT COUNT(*) FROM guions_assaig WHERE data='" . $avui . "') = 0 
	    AND data = (SELECT MIN(data) FROM guions_assaig WHERE data > '" . $avui . "' ORDER BY data LIMIT 1))";
	$var_troncs_guio = mysqli_query($con,$var_troncs_guio_qry) or die (mysql_error());
	$var_cnt = mysqli_affected_rows($con);

	echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='full_troncs_collapsible'><h4>Gui&oacute d'Assaig <span class='ui-li-count'><strong>(" . $var_cnt . ")</strong></span></h4>";
	echo "<ul data-role='listview' id='full_troncs_list' data-inset='false' data-icon='false' data-divider-theme='b'>";
	while($row_troncs_guio = mysqli_fetch_array($var_troncs_guio)) {
		if ($row_troncs_guio['tipus'] == "pinyes") { $color = "z"; }
		else { $color = substr($row_troncs_guio['prova'],0,1); }
		echo "<li class='quadre t" . $color . "'>";
		echo "<a href='#' onclick=\"javascript:mostra_tronc_sortable('" . $row_troncs_guio['id'] . "','" . $row_troncs_guio['prova'] . "');\">";
		echo ucfirst($row_troncs_guio['prova']);
		echo "<span class='texte'>" . htmlentities($row_troncs_guio['text']) . "</span>";
		echo "</a></li>";
		}
	if ($var_cnt == 0) { */ /* no hi ha guio creat per al proper assaig */
/*		echo "<div class='message error'><h1><i class='lIcon fa fa-warning'></i>Manca Gui&oacute;</h1></div>";
		echo "<p id='manca_guio'>&nbsp;</p>";
		echo "<h4>Vols crear-lo a partir d'un altre assaig?</h4>";
		$assaig = mysqli_query($con,"SELECT * FROM guions_assaig where ((data <= '" . $avui . "' OR data >= '" . $avui . "') AND data <>'0000-00-00') GROUP BY data ORDER BY data DESC LIMIT 5") or die (mysql_error());
		echo "<select name='selector_assaig_copiar' id='selector_assaig_copiar'>";
		echo "<option value='0000-00-00' selected>Tria assaig...</option>";
		while ($row_assaigs = mysqli_fetch_array($assaig)) {
			$var_data_catalana_2 = date("d-m-Y",strtotime($row_assaigs['data']));
			echo "<option value='" . $row_assaigs['data'] . "'>" . $var_data_catalana_2 . "</option>";
		}
		echo "</select>";
		echo "<a href='#' data-role='button' data-inline='true' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); if(data_guio != '0000-00-00'){mostra_guio_popup(data_guio,'false');}\"><i class='lIcon fa fa-eye'></i></a>";
		echo "<a href='#' data-role='button' data-inline='true' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" . $avui . "','no');\" data-theme='b'><i class='lIcon fa fa-copy'></i></a>";
		echo "<a href='#' data-role='button' data-inline='true' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" .$avui . "','si');\"><i class='lIcon fa fa-user'></i></a>";
		*/
		/*echo "<ul class='toolbar cf'>
		<li class='toolbar_li'><a href='#' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); if(data_guio != '0000-00-00'){mostra_guio_popup(data_guio);}\"><i class='lIcon fa fa-eye'></i></a></li>
		<li class='toolbar_li'><a href='#' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" . $avui . "','no');\"><i class='lIcon fa fa-copy'></i></a></li>
		<li class='toolbar_li'><a href='#' onclick=\"javascript:data_guio =jQuery('#selector_assaig_copiar option:selected').val(); copia_guio_desde(data_guio,'" .$avui . "','si');\"><i class='lIcon fa fa-user'></i></a></li>
		</ul>";*/
	/*}
	
	echo "</ul></div>";*/
	
	
	/* Troncs de Treball */
	$var_troncs_treball = mysqli_query($con,"SELECT * from guions_assaig where (data='0000-00-00' and tipus='troncs') ORDER BY position") or die (mysql_error());
	$var_cnt = mysqli_affected_rows($con);
	$var_prova = "";
	/*echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='full_troncs_collapsible'><h4>Troncs de Treball <span class='ui-li-count'><a href='#' onclick=\"javascript:mostra_troncs('assaig','0','0000-00-00');\"><i class='lIcon fa fa-eye'></i> Veure TOTS <strong>" . $var_cnt . "</strong></a></span></h4>";*/
	echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='full_troncs_collapsible'><h4>Troncs de Treball <span class='ui-li-count'>" . $var_cnt . "</span></h4>";
	
	echo "<ul class='troncs_treball' data-role='listview' id='full_troncs_treball_list' data-inset='false' data-icon='false' data-divider-theme='b' data-autodividers='true'>";
	/*while($row_troncs_treball = mysqli_fetch_array($var_troncs_treball)) {*/
		/*if ($var_prova != $row_troncs_treball['prova']) {
			if ($var_prova != "") echo "</div>";
			$var_prova = $row_troncs_treball['prova'];

			echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='proves_collapsible'><h5>" . $var_prova . "</h5>";
		}
		echo "<li id='prova_" . $row_troncs_treball['id'] . "'>";
		$texte = "";
		if (strlen($row_troncs_treball['text']) > 0) $texte = "texte";
		echo "<a href='#' class='prova quadre " . $texte . " t" . substr($row_troncs_treball['prova'],0,1) . "' onclick=\"javascript:click_on_prova('" . $row_troncs_treball['id'] . "','" . $row_troncs_treball['prova'] . "');\">";
		echo ucfirst($row_troncs_treball['prova']) . "</a>";
		echo "<span class='" . $texte . "'>" . htmlentities($row_troncs_treball['text']) . "</span>";
		echo "</li>";*/

	$array_tronc = array();
	while ($row_prova = mysqli_fetch_array($var_troncs_treball)) {
		$var_tronc_id = $row_prova['id'];
		
		/*$query = "SELECT * FROM troncs WHERE tronc_id = '" . $var_tronc_id . "' ORDER BY posicio_tronc";
		$tronc = mysqli_query($con,$query);
		
		unset($array_tronc);
		while($bandarra = mysqli_fetch_array($tronc)) {
			$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
			if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }
			$query2 = "SELECT altura,pes from cens WHERE id='" . $bandarra[id] . "'";
			$result = mysqli_query($con,$query2) or exit(mysql_error());
			$mides = mysqli_fetch_array($result);
			$var_altura = $mides['altura'];
			$var_pes = $mides['pes'];
			$array_tronc[$bandarra[posicio_tronc]]= array('id'=>$bandarra[id],'nom_pinya'=>$bandarra[nom_pinya],'posicio'=>$bandarra[posicio_tronc],'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes,'ocupat'=>'ocupat');
		}*/
		
		$query = "SELECT tronc,c.nom_pinya,t.id,posicio_tronc,altura,pes FROM troncs t, cens c WHERE t.tronc_id='" . $var_tronc_id . "' AND t.id=c.id ORDER BY posicio_tronc";
		$tronc = mysqli_query($con,$query);
		unset($array_tronc);
		while($bandarra = mysqli_fetch_array($tronc)) {
			/*$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";*/
			/*if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }*/
            $foto = "images/gent/thumbs/" . $bandarra['id'] . ".png";
            if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "images/gent/thumbs/ningu.png"; }
			$var_altura = $bandarra['altura'];
			$var_pes = $bandarra['pes'];
			$array_tronc[$bandarra[posicio_tronc]]= array('id'=>$bandarra[id],'nom_pinya'=>$bandarra[nom_pinya],'posicio'=>$bandarra[posicio_tronc],'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes,'ocupat'=>'ocupat');
		}
		
		/*var_dump($array_tronc);*/
		/** dibuixa tronc **/
		$var_tronc = $row_prova['prova'];
		$cadena1 = explode("d",$var_tronc);
		$estructura = $cadena1[0];
		if (substr($var_tronc, -1) == 'a') $estructura++;
		$pisos_total = $cadena1[1];
		if ($estructura == "p") {$amplada_tronc = 60; $estructura = 1; }
		else { $amplada_tronc = intval($estructura) * 60; $pisos_total = $pisos_total - 3; }
		echo "<div class='tronc' id='" . $var_tronc_id . "' style='width:" . $amplada_tronc . "px'>";
		if (substr($row_prova['prova'],0,5)!="Pinya") echo "<h1>" . $row_prova['prova'] . "</h1>";
		
		for ($rengles = 1; $rengles <= $estructura; $rengles++) {
			$altura_rengla = 0;
			$pes_rengla = 0;
			echo "<ul id='rengla_sortable" . $rengles . "' class='bandarra_tronc connectedSortable'>";
			for ($pisos = $pisos_total; $pisos > 0 ; $pisos--) {
				$posicio_actual = "r" . $rengles . "p" . $pisos;
				if ($array_tronc[$posicio_actual][altura] == 0) $te_altura = "no-te-altura";
				else $te_altura = "";
				$altura_rengla = $altura_rengla + $array_tronc[$posicio_actual][altura];
				$pes_rengla = $pes_rengla + $array_tronc[$posicio_actual][pes];
				$altura = (intval($array_tronc[$posicio_actual][altura]) * 0.5);
				echo "<li class='ui-widget-content ui-corner-tr " . $array_tronc[$posicio_actual][ocupat] . " " . $te_altura . " ' id='" . $var_tronc_id . "_r" . $rengles . "p" . $pisos . "' style='background: url(\"" . $array_tronc[$posicio_actual][foto] . "\") no-repeat center center;width:55px;height:" . $altura . "px;'>";
				echo "<h2 class='altura'>" . $array_tronc[$posicio_actual][altura] . "</h2>";
				echo "<span style='height:" . $altura . "px;display:block;' /></span>";
				echo "<h2 class='nom_pinya'>" . htmlentities($array_tronc[$posicio_actual][nom_pinya]) . "</h2>";				
				echo "</li>";
			}
			echo "<li id='dades_rengla" . $rengles . "' class='dades_rengla'><span>" . $altura_rengla . " cm<br>" . $pes_rengla . " kg</span></li>";
			echo "</ul>";
		}
		echo "</div>";
	}


	/*}*/
	echo "</ul></div>";
	
	$mes_actual = date("n");
	$any_actual = date("Y");
	$dia_actual = date("d");
	$avui = date("Y-m-d");
	
	/* assistencia prevista avui */
	/* agafo en un array els ids de la gent de troncs */
	$query1 = "SELECT id,nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY nom_pinya";
	$result = mysqli_query($con,$query1);
	$var_num_total = mysqli_affected_rows($con);
	$arr_id_troncs = array();
	while($cens_troncs = mysqli_fetch_array($result))
	{
		$arr_id_troncs[$cens_troncs['id']] = $cens_troncs['nom_pinya'];
	}
	$arr_troncs = join(',',array_keys($arr_id_troncs));
	echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='full_troncs_collapsible'><h4>Assist&egrave;ncia Prevista Avui</h4><div class='inset'>";	
	echo "<div class='message success'><i class='icon-ok'></i>";
	/* aqui fa la recerca per data, compte que amb diades o guardo tb la data o no em mostrarà res*/
	$query1 = "SELECT id,nom_pinya,diada FROM assistencia WHERE (id IN ($arr_troncs) AND diada = '". $avui . "'  AND `previst`=1) ORDER BY diada,nom_pinya";
	$result = mysqli_query($con,$query1);
	$var_num_total_ass = mysqli_affected_rows($con);
	$arr_assistents = array();
	$data_formatada = " <span>" . date("j-n-Y",strtotime($avui)) . "</span>";
	while($assistencies = mysqli_fetch_array($result))
	{
		$id_bandarra = $assistencies['id']; 
		$arr_assistents[$id_bandarra] = $arr_id_troncs[$id_bandarra] ; 
	}
	asort($arr_assistents);
	$texte = join(', ',$arr_assistents);
	echo htmlentities($texte) . "    ( " . $var_num_total_ass . " )";
	$var_high = $var_num_total - 12;
	$var_optim = $var_num_total - 10;
	echo "<br><meter min='0' max='" . $var_num_total . "' low='10' high='" . $var_high . "' optimum='" . $var_optim . "' value='" . $var_num_total_ass . "'></meter>";
	echo "</div>";
	
	echo "<div class='message error'><i class='icon-exclamation-sign'></i>";
	$query1 = "SELECT id,nom_pinya FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1'  AND canalla = '0') ORDER BY nom_pinya";
	$result = mysqli_query($con,$query1);
	$var_num_total = mysqli_affected_rows($con);
	$arr_troncs = array();
	$arr_id_troncs = array();
	while($cens_troncs = mysqli_fetch_array($result))
	{
		$arr_id_troncs[$cens_troncs['id']] = $cens_troncs['nom_pinya'];
		/*$arr_id_troncs[] = $cens_troncs['id'];
		$arr_troncs[] = htmlentities(trim($cens_troncs['nom_pinya']));*/ 
	}
	$arr_baixes = array_diff($arr_id_troncs,$arr_assistents);
	$texte = join(', ',$arr_baixes);
	echo htmlentities($texte) . "  ( " . ($var_num_total - $var_num_total_ass) . " )</div>";
	
	echo "</div></div>";
	
	/* assistencia prevista mensual per dia*/
	echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='full_troncs_collapsible'>
				<h4>Assist&egrave;ncia Prevista Mes (per dia)</h4>
				<div class='inset'>";
	/* creo un array [assaigs] amb les dates dels propers  dimarts i divendres en un mes */ 
	$d = new DateTime('-2 days');
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

	/* hi afegeixo les properes 6 diades i reordeno */
	/*$sql = "SELECT DISTINCT diada FROM assistencia where diada > '2014-01-01' ORDER BY diada";*/
	$sql = "SELECT DISTINCT id_diada, data, motiu FROM diades where data >= '" . $avui . "' ORDER BY data LIMIT 6";
	$res = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_array($res)) {
	    $dates[] = $row[1];
	}
	asort($dates);
	$arr_ids = join(',',array_keys($arr_id_troncs));
	/*$arr_dates = join(",",$dates);*/
	$sql = "SELECT a.id,a.diada,a.previst FROM assistencia a WHERE (a.id IN ($arr_ids) AND a.diada IN ('".implode("','",$dates)."')) ORDER BY previst DESC,a.nom_pinya ASC";
	echo $sql;
	$res = mysqli_query($con,$sql);
	$var_cnt = mysqli_affected_rows($con);
		
	echo "
			<ul class='llista_per_tronc'>";
	/*$query_assistents = "SELECT * FROM assistencia WHERE MONTH(diada) = " . $mes_actual . " AND YEAR(diada) = " . $any_actual . " AND `previst`=1 ORDER BY diada,nom_pinya";
	
	$assistents = mysql_query($query_assistents, $con_llista);
	$var_num_total = mysql_num_rows($assistents);*/
	$arr_assistencia = array();
	$data_actual = "";
	$txt_titol = "";
	while($assistencies = mysqli_fetch_array($res))
	{
		if ($assistencies[id_esdeveniment] == 0) {
			if ($assistencies[franja_assaig] == 0) $txt_titol = "DIA"; /* DIADA */
			else $txt_titol = "ASSAIG";
		}
		else $txt_titol = "ACTIVITAT";
		$data_formatada = "<span>" . $txt_titol . " <br>" . date("j-n",strtotime($assistencies[diada])) . "</span>";
		$bandarra_id = $assistencies[id];
		if ($data_formatada != $data_actual) {
			$arr_assistencia[$data_formatada] = array (
				$assistencies[id] => array('nom_pinya'=>$arr_id_troncs[$bandarra_id],'id_activitat'=>$assistencies[id_esdeveniment],'previst'=>$assistencies[previst])
				);
				$data_actual = $data_formatada;
		}
		else $arr_assistencia[$data_formatada] [ $assistencies[id]] = array('nom_pinya'=>$arr_id_troncs[$bandarra_id],'id_activitat'=>$assistencies[id_esdeveniment],'previst'=>$assistencies[previst]);
	}
	foreach ($arr_assistencia as $i => $dia_assistencia) {
		$contador_noms = 0;
		
		echo ("<li class='sub' id='" . $i . "'><h3>" . $i . "</h3><ul class='column'>");
		foreach ($dia_assistencia as $tronc_assistent) {
			 	$id_bandarra = key($dia_assistencia);
				echo ("<li id='" . $id_bandarra. "' >".  $tronc_assistent[nom_pinya] . "</li>");
		}
		echo "</ul></li>";
	}
	echo "</ul></li>";
	echo "</ul>";


	echo "</div></div>";

	/*******************************************************************************/

	/* assistencia prevista mensual per persona*/
	echo "<div data-role='collapsible' data-collapsed='true' data-content-theme='b' class='full_troncs_collapsible'>
				<h4>Assist&egrave;ncia Prevista Mes</h4>
				<div class='inset'>";
	/* creo un array [assaigs] amb les dates dels propers  dimarts i divendres en un mes */ 
	$d = new DateTime('-2 days');
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

	/* hi afegeixo les properes 6 diades i reordeno */
	/*$sql = "SELECT DISTINCT diada FROM assistencia where diada > '2014-01-01' ORDER BY diada";*/
	$sql = "SELECT DISTINCT id_diada, data, motiu FROM diades where data >= '" . $avui . "' ORDER BY data LIMIT 6";
	$res = mysqli_query($con,$sql);
	while ($row = mysqli_fetch_array($res)) {
	    $dates[] = $row[1];
	}
	asort($dates);
	$arr_ids = join(',',array_keys($arr_id_troncs));
	/*$arr_dates = join(",",$dates);*/
	$sql = "SELECT a.id,a.diada,a.previst FROM assistencia a WHERE (a.id IN ($arr_ids) AND a.diada IN ('".implode("','",$dates)."')) ORDER BY a.id";
	$res = mysqli_query($con,$sql);
	$var_cnt = mysqli_affected_rows($con);
	if ($var_cnt > 0) {
		/***********************************
		* Table headings                   *
		************************************/
		$emptyRow = array_fill_keys($dates,'');
		// format dates
		foreach ($dates as $k=>$v) {
		    $dates[$k] = date('d/n', strtotime($v));
		}
		$heads = "<table  data-role='table' id='taula_assistencia_mensual' data-mode='columntoggle' class='ui-responsive table-stroke stripe'>\n";
		$heads .= "<tr><th class='petit'>Nom</th><th class='petit'>" . join('</th><th class="petit">', $dates) . "</th></tr>\n";

		/***********************************
		* Main data                        *
		************************************/
		/*$sql = "SELECT a.id,a.diada,a.nom_pinya,a.previst,c.id FROM assistencia a, cens c WHERE (c.data_baixa = '0000-00-00' AND (c.estat = 'A' OR c.estat = 'MA' or c.estat = 'O') AND c.troncs = '1' AND a.id=c.id AND YEAR(a.diada) = '2014') ORDER BY a.nom_pinya";*/
		
		$curname='';
		$tdata = '';
		while (list($id, $d, $s) = mysqli_fetch_array($res)) {
		    if ($curname != $arr_id_troncs[$id]) {
			if ($curname) {
			    $tdata .= "<tr><td class='nom petit'>" . htmlentities($curname) . "</td><td class=\"td_assistencia\">" . join('</td><td class="td_assistencia">', $rowdata). "</td></tr>\n";
			}
			$rowdata = $emptyRow;
			$curname = $arr_id_troncs[$id];
		    }
		    $rowdata[$d] = $s;
		}
		$tdata .= "<tr><td class='nom petit'>" .htmlentities($curname). "</td><td class=\"td_assistencia\">" . join('</td><td class="td_assistencia">', $rowdata). "</td></tr>\n";
		$tdata .= "</table>\n";
		
		$tdata = str_replace('td_assistencia">1','td_assistencia ve"><i class="lIcon fa fa-check"></i>',$tdata);
		$tdata = str_replace('td_assistencia">0','td_assistencia no_ve"><i class="lIcon fa fa-times"></i>',$tdata);
		
		echo $heads;
		echo $tdata;
	}
	else {
		echo "Sense dades.";
	}
	echo "</div></div>";


	/* Mides Gent a Treballar */
	echo "<div data-role='collapsible' data-content-theme='b'>
		<h4>Mides</h4>
		<div class='inset'>";
	$query_posicions = "select id,cognom_1,nom,nom_pinya,altura,pes,pis7,pis8 from cens where `data_baixa` = '0000-00-00' and troncs = '1' and estat <> 'NA' and canalla <> '1' order by pis8 DESC, altura DESC, nom_pinya ASC";
	$result = mysqli_query($con,$query_posicions);
	
	echo "<form action='' method='get'>";
	echo "<table data-role='table' id='table-column-toggle' data-mode='columntoggle' class='ui-responsive table-stroke'>
              <thead>
                <tr>
                  <th data-priority='1' class='petit'>Nom</th>
                  <th data-priority='1' class='petit'>Al&ccedil;ada (cm)</th>
                  <th data-priority='1' class='petit'>Pes (kg)</th>
                  <th data-priority='3' class='petit'>Pis7</th>
                  <th data-priority='4' class='petit'>Pis8</th>
		</tr>
              </thead>
              <tbody>";
	if(mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_array($result))
		{
		    echo "<tr>";
                    echo "<td style='overflow: hidden; width: 30px; text-align: left; valign: top; whitespace: nowrap;'>" . htmlentities($row['nom_pinya']) . "</td>";
                    echo "<td style='overflow: hidden; width: 30px; text-align: left; valign: top; whitespace: nowrap;'><input type=\"text\" maxlength=\"3\" size=\"2\" name=\"altura\" id=\"altura\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','altura', this.value)\" value=\"" . $row['altura'] . "\" /></td>";
                    echo "<td style='overflow: hidden; width: 30px; text-align: left; valign: top; whitespace: nowrap;'><input type=\"text\" maxlength=\"3\" size=\"2\" name=\"pes\" id=\"pes\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','pes', this.value)\" value=\"" . $row['pes'] . "\" /></td>";
                    echo "<td style='overflow: hidden; width: 30px; text-align: left; valign: top; whitespace: nowrap;'><input type=\"text\" maxlength=\"3\" size=\"2\" name=\"pis7\" id=\"pis7\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','pis7', this.value)\" value=\"" . $row['pis7'] . "\" /></td>";
                    echo "<td style='overflow: hidden; width: 30px; text-align: left; valign: top; whitespace: nowrap;'><input type=\"text\" maxlength=\"3\" size=\"2\" name=\"pis8\" id=\"pis8\" onchange=\"actualitza_dades_cens('" . $row['id'] . "','pis8', this.value)\" value=\"" . $row['pis8'] . "\" /></td>";
		    echo "</tr>";
		} 
	}
	echo "</tbody></table>";
	echo "</form>";
	echo "</div></div>";
	/*echo "<div data-role=\"popup\" id=\"popup_texte\" data-theme=\"none\" data-overlay-theme=\"a\" data-corners=\"false\" data-tolerance=\"15\">' + closebtn + header + img + '</div>";*/
	
	echo "</div>"; /* id=full_troncs */
	mysqli_close($con);
?>