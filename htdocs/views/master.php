<?
include_once 'ViewDescriptor.php';
include_once basename(__DIR__) . '/../Settings.php';

if(Settings::debug)
	echo ' basename: '.basename(__DIR__);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?= $vd->getTitolo() ?></title>
		<base href="<?= Settings::getApplicationPath() ?>" />
		<link href="css/main.css" rel="stylesheet" type="text/css" media="screen" />
		<script type="text/javascript" src="js/lib/jquery2.js"></script>
		<script type="text/javascript" src="js/search.js"></script>
	</head>
	
	<body>
	
	</body>
</html>