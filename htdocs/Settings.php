<?

class Settings {

	public static $user = "";
    public static $password = "";
    public static $host = "";
    public static $db = "";


    private static $appPath;
    const debug = false;
   
    public static function getApplicationPath() {
    	
        if (!isset(self::$appPath)) {
            
            switch ($_SERVER['HTTP_HOST']) {
                case 'localhost':
                    // configuracion local
                    self::$appPath = 'http://' . $_SERVER['HTTP_HOST'] . '/nombreapplicacion/';
                    break;
                case 'spano.sc.unica.it':
                    // configuracion servidor
                    self::$appPath = 'http://' . $_SERVER['HTTP_HOST'] . '/nombreapplicacion/';
                    break;

                default:
                    self::$appPath = '';
                    break;
            }
        }
        
        return self::$appPath;
    }

}

?>