<?php 
    include 'home.php';
   
    $session = "S";
	$home = new Home($session);
   
?>

<html>
    <head>
        <title>Login BIBLEO</title>
        <meta charset="UTF-8" />
        <link href="CSS/style_BIBLEO.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
			<main>
<?php
	$do = "";
    if (isset($_POST["submit"]) || $_SERVER["REQUEST_METHOD"] == "POST") {
        $do = (isset($_POST['do'])) ? $_POST['do'] : "";
    }
    else {
        $do = (isset($_GET['do'])) ? $_GET['do'] : "";
    }		

	switch ($do){
        case "login": $home->start($session);
			break;	
        default: showLogin();
            break; 
	}			
    function showLogin() {
		
        echo '<div id="login">
				<img src="img/logo.png" class="logo">
				<h2>LEO der LEA-Organizer</h2>
				<form id="login_form">
					<fieldset>
						Username
						</br>
						<input type="text" name="username" id="username">
						</br>
						Password 
						</br>
						<input type="text" name="password" id="password">
						</br>
						</br>
						<input type="submit" value="Login">
						<a href="?do=login">FORCE LOGIN</a>
					</fieldset>
				</form>
			</div>';
    }
?>
			</main>
        </div>
    </body>
</html>
