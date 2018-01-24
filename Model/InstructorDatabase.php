<?php

namespace LEO\Model;

class InstructorDatabase extends Database
{

    public function __construct()
    {

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

    public function getLEAs_pdo()
    {
        $dbc = $this->linkDB_PDO();

        $sql = "SELECT * FROM LEA ORDER BY startdate";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            echo "Fehler: " . $e;
        }

        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $res = $stmt->fetchAll();


        return $res;
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

    public function getLeaID($UserID)
    {
        $sql = "SELECT LEAID 
			    FROM LEA_HAS_CLASS 
			    WHERE CLASSID = (SELECT CLASSID 
								 FROM STUDENT 
								 WHERE USERID = '" . $UserID . "')";
        return $this->querySR($sql);
    }

    public function getProject($userID)
    {
        $sql = "SELECT * 
				FROM PROJECT 
				WHERE ID = (SELECT PROJECTID 
							FROM STUDENT_HAS_PROJECT 
							WHERE STUDENTID = '" . $userID . "')";
        return $this->querySR($sql);
    }

    public function getMilestones($LeaID)
    {

        $sql = "SELECT * FROM MILESTONE WHERE LEAID = '" . $LeaID . "'";
        return $this->queryMR($sql);
    }


    public function getProjectMembers($UserID, $ProjectID)
    {

        $sql = "SELECT STUDENTID FROM STUDENT_HAS_PROJECT WHERE PROJECTID='" . $ProjectID . "'";
        return $this->queryMR($sql);
    }

    public function getLogbook($ProjectID)
    {
        $sql = "SELECT entry FROM LOGBOOK_ENTRY WHERE PROJECTID='" . $ProjectID . "'";
        return $this->queryMR($sql);
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

    public function checkUserGroup($userID)
    {

    }

    // ---------------------------Team erstellen-------------------------------

    // Returns all available team members for a project
    // (students within the same LEA and without a team)
    public function getPossibleTeamMembers($studentID)
    {
        $dbc = $this->linkDB_PDO();
        $sql = "SELECT DISTINCT USER.username, USER.firstname, USER.lastname
				FROM STUDENT, CLASS, LEA, LEA_HAS_CLASS, USER
				WHERE STUDENT.CLASSID = CLASS.ID
					AND STUDENT.USERID = USER.ID
					AND LEA_HAS_CLASS.LEAID = LEA.ID
					AND LEA_HAS_CLASS.CLASSID = CLASS.ID
					AND LEA.ID IN(SELECT LEA.ID
					AND LEA.startdate <= CURRENT_DATE
					AND LEA.enddate >= CURRENT_DATE
						FROM STUDENT, CLASS, LEA, LEA_HAS_CLASS
						WHERE STUDENT.CLASSID = CLASS.ID
							AND LEA_HAS_CLASS.LEAID = LEA.ID
							AND LEA_HAS_CLASS.CLASSID = CLASS.ID
							AND STUDENT.USERID = '" . $studentID . "')
					AND STUDENT.USERID NOT IN (SELECT STUDENTID FROM STUDENT_HAS_PROJECT)
					AND STUDENT.USERID <> '" . $studentID . "';";
        //TODO check LEA date
        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        $this->disconnect();

        return $res;
    }

    public function createProject($teamMembers)
    {
        $dbc = $this->linkDB_PDO();
        $ids = array();
        for ($i = 0; $i < count($teamMembers); $i++) {
            array_push($ids, $this->getIDFromUsername($teamMembers[$i]));
        }

        $projectID = $this->createUUID();
        $LEAID = $this->getLEAIDFromStudent($ids[0]);
        $dummyID = $this->getDummyID();


        $sql = "INSERT INTO `PROJECT`(`ID`, `idea_description`, `task`, `title`, `LEAID`, `INSTRUCTORID`)
				VALUES ('" . $projectID
            . "','Bitte Beschreibung hinzufügen','Bitte Kurzbeschreibung hinzufügen','Bitte Titel hinzufügen.','"
            . $LEAID . "','"
            . $dummyID . "')";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            $this->disconnect();
            return;
        }

        $this->addMembersToProject($projectID, $ids);

        $this->disconnect();

    }

    private function addMembersToProject($projectID, $memberIDs)
    {
        $dbc = $this->linkDB_PDO();
        for ($i = 0; $i < count($memberIDs); $i++) {

            $sql = "INSERT INTO `STUDENT_HAS_PROJECT`(`STUDENTID`, `PROJECTID`)
					VALUES ('" . $memberIDs[$i]
                . "', '" . $projectID . "')";

            try {
                $stmt = $dbc->query($sql);
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
            }

        }
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

    private function getLEAIDFromStudent($studentID)
    {
        $dbc = $this->linkDB_PDO();
        $sql = "SELECT LEA_HAS_CLASS.LEAID
				FROM LEA_HAS_CLASS, CLASS, STUDENT
				WHERE LEA_HAS_CLASS.CLASSID = CLASS.ID
					AND STUDENT.CLASSID = CLASS.ID
					AND STUDENT.USERID = '" . $studentID . "';";

        //TODO check LEA date

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res[0]["LEAID"];
    }

    //Returns the ID of the dummy intructor:
    private function getDummyID()
    {
        $dbc = $this->linkDB_PDO();
        $sql = "SELECT ID
				FROM USER
				WHERE USERNAME = 'Dummy'";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res[0]["ID"];

    }

    //Checks, if the given student (username) is already in a current project
    public function isInProject($studentName)
    {
        $dbc = $this->linkDB_PDO();

        $sql = "SELECT STUDENTID
				FROM STUDENT_HAS_PROJECT, PROJECT, LEA
				WHERE STUDENT_HAS_PROJECT.PROJECTID = PROJECT.ID
					AND PROJECT.LEAID = LEA.ID
					AND LEA.startdate <= CURRENT_DATE
					AND LEA.enddate >= CURRENT_DATE
					AND STUDENTID = '" . $this->getIDFromUsername($studentName) . "'";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        $db = null;

        if (count($res) == 0) return false;
        else return true;
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
                $stmt = $this->dbc->query($sql);
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