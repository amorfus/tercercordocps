<?php

FronController::dispatch($_REQUEST);

class FronController {

	/**
	 * Gestore delle richieste al punto unico di accesso all'applicazione
	 * @param array $request i parametri della richiesta
	 */
	public static function dispatch(&$request) 
	{
		// inizializziamo la sessione
		
		session_start();
		
		if (isset($request["page"])) 
		{
			switch ($request["page"]) 
			{
				/**
				 * Usuario no autenticado
				 */
				case "login":
					
					$controller = new BaseController();
					$controller->handleInput($request, $_SESSION);
					
					break;

				/**
				 * Usuario simple
				 */
				case 'user':
					
					$controller = new UserController();
					$sessione = &$controller->getSessione($request);
					
					if (!isset($sessione)) {
						self::write403();
					}
					
					$controller->handleInput($request, $sessione);
					break;

				/**
				 * Usuario de tecnica
				 */
				case 'equipo':
					
					$controller = new EquipoController();
					$sessione = &$controller->getSessione($request);
					
					if (!isset($sessione)) {
						self::write403();
					}
					$controller->handleInput($request, $sessione);
					break;

				case 'administrator':
					$controller = new AdministratorController();
					$sessione = &$controller->getSessione();
					if (!isset($sessione)) {
						self::write403();
					}
					$controller->handleInput($request, $sessione);
					break;

				default:
					//self::write404();
				break;
			}
		}
	}
	
	public static function write404() {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 404 Not Found');
        $titolo = "File not found!";
        $mensaje = "La èagina que estas buscando no existe";
        include_once('view/error.php');
        exit();
    }

    /**
     * Crea una pagina di errore quando l'utente non ha i privilegi 
     * per accedere alla pagina
     */
    public static function write403() {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 403 Forbidden');
        $titolo = "Acseso denegado";
        $mensaje = "No tienes los derechos a ver esta pagina";
        $login = true;
        include_once('view/error.php');
        exit();
    }
	
}

?>