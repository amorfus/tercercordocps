<?php
define('INCLUDE_CHECK',true);
require "connect.php";
require "guions.class.php";


$id = (int)$_GET['id'];
$var_data = $_GET['data'];
$var_tipus = $_GET['tipus'];
try{

	switch($_GET['action'])
	{
		case 'delete':
			ToDo::delete($id);
			break;
			
		case 'rearrange':
			ToDo::rearrange($_GET['positions'], $var_data);
			break;
			
		case 'edit':
			ToDo::edit($id,$_GET['text']);
			break;
			
		case 'new':
			ToDo::createNew($_GET['prova'],$var_data,$var_tipus);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

echo "1";
?>
