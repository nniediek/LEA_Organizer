<?php
	include 'leamanager.php';
	include 'instructor.php';
	include 'student.php';
	include 'team.php';
	include 'database.php';
	
	$database = new database();
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
		<link href="CSS/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
			
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
		case "addLEA": $leamanager->showCreateLea();
			break;
		case "readDB": var_dump($database->selectALL('LEA'));
			break;
		case "logout": logout();
			break;	
		case "saveLEA" : $leamanager->showLeaHinzufuegen();
			break; 			
		case "leahinzufuegen": $leamanager->showLeaHinzufuegen();
			break;		
		case "createTeam": $student->showManageTeam();
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
						<hr>
						<a href="?do=leamanager">FORCE LOGIN LEAManager</a>
						<hr>
						<a href="?do=instructor">FORCE LOGIN Instructor</a>
						<hr>
						<a href="?do=student">FORCE LOGIN Student</a>
						<hr>
						<a href="?do=team">FORCE LOGIN Team</a>
						<hr>
						<a href="?do=readDB">proto connect to db</a>
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
