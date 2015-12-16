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
    	
    	if(!$toRet->setNome($request['nome'])){ $vd->setErrorMessage('Nome non valido.'); $check = false;}
    	if(!$toRet->setCognome($request['cognome'])){ $vd->setErrorMessage('Cognome non valido.'); $check = false;}
    	
    	if(isset($request['username']))
    		if(!$toRet->setUsername($request['username'])){ $vd->setErrorMessage('Username non valido.'); $check = false;}
    		
    	if(isset($request['first-password']))	
    		if(!$toRet->setPassword($request['first-password'])){ $vd->setErrorMessage('Password non valida.'); $check = false;}
    	 
    	if(isset($request['ruolo']))
    		if(!$toRet->setRuolo($request['ruolo'])){ $vd->setErrorMessage('Ruolo non valido.'); $check = false;}
    	else
    		$toRet->setRuolo(User::Buyer);
    	     
    	if(!$toRet->setEmail($request['email'])){ $vd->setErrorMessage('E-Mail non valida.'); $check = false;}
    	if(!$toRet->setVia($request['via'])){ $vd->setErrorMessage('Via non valida.'); $check = false;}
    	if(!$toRet->setNumeroCivico($request['numero_civico'])){ $vd->setErrorMessage('Civico non valido.'); $check = false;}
    	if(!$toRet->setCitta($request['citta'])){ $vd->setErrorMessage('Citta non valida.'); $check = false;}
    	if(!$toRet->setProvincia($request['provincia'])){ $vd->setErrorMessage('Provincia non valida.'); $check = false;}
    	if(!$toRet->setCap($request['cap'])){ $vd->setErrorMessage('CAP non valido.'); $check = false;}
    	 
    	if($check)
    		return $toRet;
    	else
    		return false;
    }
    
    public static function recoverUserFromRequest($request)
    {
    	$toRet = new User();
    	
    	$toRet->setNome($request['nome']);
    	$toRet->setCognome($request['cognome']);
    	
    	if(isset($request['username']))
    		$toRet->setUsername($request['username']);
    	
    	$toRet->setEmail($request['email']);
    	$toRet->setVia($request['via']);
    	$toRet->setNumeroCivico($request['numero_civico']);
    	$toRet->setCitta($request['citta']);
    	$toRet->setProvincia($request['provincia']);
    	$toRet->setCap($request['cap']);
    	 
    	return $toRet;
    }
    
    public static function storeUser(User $user, ViewDescriptor $vd)
    {
    	$nome = $user->getNome();
    	$cognome = $user->getCognome();
    	$username = $user->getUsername();
    	$password = $user->getPassword();
    	$email = $user->getEmail();
    	$ruolo = User::Buyer;
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
	    				(Nome, Cognome, Username, Password, Email, Ruolo, Via, Civico, Citta, Provincia, Cap) 
	    			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
	    	
	    	$stmt->prepare($qry);
	    	
	    	$stmt->bind_param('sssssisissi',$nome,
	    									$cognome,
	    									$username,
	    									$password,
	    									$email,
	    									$ruolo,
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
    	$nome = $user->getNome();
    	$cognome = $user->getCognome();
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
					Nome = ?,
    				Cognome = ?,
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
    	 
    	$stmt->bind_param('ssssissii', $nome, $cognome, $email, $via, $civico, $citta, $provincia, $cap, $id);
    	 
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
    				Nome,
    				Cognome,
    				Username,
    				Password,
    				Email,
    				Ruolo,
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
    				$res_id, $res_nome, 
    			   	$res_cognome, 
   					$res_username, 
    				$res_password, 
   					$res_email, 
    				$res_ruolo, 
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
    		$toRet->setRuolo($res_ruolo);
    		$toRet->setNome($res_nome);
    		$toRet->setCognome($res_cognome);
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
	    	
		    
		    if($toRet->getRuolo() == User::Seller)
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
    
    public static function changeRuolo(User $user, $ruolo)
    {
    	$stmt = DB::istance()->stmt_init();
    	
    	$qry = "
    			UPDATE User 
    			SET Ruolo=? 
    			WHERE ID=?
    	";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param('ii', $ruolo, $user->getId());
    	
    	return $stmt->execute();
    }
    
    public static function getListaUtenti()
    {
    	$listUser = array();
    	$admin = User::Administrator;
    	
    	$stmt = DB::istance()->stmt_init();
    	 
    	$qry = "SELECT
    				ID,
    				Nome,
    				Cognome,
    				Username,
    				Password,
    				Email,
    				Ruolo,
    				Via,
    				Civico,
    				Citta,
    				Provincia,
    				Cap,
    				Credito
    			FROM User
    			WHERE Ruolo != ?
    			
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
    				$res_id, $res_nome,
    				$res_cognome,
    				$res_username,
    				$res_password,
    				$res_email,
    				$res_ruolo,
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
	    	
	    		$toRet->setRuolo($res_ruolo);
	    		$toRet->setNome($res_nome);
	    		$toRet->setCognome($res_cognome);
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
	    		
	    		if($toRet->getRuolo() == User::Seller)
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
    				Nome,
    				Cognome,
    				Username,
    				Password,
    				Email,
    				Ruolo,
    				Via,
    				Civico,
    				Citta,
    				Provincia,
    				Cap,
    				Credito
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
    				$res_id, $res_nome, 
    			   	$res_cognome, 
   					$res_username, 
    				$res_password, 
   					$res_email, 
    				$res_ruolo, 
    				$res_via, 
    				$res_civico, 
    				$res_citta, 
    				$res_provincia, 
    				$res_cap, 
    				$res_credito
    		);

	    	$stmt->fetch(); 
	    	
    		$toRet = new User();
    		
    		$toRet->setRuolo($res_ruolo);
    		$toRet->setNome($res_nome);
    		$toRet->setCognome($res_cognome);
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
	    	
		    
		    if($toRet->getRuolo() == User::Seller)
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
    
    public static function addCreditFromUser($id, $credito, $vd)
    {
    	if (!filter_var($credito, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d+(,\d{1,2})?$/'))))
    	{
    		$vd->setErrorMessage('Importo non valido.');
    		return false;
    	}
    		
    	
    	$stmt = DB::istance()->stmt_init();
    	
    	$qry = "
    			UPDATE User
    			SET Credito = Credito + ?
    			WHERE ID = ?
    	";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param('ii', $credito, $id);
    	
    	if(!$stmt->execute())
    	{
    		$vd->setErrorMessage('Errore durante il salvataggio dei dati, segnalare il problema ad un amministratore.');
    		return false;
    	}
    	
    	return true;
    }
    
    public static function followNegoziofromId($id_negozio, $id_user)
    {
    	$stmt = DB::istance()->stmt_init();
    	
    	$qry = "
    			SELECT *
    			FROM Follow
    			WHERE 
    				User_ID = ? AND
    				Negozio_ID = ?
    			
    	";
    	
    	$stmt->prepare($qry);
    	
    	$stmt->bind_param('ii', $id_user, $id_negozio);
    	
    	if(!$stmt->execute())
    		return false;
    	
    	$stmt->store_result();
    	
    	if($stmt->num_rows == 0)
    	{
    		$qry = "
    				INSERT INTO Follow
    					(User_ID, Negozio_ID)
    				VALUE
    					(?, ?)
    		";
    		
    		$stmt->prepare($qry);
    		 
    		$stmt->bind_param('ii', $id_user, $id_negozio);
    		 
    		if(!$stmt->execute())
    			return false;
    	}
    	
    	return true;
    }
    
    public static function unfollowNegoziofromId($id_negozio, $id_user)
    {
    	$stmt = DB::istance()->stmt_init();
    	 
    	$qry = "
    			DELETE 
    			FROM Follow
    			WHERE
    				User_ID = ? AND
    				Negozio_ID = ?
    
    	";
    	 
    	$stmt->prepare($qry);
    	 
    	$stmt->bind_param('ii', $id_user, $id_negozio);
    	 
    	if(!$stmt->execute())
    		return false;
    	 
    	return true;
    }
}

?>
