 <?php
	define('INCLUDE_CHECK',true);
	require_once('connect.php');
	
	/*header("access-control-allow-origin: *");*/

	$con = mysqli_connect($db_host, $db_user, $db_pass,$db_database);
	if (mysqli_connect_errno($con)) {
		echo("Error de conexio: ". mysqli_connect_error());
		exit();
	}

	if(!$_GET['username'] || !$_GET['password']) {
		$response['success'] = false;
		/*$err[] = */ /*echo 'Has d\'omplir tots els camps!';*/
	}
	/*if(!count($err))*/
	else
	{
		$_GET['username'] = mysqli_real_escape_string($_GET['username']);
		$_GET['password'] = mysqli_real_escape_string($_GET['password']);
		$_GET['rememberMe'] = (int)$_GET['rememberMe'];
		
		// Escaping all input data
		$sql = "SELECT id,equip,usr,junta FROM usuaris WHERE activat='S' AND usr='{$_GET['username']}' AND pass='" . md5($_GET['password']) . "'";
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_assoc($result);

		if($row['usr'])
		{
			// If everything is OK login
			
			$_SESSION['usr']=$row['usr'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['equip'] = $row['equip'];
			$_SESSION['junta'] = $row['junta'];
			$_SESSION['rememberMe'] = $_GET['rememberMe'];
			
			$response['success'] = true;
			
			// Store some data in the session
			
			/*setcookie('tzRemember',$_GET['rememberMe']);*/
		}
		else {
			$err[]='Error en l\'usuari/a o contrasenya!';
			$response['success'] = false;
		}
	}


           /* $result=mysql_query($sql);
            $num_row = mysql_num_rows($sql);
            $row=mysql_fetch_array($result);


            if (is_object($result) && $result->num_rows == 1) {
            $response['success'] = true;

            }
            else
            {
            $response['success'] = false;
            }*/

           echo json_encode($response);

             //echo 'OK';

            ?>