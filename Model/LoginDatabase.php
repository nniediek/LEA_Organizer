<?php

namespace LEO\Model;

class LoginDatabase extends Database
{

    public function __construct()
    {

    }

    public function selectUserByUsername($Username)
    {
        $sql = "SELECT * FROM USER WHERE username = '" . $Username . "'";
        return $this->querySR($sql);
    }

    public function selectUserByID($UserID)
    {
        $sql = "SELECT * FROM USER WHERE ID = '" . $UserID . "'";
        return $this->querySR($sql);
    }


    private function getIDFromUsername($name)
    {
        $dbc = $this->linkDB_PDO();
        echo $name;
        $sql = "SELECT ID FROM USER WHERE username = '" . $name . "';";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res[0]["ID"];
    }


    public function getUserGroup($username)
    {
        $dbc = $this->linkDB_PDO();
        // select instructor with $username
        $sql = "SELECT i.isManager FROM INSTRUCTOR i,USER u WHERE  i.USERID = u.ID AND u.username = '" . $username . "'";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            echo "Fehler: " . $e;
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        // if username is in instructor
        if ($res) {
            if ($res[0]['isManager'] == 1) {
                echo 'Leamanager';
                return 1;
            } else {
                echo 'dozent';
                return 2;
            }
        } else {
            // try to select student with $username
            $sql = "SELECT * FROM STUDENT i,USER u WHERE  i.USERID = u.ID AND u.username = '" . $username . "'";

            try {
                $stmt = $dbc->query($sql);
            } catch (PDOException $e) {
                echo "Fehler: " . $e;
            }

            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $res = $stmt->fetchAll();

            if ($res) {
                echo 'Student';
                return 3;
            } else {
                // user not found in instructors or student
                echo 'does not exist';
                return -1;
            }
        }
    }


}