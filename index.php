<?php
	include 'leamanager.php';
	include 'dozent.php';
	include 'studi.php';
	include 'team.php';
	
	$leamanager = new Leamanager();
	$dozent = new Dozent();
	$studi = new Studi();
	$team = new Team();

?>

<html>
    <head>
        <title>BIBLEO LEA-Organizer</title>
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
	$do = (isset($_GET['do'])) ? $_GET['do'] : ""; }
    	

	switch ($do){
		case "saveLEA" : $leamanager->showLeaHinzufuegen();
			break; 
        case "leamanager": $leamanager->showHome();
			break;
		case "LEA hinzuf&uuml;gen": $leamanager->showCreateLea(); 
			break;
		case "addLEA": $leamanager->showCreateLea();
			break;
		case "dozent": $dozent->showHome();
			break;	
		case "studi": $studi->showCreateTeam();
			break;	
		case "team": $team->showHome();
			break;
		case "leahinzufuegen": $leamanager->showLeaHinzufuegen();
			break;		
		case "createTeam": $studi->showManageTeam();
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
						<a href="?do=leamanager">FORCE LOGIN LM</a>
						<a href="?do=dozent">FORCE LOGIN D</a>
						<a href="?do=studi">FORCE LOGIN S</a>
						<a href="?do=team">FORCE LOGIN T</a>
						<a href="?do=leahinzufuegen" > test leahinz </a>
					</fieldset>
				</form>
			</div>';
    }
?>
			</main>
        </div>
    </body>
</html>
