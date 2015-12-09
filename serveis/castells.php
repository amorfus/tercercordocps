<!--<head>-->
<!-- Per als guions d'assaig -->
	<!--<link rel="stylesheet" type="text/css" href="./css/guions.css" />
</head>-->
<?php
define('INCLUDE_CHECK',true);
require "connect.php";
require "castells.class.php";

 
$var_data = $_POST['data'];
$var_data_formatada = date("d-m-Y",strtotime($var_data));
// Select all the CastellDiadas, ordered by position:

/*$query = mysql_query("SELECT * FROM `resultats` WHERE `data` = '" . $var_data . "' ORDER BY `ronda` ASC");*/
if (isset($_POST['diada_id']) || isset($_GET['diada_id'])) {
	$var_id_diada = $_GET['diada_id'];
	if ($var_id_diada == '') $var_id_diada = $_POST['diada_id'];
	$query = mysql_query("SELECT * FROM `resultats` WHERE `diada_id` ='" . $var_id_diada . "' ORDER BY `ronda` ASC");
	$query1 = mysql_query("SELECT * FROM diades WHERE id_diada = '" . $var_id_diada . "'");
	$dades_diada = mysql_fetch_array($query1);
}
else {
	$query = mysql_query("SELECT * FROM `resultats` WHERE `diada_id` = (SELECT id_diada from diades where data = '" . $var_data . "') ORDER BY `ronda` ASC");
	$var_id_diada = '0000-00-00';
}

$CastellsDiada = array();

// Filling the $CastellsDiada array with new CastellDiada objects:

while($row = mysql_fetch_assoc($query)){
	$CastellsDiada[] = new CastellDiada($row);
}
echo "<div class='inset'>";
if ($var_data != '0000-00-00') //assaig o diada
	{
		$var_data_formatada = date("d-m-Y",strtotime($dades_diada['data']));
		echo "<h1 class='tercercordo-titol'>" . $dades_diada['motiu'] . "</h1><h3><span>" . $dades_diada['lloc'] . ", " . $var_data_formatada . "</span></h3>";
		/*echo "<div id='llista_close' class='no_print'>";*/
		echo "<a onclick=\"javascript:$.mobile.activePage.find('#panell_auxiliar').panel('open');return false;\" class=\"ui-block-a afegir_castell no_print ui-btn\" href=\"#\"><i class='lIcon fa fa-plus'> Afegir Castell</i></a>";
		/*echo "<a onclick='javascript:jQuery('#paper_esquerra').stop().animate({'left': '-800px'}, 'slow'); jQuery('#contadors').stop().animate({top:'3000'}); restaura_pantalla();return false;\"
		href=\"#\"><i class='lIcon fa fa-times'></i></a>";*/
		/*echo "</div>";*/
	}
else //troncs de treball
	{
		/*echo "<h2>Troncs de Treball";*/
		/*echo "<a id=\"afegir_prova\" onclick=\"javascript:afegir_prova_guio('<?php echo($var_data);?>','Prova');return false;\" class=\"green-button no_print\" href=\"#\">Afegir Prova +</a>":*/
		/*echo "<div id=\"llista_close\" class=\"no_print\">";*/
		//<input type=button class=\"no_print\" value=\"Imprimir\" onclick=\"javascript:printPage('#llista_guions');\">";
		/*echo "<a onclick=\"javascript:printPage('#llista_guions');\" href=\"#\"><img src=\"images/icones/32px/printer.png\" alt=\"Imprimir\"></a>";*/
		/*echo "<a onclick=\"javascript:jQuery('#paper_esquerra').stop().animate({'left': '-800px'}, 'slow'); jQuery('#contadors').stop().animate({top:'3000'}); restaura_pantalla();return false;\"
		href=\"#\"><img src=\"./pinyes_imatges/icones/snow/Stop.png\" alt=\"Tanca Gui&oacute;\"></a></div></h2>";*/
	}
	
echo "<br><ul id=\"sortable\" class=\"todoList\">";	
		// Looping and outputting the $CastellsDiada array. The __toString() method
		// is used internally to convert the objects to strings:
		
		foreach($CastellsDiada as $item){
			echo $item;
		}

echo "</ul></div>";