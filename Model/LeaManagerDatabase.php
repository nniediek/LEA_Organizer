<?php

namespace LEO\Model;

class LeaManagerDatabase extends Database
{

    public function __construct()
    {

    }



    //04-01-2018
    //function to get ALL LEA-titles existing in database
    public function getLEAs()
    {
        $dbc = $this->linkDB_PDO();
        try {
            $pdo = $dbc;
        } catch (PDOException $e) {
            echo "Fehler bei der Verbindung: " . $e->getMessage();

        }

        $sql = "SELECT title FROM  LEA order by startdate desc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        $pdo = null;

        return $res;

    }


    /*
        public function getLEAs(){
        $sql = "SELECT * FROM LEA ORDER BY startdate";
        return $this->queryMR($sql);
    }
    */

    public function getClasses()
    {
        $sql = "SELECT * FROM CLASS ORDER BY name";
        return $this->queryMR($sql);
    }

    public function addMilestone($postArray)
    {

        $dbc = $this->linkDB_PDO();
        $milestoneID = $this->createUUID();
        $description = $postArray['description'];
        $deadline = $postArray['deadline'];

        $title = $this->selectLEAtitle($postArray['LEAID']);

        $LEAID = $postArray['LEAID'];

        //check if deadline is empty
        if (empty($deadline) == true) {
            echo 'Please pick a valid date!';
        } else {
            try {
                $pdo = $dbc;
            } catch (PDOException $e) {
                echo "Fehler bei der Verbindung: " . $e->getMessage();

            }
            $sql = "INSERT INTO MILESTONE (ID, description , deadline, LEAID)
			VALUES (:milestoneID, :description , :deadline , :LEAID)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':milestoneID', $milestoneID);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':LEAID', $LEAID);

            $stmt->execute();
            //problem
            $_POST['controller'] = 'LeaManager';
            $_POST['do'] = 'editLEA';

        }

    }

    public function writeLEA($postArray)
    {

        $ID = $this->createUUID();
        $title = "Lernaufgabe: " . $postArray['from'] . " bis " . $postArray['till'];
        $startdate = $postArray['from'];
        $enddate = $postArray['till'];

        $db = $this->linkDB_mysqli();

        //check wether a date is picked and if the enddate is beyond the startdate
        if (empty($startdate) == true OR empty($enddate) == true OR $enddate < $startdate) {
            echo 'Please pick a valid date!';
        } //else write into db
        else {
            $sql = "INSERT INTO LEA (ID, title, startdate,enddate)
			VALUES ('$ID', '$title', '$startdate','$enddate');";


            try {
                $db->query($sql);
                echo 'lea successful created';
            } catch (Exception $e) {
                $e->getMessage();
                echo 'crashed';
            }

            //write values in LEA_HAS_CLASS
            foreach ($postArray['selectedClasses'] as $class) {
                $sql = "INSERT INTO LEA_HAS_CLASS (LEAID, CLASSID) VALUES ('" . $ID . "', (SELECT ID FROM CLASS WHERE name = '" . $class . "'));";

                try {
                    $db->query($sql);
                } catch (Exception $e) {
                    $e->getMessage();
                    echo 'crashed';
                }
            }


        }
    }

    //08-01-2018 test
    //function to get the correct LEA-title by passing an ID
    public function selectLEAtitle($ID)
    {
        $dbc = $this->linkDB_PDO();
        try {
            $pdo = $dbc;
        } catch (PDOException $e) {
            echo "Fehler bei der Verbindung: " . $e->getMessage();

        }


        $sql = "SELECT title FROM LEA WHERE ID = :ID ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ID', $ID);

        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        $pdo = null;

        return $res;

    }

    //03-01-2018 test
    public function selectLEAByTitle($title)
    {
        $dbc = $this->linkDB_PDO();
        var_dump($title);
        try {
            $pdo = $dbc;
        } catch (PDOException $e) {
            echo "Fehler bei der Verbindung: " . $e->getMessage();

        }


        $sql = "SELECT title FROM LEA WHERE title = :title ";
        $stmt = $pdo->prepare($sql);

        if (is_array($title)) {

            $stmt->bindParam(':title', $title[0]['title']);
        } else {
            $stmt->bindParam(':title', $title);
        }
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        //$pdo = null;

        return $res;


    }

    //04-01-2018
    //function to get the description of the Milestone of a specific LEA by passing the dedicated LEA-title
    public function getMilestoneDescription($title)
    {
        $dbc = $this->linkDB_PDO();
        try {
            $pdo = $dbc;
        } catch (PDOException $e) {
            echo "Fehler bei der Verbindung: " . $e->getMessage();

        }
        $sql = "SELECT description FROM MILESTONE WHERE LEAID IN(SELECT ID FROM LEA WHERE title = :title);";
        $stmt = $pdo->prepare($sql);


        //check if the passed title is a String or an array
        if (is_array($title)) {

            $stmt->bindParam(':title', $title[0]['title']);
        } else {
            $stmt->bindParam(':title', $title);
        }


        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res;

    }

    //04-01-2018
    //function to get the LEA-ID by passing a LEA-title
    public function getLEAIDByTitle($title)
    {
        $dbc = $this->linkDB_PDO();
        var_dump($title);
        try {
            $pdo = $dbc;
        } catch (PDOException $e) {
            echo "Fehler bei der Verbindung: " . $e->getMessage();

        }

        $sql = "SELECT ID FROM LEA WHERE title = :title";
        $stmt = $pdo->prepare($sql);

        if (is_array($title)) {

            $stmt->bindParam(':title', $title[0]['title']);
        } else {
            $stmt->bindParam(':title', $title);
        }


        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        $pdo = null;

        return $res;
    }


}