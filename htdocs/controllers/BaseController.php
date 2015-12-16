<?

include_once 'view/ViewDescriptor.php';
include_once 'model/User.php';
include_once 'model/UserFactory.php';

/**
 * Controllador que gestiona los usuario no autenticados en el sistema
 * los otros controlladores erencian de este
 */
class BaseController {

	const user = 'user';
	const impersonato = '_imp';

	public function __construct() {}

	/**
	 * Metodo que gestiona las request del usuario, las clases ijas hacen Override
	 */
	public function handleInput(&$request, &$session) {

		if(!isset($_SESSION['carrello'])) $_SESSION['carrello'] = new Carrello();
		
		$ajax = false;
		
		$vd = new ViewDescriptor();

		$vd->setPagina($request['page']);

		$vd->setUrlBase('login');

		$db = DB::istance();
		
		if(!$db) {
			$vd->setErrorMessage("Errore conneccion DB");
			$this->showLoginPage($vd);
		} else {
			
			if ($this->loggedIn()) {
				$user = $_SESSION[self::user];
				$offerteFollowers = OffertaFactory::loadOffertFromFollow($user->getId());
				$this->showHomeUtente($vd);
			} else {
				// usuario non autenticado
				if(isset($request["subpage"])) {
					$res = $this->setViewDescriptorFromSubpage($request, $vd, new User());
						
					if(!$res) {
						switch ($request['subpage']) {
							case 'Registracion':
								$vd->setSottoPagina('Registracion');
								break;
									
							default:
						}
					}
					$this->showLoginPage($vd);
				} else {
					$this->showLoginPage($vd);
				}
			}
			
			/**
			 * Gestion de los comandos de la applicacion		
			 */

			if (isset($request["cmd"])) {
				switch ($request["cmd"]) {
					case 'login':
						$username = isset($request['user']) ? $request['user'] : '';
						$password = isset($request['password']) ? $request['password'] : '';
						$this->login($vd, $username, $password);

						if ($this->loggedIn()) {
							$user = $_SESSION[self::user];
							
							switch ($user->getRoll()){
								case User::User:
									
									$vd->setUrlBase('user');
									break;

								case User::Equipo:

									$vd->setUrlBase('equipo');
									break;

								case User::Administrator:

									$vd->setUrlBase('administrator');
									break;

								default:
							}
						}
						break;

						// logout
					case 'logout':

						$this->logout($vd);
						break;
							
					case 'registracion':

						$new_user = UserFactory::makeUserFromRequest($request, $vd);

						if($new_user && UserFactory::storeUser($new_user, $vd)) {
							$vd->setInfoMessage('La tua registrazione &egrave; andata a buon fine.');
							$vd->setSottoPagina('Home');
						} else {
							$recoveredUser = UserFactory::recoverUserFromRequest($request);
						}
				
						$this->showLoginPage($vd);
						break;

					default : $this->showLoginPage($vd);
				}
			}				
		}
		
		// crido la vista
		require 'mvc/view/master.php';

		// cierro la conexion con el db
		DB::chiudiDB();
	}

	public function setViewDescriptorFromSubpage($request, ViewDescriptor $vd, User $user) {
		return false;
	}

	protected function loggedIn() {
		return isset($_SESSION) && array_key_exists(self::user, $_SESSION);
	}

	protected function showLoginPage($vd) {
		$vd->setTitolo("Tercercordon - login");
		$vd->setMenuFile('view/login/menu.php');
		$vd->setLoginFile('view/login/login.php');
		$vd->setContentFile('view/login/content.php');
	}

	protected function showHomeUser($vd) {
		$vd->setTitolo("Tercercordon - Usuario");
		$vd->setMenuFile('mvc/view/user/menu.php');
		$vd->setLoginFile('mvc/view/user/login.php');
		$vd->setContentFile('mvc/view/user/content.php');
	}

	protected function showHomeEquipo($vd) {
		$vd->setTitolo("Tercercordon - Equipo");
		$vd->setMenuFile('view/equipo/menu.php');
		$vd->setLoginFile('view/equipo/login.php');
		$vd->setContentFile('view/equipo/content.php');
	}

	protected function showHomeAdministrator($vd) {
		$vd->setTitolo("Tercercordon - Super User ");
		$vd->setMenuFile('mvc/view/administrator/menu.php');
		$vd->setLoginFile('mvc/view/administrator/login.php');
		$vd->setContentFile('mvc/view/administrator/content.php');
	}

	protected function showHomeUtente($vd) {
		$user = $_SESSION[self::user];
		switch ($user->getRoll()) {
			case User::User:
				$this->showHomeUser($vd);
				break;

			case User::Equipo:
				$this->showHomeEquipo($vd);
				break;

			case User::Administrator:
				$this->showHomeAdministrator($vd);
				break;
		}
	}

	protected function login($vd, $username, $password) {

		$user = UserFactory::loadUser($username, $password);
		if ($user) {

			$_SESSION[self::user] = $user;
			$this->showHomeUsuario($vd);
		} else {
			$vd->setErrorMessage("Usuario desconosido o dados equivocados");
			$this->showLoginPage($vd);
		}
	}

	protected function logout($vd) {	
		
		$_SESSION = array();
		if (session_id() != '' || isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 2592000, '/');
		}

		session_destroy();

		$vd->setUrlBase('login');

		$this->showLoginPage($vd);
	}

	protected function setImpToken(ViewDescriptor $vd, &$request) 
	{
		if (array_key_exists('_imp', $request)) 
		{
			$vd->setImpToken($request['_imp']);
		}
	}
}

?>
