<?php 
session_start();

 var_dump($_SESSION);
spl_autoload_register(function ($className) {
    if (substr($className, 0, 4) !== 'LEO\\') {
            // not our business
            return;
    }

    $fileName = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, substr($className, 4)).'.php';
	
    if (file_exists($fileName)) {
            include $fileName;
    }
});    
    
?>
<html>
    <head>
        <title>BIBLEO LEA-Organizer</title>
        <meta charset="UTF-8" />
        
		<link href="CSS/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="script/leamanager.js"></script>
    </head>
    <body>
        <div id="wrapper">
	
<?php
    
$controllerName = "";
$doMethodName = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$controllerName = isset($_POST['controller']) && $_POST['controller'] ? $_POST['controller'] : "Login";
		$doMethodName = isset($_POST['do']) && $_POST['do'] ? $_POST['do'] : "showLogin";
	} else {
		$controllerName = isset($_GET['controller']) && $_GET['controller'] ? $_GET['controller'] : "Login";
		$doMethodName = isset($_GET['do']) && $_GET['do'] ? $_GET['do'] : "showLogin";
	}

$controllerClassName = 'LEO\\'.ucfirst($controllerName);

try {
    $controller = new $controllerClassName();
    $controller->$doMethodName();
} catch (Exception $e) {
    echo 'Page not found: '.$controllerClassName.'::'.$doMethodName;
}


function checkPermission($permission){
	$controller = "Login";
	switch($permission){
		
		case 0: $controller = "LeaManager";
		break;
		case 1: $controller = "Instructor";
		break;
		case 2: $controller = "Student";
		break;
		default: $controller = "Login";
	}
	echo $controller;
	return $controller;
}

?>
        </main>
        </div>
    </body>
</html>
