<?php

namespace LEO\Model;

abstract class Database
{

    private $dbName = "ibd2h16abo_LEO";
    private $linkName = "mysqlbi.pb.bib.de";
    private $user = "ibd2h16abo";
    private $pw = "7zyp4Tq6";


    public function __construct()
    {

    }


    public function linkDB_mysqli()
    {
        $db = new \Mysqli($this->linkName, $this->user, $this->pw, $this->dbName);

        if ($db->connect_error) {
            die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        } else {
            return $db;
        }
    }

    public function linkDB_PDO()
    {

        $db = new \PDO('mysql:host=' . $this->linkName . ';dbname=' . $this->dbName, $this->user, $this->pw);

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

    public function createUUID()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}