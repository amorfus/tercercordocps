<?
include_once 'User.php';
include_once 'DB.php';

class UserFactory {

    private function __construct() {}
    
    public static function makeUserFromRequest($request, $vd)
    {
    	$check = true;
    	 
    	$toRet = new User();
    	
    	if($request['first-password'] != $request['confirm-password'])
    	{
    		$vd->setErrorMessage('Las passwords no coinciden.');
    		$check = false;
    	}
    	
    	if(!$toRet->setNombre($request['nombre'])){ $vd->setErrorMessage('Nombre non valido.'); $check = false;}
    	if(!$toRet->setApellido($request['apellido'])){ $vd->setErrorMessage('Apellido non valido.'); $check = false;}
    	
    	if(isset($request['username']))
    		if(!$toRet->setUsername($request['username'])){ $vd->setErrorMessage('Username non valido.'); $check = false;}
    		
    	if(isset($request['first-password']))	
    		if(!$toRet->setPassword($request['first-password'])){ $vd->setErrorMessage('Password non valida.'); $check = false;}
    	 
    	if(isset($request['roll']))
    		if(!$toRet->setRoll($request['roll'])){ $vd->setErrorMessage('Roll non valido.'); $check = false;}
    	else
    		$toRet->setRoll(User::User);
    	     
    	if(!$toRet->setEmail($request['email'])){ $vd->setErrorMessage('E-Mail non valida.'); $check = false;}
    	if(!$toRet->setDireccion($request['direccion'])){ $vd->setErrorMessage('Direccion non valido.'); $check = false;}
    	 
    	if($check)
    		return $toRet;
    	else
    		return false;
    }
    
    public static function recoverUserFromRequest($request) {
    	$toRet = new User();
    	
    	$toRet->setNombre($request['nombre']);
    	$toRet->setApellido($request['apellido']);
    	
    	if(isset($request['username']))
    		$toRet->setUsername($request['username']);
    	
    	$toRet->setEmail($request['email']);
    	$toRet->setCap($request['direccion']);
    	 
    	return $toRet;
    }
    
    public static function storeUser(User $user, ViewDescriptor $vd)
    {
    	$nombre = $user->getNombre();
    	$apellido = $user->getApellido();
    	$username = $user->getUsername();
    	$password = $user->getPassword();
    	$email = $user->getEmail();
    	$roll = User::Buyer;
    	$via = $user->getVia();
    	$civico = $user->getNumeroCivico();
    	$citta = $user->getCitta();
    	$provincia = $user->getProvincia();
    	$cap = $user->getCap();
    	
    	$stmt = DB::istance()->stmt_init();
    	
    	$qry = "SELECT 
    				* 
    			FROM User 
    			WHERE Username=?";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param('s', $username);
    	
    	if(!$stmt->execute()) 
    		return false;
    	
    	$stmt->store_result();
    	
    	if($stmt->num_rows == 0)
    	{
	    	$password = md5($password);
	    
	    	$qry = 'INSERT INTO User 
	    				(Nombre, Apellido, Username, Password, Email, Roll, Via, Civico, Citta, Provincia, Cap) 
	    			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
	    	
	    	$stmt->prepare($qry);
	    	
	    	$stmt->bind_param('sssssisissi',$nombre,
	    									$apellido,
	    									$username,
	    									$password,
	    									$email,
	    									$roll,
	    									$via,
	    									$civico,
	    									$citta,
	    									$provincia,
	    									$cap);
	    	
	    	if($stmt->execute())
	    	{
	    		return true;
	    	}
	    	else
	    	{
	    		$vd->setErrorMessage("Errore di salvataggio dati, per favore riprova piu tardi.$stmt->errno $stmt->error");
	    		return false;
	    	}
    	}
    	else 
    	{
    		$vd->setErrorMessage("Username gia in uso.");
    		return false;
    	}
    	
    }
    
