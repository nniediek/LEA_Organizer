<?php

namespace LEO\Model;

class StudentDatabase extends Database
{

    public function __construct()
    {

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

    // ---------------------------create team-------------------------------

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
        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();

        return $res;
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

        if (count($res) == 0) return false;
        else return true;
    }

    // updates a value in a given table
    public function updateSingleValue($table, $column, $value, $id)
    {
        $dbc = $this->linkDB_PDO();
        $sql = "UPDATE " . strtoupper($table)
            . " SET " . $column . " = '" . $value
            . "' WHERE ID = '" . $id . "'";

        try {
            $stmt = $dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }
	
	//Check if there is already a file uploaded for a certain Milestone
	public function checkFile($pid,$mid)
	{
		$sql = "SELECT ID FROM DOCUMENT WHERE PROJECTID ='".$pid."' AND MILESTONEID = '".$mid."'";
		if(!$this->querySR($sql))
			return false;
		else
			return true;
	}
	
	//Upload File for specific Milestone (ProjectID, MilestoneID)
	public function uploadFile($pid, $mid){
		
		//Possibility to limit valid data types
		//$validTypes = ["jpg","txt","pdf"];
		
		$uploaddir = 'uploads/';
		$filename = explode(".",$_FILES['msFile']['name'])[0];
		$type = explode(".",$_FILES['msFile']['name'])[1];
		$id = $this->createUUID();
		$uploadfile = $uploaddir.$filename.'_'.$id.'.'.$type;

		if(move_uploaded_file($_FILES['msFile']['tmp_name'], $uploadfile)) {			
			$sql = "INSERT INTO DOCUMENT (ID, PATH, PROJECTID, MILESTONEID) VALUES ('".$id."','".$uploadfile."','".$pid."','".$mid."')";
			$db = $this->linkDB();
			$db->query($sql);
			header('Location: '. $_SERVER['PHP_SELF'] . '?controller=Student&do=showHome');	
		} 
		else {
			echo "Möglicherweise eine Dateiupload-Attacke!\n";
		}
	}
	
	//Delete uploaded File from DB and from Server
	public function deleteFile($pid, $mid){
		//Delete from Server
		$sql = "SELECT path FROM DOCUMENT WHERE PROJECTID = '".$pid."' AND MILESTONEID = '".$mid."'";
		$filepath = $this->querySR($sql)->path;
		unlink($filepath);
		
		//Delete from DB
		$sql = "DELETE FROM DOCUMENT WHERE PROJECTID = '".$pid."' AND MILESTONEID = '".$mid."'";
		$db = $this->linkDB();
		$db->query($sql);	
		
		//Redirect
		header('Location: '. $_SERVER['PHP_SELF'] . '?controller=Student&do=showHome');	
		die;
		break;
	}

}