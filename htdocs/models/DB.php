<?php

include_once "Settings.php";

class DB extends mysqli {
    /**
     * Singleton para gestionar la conexion al db
     */
	
	private static $_istance = null;
    
	private function __construct()
	{
		
	}
	
    public static function istance()
    {
    	if(self::$_istance == null)
    	{
    		self::$_istance = new DB();
    		
    		self::$_istance->connect(Settings::$host, Settings::$user, Settings::$password, Settings::$db);
    		
    		if(self::$_istance->connect_errno != 0)
    		{
    			$idErrore = self::$_istance->connect_errno;
    			$msg = self::$_istance->connect_error; 
    			error_log("Errore nella connessione al server $idErrore : $msg", 0);
    			
    			self::$_istance = null;
    		}

    	}
    	
    	return self::$_istance;
    }
    
    public static function chiudiDB()
    {
    	if(self::$_istance != null)
    	{
    		self::$_istance->close();
    	}
    }
}

?>