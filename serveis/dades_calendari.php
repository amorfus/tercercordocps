<?php
define('INCLUDE_CHECK',true);

require "connect.php";
require "functions.php";

 $con = mysql_connect( $db_host, $db_user, $db_pass);
	 if(!$con)
	   die('Could not connect: ' . mysql_error() );
	 
	 mysql_select_db($db_database, $con);

if (isset($_POST['dades'])) $dades=$_POST['dades'];
if (isset($_GET['dades'])) $dades=$_GET['dades'];
if (isset($_POST['punts'])) $punts=$_POST['punts'];
else $punts=$_GET['punts'];
/*if (isset($_POST['dades'])) $dades=$_POST['dades'];
else $dades=6;
switch ($dades) 
	{
	case 1: 
			$diades = mysql_query("SELECT * FROM diades WHERE data <> '0000-00-00' order by data");
			while($diada = mysql_fetch_array($diades))
				{	
					$var_id = $diada[data] . "_" . $diada[hora];
					$var_title = $diada[motiu];
					$var_start = $diada[data];
					$var_end = $diada[data];
					$var_url = "/prog_pinyes/autoritzat.php?actuacio_select=" . $diada[data];
					
					$array_dades []= array('id'=>$var_id,'title'=>$var_title,'start'=>$var_start,'end'=>$var_end,'url'=>$var_url);
				}
			break;
	case 2:
			echo "Assaigs";
			break;
	case 4:
			$incidencies = mysql_query("SELECT * FROM incidencies order by start");
			while($incidencia = mysql_fetch_array($incidencies))
				{	
					$var_id = $incidencia['id'];
					$var_title = $incidencia['nom_pinya'];
					$var_start = $incidencia['inici'];
					$var_end = $incidencia['final'];
					
					$array_dades []= array('id'=>$var_id,'title'=>$var_title,'start'=>$var_start,'end'=>$var_end,'backgroundColor '=>'red');
				}
			break;
	case 6:*/
			$diades = mysql_query("SELECT * FROM diades WHERE data <> '0000-00-00' order by data");
			while($diada = mysql_fetch_array($diades))
				{	
					$var_id = $diada[id_diada];
					$var_title = $diada[motiu];
					$var_start = $diada[data];
					$var_end = $diada[data];
					/*if ($dades!="assist")
						$var_url = "/prog_pinyes/autoritzat.php?actuacio_id=" . $var_id;
					else*/
						$var_url = "#";
					
					$array_dades []= array('id'=>$var_id,'title'=>$var_title,'start'=>$var_start,'end'=>$var_end,'className'=>'diada','url'=>$var_url,'backgroundColor'=>'#71b2ff');
				}
			$incidencies = mysql_query("SELECT * FROM incidencies order by inici");
			while($incidencia = mysql_fetch_array($incidencies))
				{	
					$var_id = $incidencia['id'];
					$var_title = $incidencia['nom_pinya'];
					$var_start = $incidencia['inici'];
					$var_end = $incidencia['final'];
					
					$array_dades []= array('id'=>$var_id,'title'=>$var_title,'start'=>$var_start,'end'=>$var_end,'backgroundColor'=>'red','borderColor'=>'red');
				}
			$activitats = mysql_query("SELECT * FROM activitats order by inici");
			while($activitat = mysql_fetch_array($activitats))
				{	
					$var_id = $activitat['id'];
					$var_title = $activitat['activitat'];
					$var_start = $activitat['inici'];
					$var_end = $activitat['final'];
					/*if ($dades!="assist")
						$var_url = "/prog_pinyes/autoritzat.php?actuacio_select=" . $activitat[inici];
					else*/
						$var_url = "#";
					$array_dades []= array('id'=>$var_id,'title'=>$var_title,'start'=>$var_start,'end'=>$var_end,'className'=>'activitat','url'=>$var_url,'backgroundColor'=>'orange','borderColor'=>'yellow');
				}
			if ($punts == 'si') {
				$punts = mysql_query("SELECT * FROM punts_canalla order by data");
				while($dies_processats = mysql_fetch_array($punts))
				{	
					$var_id = $dies_processats['data'] . 'p';
					$var_title = 'PUNTUAT';
					$var_start = $dies_processats['data'];
					$var_end = $dies_processats['data'];
					
					$array_dades []= array('id'=>$var_id,'title'=>$var_title,'start'=>$var_start,'end'=>$var_end,'className'=>'activitat','url'=>'#','backgroundColor'=>'orange','borderColor'=>'yellow');
				}
			}
			/*break;
	default;
	}*/
	
echo json_encode($array_dades);	
mysql_close($con);

?>