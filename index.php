<?php
session_start();

spl_autoload_register(function ($className) {
    if (substr($className, 0, 4) !== 'LEO\\') {
        // not our business
        return;
    }

    $fileName = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, substr($className, 4)) . '.php';

    if (file_exists($fileName)) {
        include $fileName;
    }
});

?>
<html>
<head>
    <title>BIBLEO LEA-Organizer</title>
    <meta charset="UTF-8"/>

    <link href="CSS/style.css" rel="stylesheet" type="text/css"/>
    <link href="CSS/style_gui.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="script/leamanager.js"></script>
</head>
<body>
<div id="wrapper">

    <?php

    $controllerName = "";
    $doMethodName = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $controllerName = isset($_POST['controller']) && $_POST['controller'] ? $_POST['controller'] : (isset($_SESSION["permission"]) ? getController($_SESSION["permission"]) : "Login");
        $doMethodName = isset($_POST['do']) && $_POST['do'] ? $_POST['do'] : (isset($_SESSION["permission"]) ? "showHome" : "showLogin");
    } else {
        $controllerName = isset($_GET['controller']) && $_GET['controller'] ? $_GET['controller'] : (isset($_SESSION["permission"]) ? getController($_SESSION["permission"]) : "Login");
        $doMethodName = isset($_GET['do']) && $_GET['do'] ? $_GET['do'] : (isset($_SESSION["permission"]) ? "showHome" : "showLogin");
    }

    $controllerClassName = 'LEO\\' . ucfirst($controllerName);

    echo $controllerName;
    echo $doMethodName;

    try {
        $controller = new $controllerClassName();
        $controller->$doMethodName();
    } catch (Exception $e) {
        echo 'Page not found: ' . $controllerClassName . '::' . $doMethodName;
    }


    function getController($permission)
    {
        $controller = "";
        echo $permission;

        switch ($permission) {

            case 1:
                $controller = "LeaManager";
                break;
            case 2:
                $controller = "Instructor";
                break;
            case 3:
                $controller = "Student";
                break;
            default:
                $controller = "Login";
        }
        return $controller;
    }

    ?>
    </main>
</div>
</body>
</html>
