<?php

namespace LEO;

use LEO\Model\Database;

class Login
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function showLogin()
    {
        var_dump($_POST);

        echo '<div id="login">
				<img src="img/logo.png" class="logo">';

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
			<a href="?controller=LeaManager&do=showHome">FORCE LOGIN LEAManager</a>
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

        echo "Test";

        if ($connect = ldap_connect($ldap_address, $ldap_port)) {
            // Verbindung erfolgreich
            ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

            // Authentifizierung des Benutzers -- pw und username dürfen nicht null sein, da ldap_bind sonst nicht richtig läuft
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
                    if (isset($_SESSION["error"])) {
                        unset($_SESSION["error"]);
                    }

                    header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=' . $controller . '&do=showHome');
                    die;

                }
            } else {
                ldap_close($connect);
                $_SESSION["error"] = true;
                header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=Login&do=showLogin');
                die;

            }

            //echo $_SESSION["group"];
        }
    }

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
