<?php

namespace LEO;

use LEO\Model\LoginDatabase;

class Login
{
    private $db;
	
    public function __construct()
    {
        $this->db = new LoginDatabase();
    }

    public function showLogin()
    {
    
        echo '<div id="login">
				<img src="img/logo.png" class="logo">';

        // if wrong username/password was wrong show error message
		if (isset($_SESSION["error"])) {
            echo '<h2 style="color: red">Falsche Benutzerdaten </h2>';
        }

        echo '<h2>LEO der LEA-Organizer</h2>
			<form id="login_form" action="" method="POST">
				<fieldset>
					Username
					</br>
					<input type="text" name="username" id="username">
					</br>
					Password 
					</br>
					<input type="password" name="pw" id="pw">
					</br>
					</br>
					<input type="hidden" name="do" value="loginUser">
					<input type="hidden" name="controller" value="Login">
					<input type="submit" value="Login">									
				</fieldset>
			</form>
			<hr>
			<a href="?controller=Login&do=forceLoginLeaManager">FORCE LOGIN LEAManager</a>
			<hr>
		</div>';
    }

    public function loginUser()
    {

        $username = $_POST["username"];
        $pw = $_POST["pw"];

        $ldap_address = "ldap://pb.bib.de";
        $domain = "PB";
        $dn = "DC=pb,DC=bib,DC=de";
        $ldap_port = 389;


        // connect to the ldap
		if ($connect = ldap_connect($ldap_address, $ldap_port)) {
            // connection success
            ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

            // authendification of the user -- user and pw shouldn't be null
            if (@$bind = ldap_bind($connect, $domain . "\\" . $username, $pw) && $pw != null && $username != null) {

                $user = $this->db->selectUserByUsername($username);
                if ($user == null) {
                    echo 'User wurde nicht in LEA DB gefunden!';
                } else {
                    $_SESSION["permission"] = $this->db->getUserGroup($username);
                    $_SESSION["userID"] = $user->ID;
                    $_SESSION["username"] = $user->username;
                    echo $_SESSION["username"];
                    ldap_close($connect);

                    $controller = "";


                    switch ($_SESSION["permission"]) {
                        case 1:
                            $controller = "LeaManager";
                            break;
                        case 2:
                            $controller = "Instructor";
                            break;
                        case 3:
                            $controller = "Student";
                            break;
                    }
                    
					// unset error after successful login
					if (isset($_SESSION["error"])) {
                        unset($_SESSION["error"]);
                    }

					// call showHome after login
                    header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=' . $controller . '&do=showHome');
                    die;

                }
            } else {
                ldap_close($connect);
                $_SESSION["error"] = true;
                header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=Login&do=showLogin');
                die;

            }

        }
    }

	// for development reasons needed
	function forceLoginLeaManager(){
		 $_SESSION["permission"] = 1;
		 $_SESSION["username"] = "Forced LeaManager";
		 header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=LeaManager&do=showHome');
         die;
	}
	
	// destroys session and returns to login screen
    function logoutUser()
    {
        if (isset($_SESSION["username"])) {
            session_destroy();
            $_SESSION = array();
            header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=Login&do=showLogin');
            die;
        }

    }
}