    public static function updateUser(User $user)
    {
    	$id = $user->getId();
    	$nombre = $user->getNombre();
    	$apellido = $user->getApellido();
    	$email = $user->getEmail();
    	$via = $user->getVia();
    	$civico = $user->getNumeroCivico();
    	$citta = $user->getCitta();
    	$provincia = $user->getProvincia();
    	$cap = $user->getCap();
    	 
    	$stmt = DB::istance()->stmt_init();
    	 
    	$qry = "
    			UPDATE User 
				SET 
					Nombre = ?,
    				Apellido = ?,
    				Email = ?,
    				Via = ?,
    				Civico = ?,
    				Citta = ?,
    				Provincia = ?,
    				Cap = ?
				WHERE	
					ID = ?
    	";
    	 
    	$stmt->prepare($qry);
    	 
    	$stmt->bind_param('ssssissii', $nombre, $apellido, $email, $via, $civico, $citta, $provincia, $cap, $id);
    	 
    	if($stmt->execute())
    		return true;
    	return false;    	 
    }

    /**
     * Carica un utente tramite username e password
     * @param string $username
     * @param string $password
     * @return \User|\Docente|\Studente
     */
    public static function loadUser($username, $password) 
    {
    	$stmt = DB::istance()->stmt_init();
    
    	$pass = md5($password);
    	
    	$qry = "SELECT 
    				ID,
    				Nombre,
    				Apellido,
    				Username,
    				Password,
    				Email,
    				Roll,
    				Via,
    				Civico,
    				Citta,
    				Provincia,
    				Cap,
    				Credito
    			FROM User 
    			WHERE Username=? AND Password=?";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param("ss", $username, $pass);
    	
    	if(!$stmt->execute())
    		return false;
    	
    	$stmt->store_result();

    	if($stmt->num_rows > 0)
    	{	
    		$stmt->bind_result(	
    				$res_id, $res_nombre, 
    			   	$res_apellido, 
   					$res_username, 
    				$res_password, 
   					$res_email, 
    				$res_roll, 
    				$res_via, 
    				$res_civico, 
    				$res_citta, 
    				$res_provincia, 
    				$res_cap, 
    				$res_credito
    		);

	    	$stmt->fetch(); 
	    	
	    	if(Settings::debug)
	    		echo " cap da db: $res_cap";
	    	
	    		
    		$toRet = new User();
    		$toRet->setRoll($res_roll);
    		$toRet->setNombre($res_nombre);
    		$toRet->setApellido($res_apellido);
    		$toRet->setUsername($res_username);
    		$toRet->setPassword(md5($res_password));
    		$toRet->setVia($res_via);
    		$toRet->setNumeroCivico($res_civico);
    		$toRet->setCitta($res_citta);
    		$toRet->setProvincia($res_provincia);
    		$toRet->setCap($res_cap);
    		$toRet->setId($res_id);
    		$toRet->setEmail($res_email);
    		$toRet->addCredito($res_credito);
    		
    		if(Settings::debug)
    			echo " cap:" . $toRet->getCap();
	    	
		    
		    if($toRet->getRoll() == User::Seller)
		    {
		    	$negozio = NegozioFactory::loadNegozioFromUserID($toRet->getId());
		    	
		    	if($negozio)
		    		$toRet->setNegozio($negozio);
		    	else 
		    		return false;
		    }
		    
		    return $toRet;
    	}
    	
    	return false;
		
    }    
    
    public static function changeRoll(User $user, $roll)
    {
    	$stmt = DB::istance()->stmt_init();
    	
    	$qry = "
    			UPDATE User 
    			SET Roll=? 
    			WHERE ID=?
    	";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param('ii', $roll, $user->getId());
    	
    	return $stmt->execute();
    }
    
