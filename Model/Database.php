<?php

namespace LEO\Model;

abstract class Database
{

    private $DB_NAME = "ibd2h16abo_LEO";
    private $LINK_NAME = "mysqlbi.pb.bib.de";
    private $USER = "ibd2h16abo";
    private $PW = "7zyp4Tq6";
    


    public function __construct()
    {

    }


    public function linkDB_mysqli()
    {
        $db = new \Mysqli($this->LINK_NAME, $this->USER, $this->PW, $this->DB_NAME);

        if ($db->connect_error) {
            die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        } else {
            return $db;
        }
    }

    public function linkDB_PDO()
    {
        $db = new \PDO('mysql:host=' . $this->LINK_NAME . ';dbname=' . $this->DB_NAME, $this->USER, $this->PW);

        return $db;
    }

    public function queryMR($sql)
    {
        $db = $this->linkDB_mysqli();
        $res = $db->query($sql);
        $result = array();
        while ($row = $res->fetch_object()) {
            $result[] = $row;
        }

        $db->close();

        return count($res) > 0 ? $result : false;
    }

    //Query SQL with one single result i.e. ID
    public function querySR($sql)
    {
        $db = $this->linkDB_mysqli();
        $res = $db->query($sql);
        if ($db->error) {
            echo "Fehler: " . $db->error;
        }

        $db->close();

        return count($res) == 1 ? $res->fetch_object() : false;
    }

    // UPDATES GIVEN TABLE
    public function updateTable($table)
    {

        $dbc = $this->linkDB_PDO();

        $columnNames = array_keys($_POST);

        $sql = "UPDATE " . $table . " SET ";

        for ($i = 0; $i < count($columnNames) - 1; $i++) {
            $sql .= $columnNames[$i] . " = '" . $_POST[$columnNames[$i]] . "' , ";
        }

        $sql .= $columnNames[count($columnNames) - 1] . " = '" . $_POST[$columnNames[count($columnNames) - 1]]
            . "' WHERE ID = '" . $_POST['ID'] . "'";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }

    public function selectUserByUsername($username)
    {
        $sql = "SELECT *
				FROM USER
				WHERE username = '" . $username . "'";

        return $this->querySR($sql);
    }

    public function selectUserByID($userID)
    {
        $sql = "SELECT *
				FROM USER
				WHERE ID = '" . $userID . "'";

        return $this->querySR($sql);
    }

    public function createUUID()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}