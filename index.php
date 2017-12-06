<?php
	include 'leamanager.php';
	include 'instructor.php';
	include 'student.php';
	include 'team.php';
	include 'database.php';
	
	$dbconn = new database();
	$leamanager = new LEAManager();
	$instructor = new Instructor();
	$student = new Student();
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
        $do = (isset($_GET['do'])) ? $_GET['do'] : ""; 
    }		
	switch ($do){
        case "leamanager": $leamanager->showHome();
			break;
		case "instructor": $instructor->showHome();
			break;	
		case "student": $student->showHome();
			break;	
		case "team": $team->showHome();
			break;
		case "addLEA": $leamanager->showLeaHinzufuegen();
			break;
		case "readDB": var_dump($dbconn->selectALL('LEA'));
			break;
		case "logout": logout();
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
						<a href="?do=readDB">FORCE LOGIN T</a>
					</fieldset>
				</form>
			</div>';
    }
	
	function logout(){
		
	// sets cookie expiry date in the past to remove them
		foreach ($_COOKIE as $key => $value){
				
			setcookie($key, $value, time() - 3600); 
		}
	//  destroys all of the data associated with the current session

		// session_destroy();
		
		// session_unset(); 
		
	
		showLogin();
	}
?>
			</main>
        </div>
    </body>
</html>