    public static function getListaUtenti()
    {
    	$listUser = array();
    	$admin = User::Administrator;
    	
    	$stmt = DB::istance()->stmt_init();
    	 
    	$qry = "SELECT
    				ID,
    				Nombre,
    				Apellido,
    				Username,
    				Password,
    				Email,
    				Roll,
    				Via,
    				Civico,
    				Citta,
    				Provincia,
    				Cap,
    				Credito
    			FROM User
    			WHERE Roll != ?
    			
    	";
    	
    	if(Settings::debug)
    		echo "admin: $admin";

    	if(Settings::debug)
    		echo $stmt->errno.' - '.$stmt->error;
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param('i', $admin);
    	 
    	if(!$stmt->execute())
    		return false;
    	 
    	$stmt->store_result();
    	
    	if($stmt->num_rows > 0)
    	{
    		$stmt->bind_result(
    				$res_id, $res_nombre,
    				$res_apellido,
    				$res_username,
    				$res_password,
    				$res_email,
    				$res_roll,
    				$res_via,
    				$res_civico,
    				$res_citta,
    				$res_provincia,
    				$res_cap,
    				$res_credito
    		);
    	
    		while($stmt->fetch())
    		{
    	
	    		$toRet = new User();
	    	
	    		$toRet->setRoll($res_roll);
	    		$toRet->setNombre($res_nombre);
	    		$toRet->setApellido($res_apellido);
	    		$toRet->setUsername($res_username);
	    		$toRet->setPassword(md5($res_password));
	    		$toRet->setVia($res_via);
	    		$toRet->setNumeroCivico($res_civico);
	    		$toRet->setCitta($res_citta);
	    		$toRet->setProvincia($res_provincia);
	    		$toRet->setCap($res_cap);
	    		$toRet->setId($res_id);
	    		$toRet->setEmail($res_email);
	    		$toRet->addCredito($res_credito);
	    		
	    		if($toRet->getRoll() == User::Seller)
	    		{
	    			$negozio = NegozioFactory::loadNegozioFromUserID($toRet->getId());
	    		
	    			if($negozio)
	    				$toRet->setNegozio($negozio);
	    			else
	    				return false;
	    		}
	    		
	    		$listUser[] = $toRet;
    		}
    	
    		return $listUser;
    	}
    	 
    	return false;
    	 
    }
    
    public static function &loadUserFromId($id)
    {
    	$stmt = DB::istance()->stmt_init();
    	
    	$qry = "SELECT 
    				ID,
    				Nombre,
    				Apellido,
    				Username,
    				Password,
    				Email,
    				Roll,
    				Direccion
    			FROM User 
    			WHERE ID = ? ";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param("i", $id);
    	
    	if(!$stmt->execute())
    		return false;
    	
    	$stmt->store_result();

    	if($stmt->num_rows > 0)
    	{	
    		$stmt->bind_result(	
    				$res_id, $res_nombre, 
    			   	$res_apellido, 
   					$res_username, 
    				$res_password, 
   					$res_email, 
    				$res_roll, 
    				$res_via, 
    				$res_civico, 
    				$res_citta, 
    				$res_provincia, 
    				$res_cap, 
    				$res_credito
    		);

	    	$stmt->fetch(); 
	    	
    		$toRet = new User();
    		
    		$toRet->setRoll($res_roll);
    		$toRet->setNombre($res_nombre);
    		$toRet->setApellido($res_apellido);
    		$toRet->setUsername($res_username);
    		$toRet->setPassword(md5($res_password));
    		$toRet->setVia($res_via);
    		$toRet->setNumeroCivico($res_civico);
    		$toRet->setCitta($res_citta);
    		$toRet->setProvincia($res_provincia);
    		$toRet->setCap($res_cap);
    		$toRet->setId($res_id);
    		$toRet->setEmail($res_email);
    		$toRet->addCredito($res_credito);
	    	
		    
		    if($toRet->getRoll() == User::Seller)
		    {
		    	$negozio = NegozioFactory::loadNegozioFromUserID($toRet->getId());
		    	
		    	if($negozio)
		    		$toRet->setNegozio($negozio);
		    	else 
		    		return false;
		    }
		    
		    return $toRet;
    	}
    	
    	return false;
    }
?>
