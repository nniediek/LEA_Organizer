<?php
	
	session_start();
	$_SESSION['id'] = "00001";
	
	include 'classes/user.php';
	include 'classes/leamanager.php';
	include 'classes/instructor.php';
	include 'classes/student.php';
	include 'classes/project.php';
	
	

	$leamanager = new LEAManager('testuser', 'hans', 'meier', 'hans.hans@hans.hans');
	$instructor;
	$student;
	$project;
	
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
	    case "addLEA": $leamanager->showCreateLea();
			break;
		case "saveLEA": $leamanager->saveLEA();
			
		case "instructor": $instructor->showHome();
			break;
			
		case "student": $student->showHome();
			break;
		case "createProject": $student->showManageProject();
			break;	
			
		case "project": $project->showHome();
			break;

		case "readDB": var_dump($database->selectALL('LEA'));
			break;
		case "logout": logout();
			break;
		case "loginUser": login();
			break;					

        default: showLogin();
            break; 
	}
	
	
    function showLogin() {
		
        echo '<div id="login">
				<img src="img/logo.png" class="logo">
				<h2>LEO der LEA-Organizer</h2>
				<form id="login_form" action="" method="POST">
					<fieldset>
						Username
						</br>
						<input type="text" name="username" id="username">
						</br>
						Password 
						</br>
						<input type="password" name="password" id="password">
						</br>
						</br>
						<input type="hidden" name="do" value="loginUser" >
						<input type="submit" value="Login">
						
						</form>
						
						
						<hr>
						<a href="?do=leamanager">FORCE LOGIN LEAManager</a>
						<hr>
						<a href="?do=instructor">FORCE LOGIN Instructor</a>
						<hr>
						<a href="?do=student">FORCE LOGIN Student</a>
						<hr>
						<a href="?do=project">FORCE LOGIN Project</a>
						<hr>
						<a href="?do=readDB">proto connect to db</a>
						
					</fieldset>
				
			</div>';
		}
	
	function logout(){
		
	// sets cookie expiry date in the past to remove them
		
		/*foreach ($_SESSION as $key => $value){
				
			setcookie($key, $value, time() - 3600); 
		}*/
	
	//  destroys all of the data associated with the current session

		session_destroy();
		
		$_SESSION = array();
		
		/*foreach ($_SESSION as $key => $value){
				
			echo $key . ": " . $value;
		} */
		
		showLogin();
	}
	
	/*public function login(){
		
		/*$res = $this->db->selectAllUserData($_POST['username']);
		
		if($res->pw == $_POST['pw'] && $res->username == $_POST['username']){
				//createSession($res->id);
				echo 'success! now logged in as ' . $res->username;
			

		if()			
			
        } 
		
	} */
		
	 function login(){
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if($username == "" || $password == ""  ){
			
			echo '<script> console.log("etwas ist leer")</script>';
			showLogin();
		}else{
			
		
		$ldap_address = "ldap://pb.bib.de";
		$domain = "PB";
		$dn = "DC=pb,DC=bib,DC=de";
		$ldap_port = 389;
		
		
		if ($connect = ldap_connect($ldap_address, $ldap_port)) {
   // Verbindung erfolgreich
			ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

	// Authentifizierung des Benutzers
			if (@$bind = ldap_bind($connect, $domain . "\\" . $username, $password)) {
				echo'Login erfolgreich';
		ldap_close($connect);
      
			}else{
				echo '<script> console.log("loginversuch fehlgeschlagen")</script>';
				showLogin();
				ldap_close($connect);
			}
		}
		}
	 }
?>
			</main>
        </div>
    </body>
</html>
