<?php
define('INCLUDE_CHECK',true);
require "connect.php";
require "castells.class.php";


$var_id_diada = (int)$_GET['id_diada'];
$var_resultat_id = $_GET['resultat_id'];
$var_resultat = $_GET['resultat'];
$var_castell = $_GET['castell'];
try{

	switch($_GET['action'])
	{
		case 'delete':
			CastellDiada::delete($var_resultat_id);
			break;
			
		case 'rearrange':
			CastellDiada::rearrange($_GET['positions'], $var_resultat_id);
			break;
			
		case 'edit':
			CastellDiada::edit($var_resultat_id,$var_resultat);
			break;
			
		case 'new':
			CastellDiada::createNew($var_id_diada,$var_castell);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

echo "1";
?>
