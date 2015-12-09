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
	/*echo "<ul data-role='listview' data-inset='true' data-theme='b'><li data-role='list-divider'>Menu</li>";*/
	/*echo "<ul data-nativedroid-plugin='gallery'>";*/

	/*echo "<div data-role='popup' id='popup_gent_troncs' class='ui-content'>";*/
	echo "<ul class='llista_gent_tronc' data-inset='true' data-theme='b'>";

	/*echo "<div class='checkout checkout--active'>";*/
	/*echo "<a class='checkout__button' href='#'><span class='checkout__text'>";
	echo "<span class='checkout__text-inner checkout__initial-text'><i class='lIcon fa fa-group'></i></span>";
	echo "<span class='checkout__text-inner checkout__final-text'><i class='lIcon fa fa-times'></i></i></span></span></a>";*/
	/*echo "<div class='checkout__order'>;*/
	/*echo "<div class='checkout__order-inner'>";*/
	/*echo "<table class='checkout__summary'>";

	echo "<thead><tr><th>Nom</th><th>Pis</th><th>Al&ccedil;ada</th><th>&nbsp;</th></tr></thead>";
	echo "<tbody>";*/
				
	$query1 = "SELECT id,nom_pinya,canalla,altura,pes,pis7,pis8 FROM cens WHERE (data_baixa = '0000-00-00' AND (estat = 'A' OR estat = 'MA' or estat = 'O') AND troncs = '1') ORDER BY canalla,pis8 DESC,nom_pinya";
	$result = mysqli_query($con,$query1);
	$pis_actual = '0';
	while($bandarra = mysqli_fetch_array($result)) {
		$var_id = $bandarra[id];
		$var_nom = $bandarra[nom_pinya];
		$var_pis7 = $bandarra[pis7];
		$var_pis8 = $bandarra[pis8];
		/*$foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png";
		if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "http://www.castellersdelpoblesec.cat/prog_pinyes/pinyes_imatges/gent/thumbs/ningu.png"; }*/
        $foto = "images/gent/thumbs/" . $bandarra['id'] . ".png";
        if (!file_exists ("../../pinyes_imatges/gent/thumbs/" . $bandarra['id'] . ".png")) { $foto = "images/gent/thumbs/ningu.png"; }
		/*$query2 = "SELECT altura,pes from cens WHERE id='" . $var_id . "'";
		$result = mysqli_query($con,$query2) or exit(mysql_error());
		$mides = mysqli_fetch_array($result);*/
		$var_altura = $bandarra['altura'];
		$var_pes = $bandarra['pes'];
		
		if ($var_pis8 != $pis_actual) { 
			switch ($var_pis8) {
				case '5': 	echo "<h1 class='pis_assist'>5s</h1>";
							break;
				case '4': 	echo "<h1 class='pis_assist'>4s</h1>";
							break;
				case '3': 	echo "<h1 class='pis_assist'>3s</h1>";
							break;
				case '2': 	echo "<h1 class='pis_assist'>2s</h1>";
							break;
				case '1': 	echo "<h1 class='pis_assist'>Bs</h1>";
							break;
			}
			$pis_actual = $var_pis8; 
		}

		/*echo "<li style='background:url(\"". $foto . "\") no-repeat center center;'><a href='#'><h2>" . htmlentities($var_nom) . "</h2><span>" . $var_altura . "</span></a></li>";*/
		
		/*echo "<tr><a href='#' onclick=\"$('#pag_segona').slideToggle();\"><td class='nom'><img src='". $foto . "'> " . htmlentities($var_nom) . "</td><td>" . $var_pis7 . " | " . $var_pis8 . "</td><td>" . $var_altura . "</td><td><button class='checkout__action'><i class='icon fa fa-trash'></i></button></td></a></tr>";*/
		if ($var_pis8 == '') $pis = $var_pis7;
		else $pis = $var_pis8;
		/*echo "<li class='nom' style='background: url(\"" . $foto . "\") no-repeat center center;width:55px;height:55px;'><a href='#inici' onclick=\"canvia_posicio_tronc_clickada($var_id,'$foto',$var_altura);\"><span class='pis'>" . $pis . "</span><span class='nom'>" . htmlentities($var_nom) . "</span><span class='altura'>" . $var_altura . "</span></a><button class='checkout__action'><i class='icon fa fa-trash'></i></button></li>";*/
		echo "<li class='nom' style='background: url(\"" . $foto . "\") no-repeat center center;width:55px;height:55px;'><a href='#inici' onclick=\"canvia_posicio_tronc_clickada($var_id,'$foto',$var_altura);$('#panell_auxiliar').panel('close');\"><span class='nom'>" . htmlentities($var_nom) . "</span><span class='altura'>" . $var_altura . "</span></a><button class='checkout__action'><i class='icon fa fa-trash'></i></button></li>";
		/*echo "<li style='background:url(\"" . $foto . "\") no-repeat' data-image-large='" . $foto . "' data-image-thumb='" . $foto . "'><div class='closeTrigger'><i class='icon-remove'></i> tanca</div><span>$var_nom</span></li>";*/
				
		$parell []= array('id'=>$var_id,'nom'=>$var_nom,'posicio'=>$var_posicio,'foto'=>$foto,'altura'=>$var_altura,'pes'=>$var_pes);
		$data= $data . $parell;
	
		/*echo "<li data-icon='male'>" . htmlentities($bandarra['nom_pinya']) . "</li>";*/
	}
	/*echo "</tbody>";

	echo "</table><!-- /checkout__summary -->";*/
	/*echo "<button class='checkout__close checkout__cancel'><i class='lIcon fa fa-times'></i>Tancar</button>";*/
	/*echo "</div><!-- /checkout__order-inner -->";*/
	/*echo "</div><!-- /checkout__order -->";*/
	/*echo "</div><!-- /checkout -->";*/

	echo "</ul>";
	/*echo "</div>";*/
	mysqli_close($con);
?>