<?php

namespace LEO\Model;

class Database {
    private $dbc;
	
    private $dbName = "ibd2h16abo_LEO";
    private $linkName = "mysqlbi.pb.bib.de";
    private $user = "ibd2h16abo";
    private $pw = "7zyp4Tq6";
	
    
	 public function __construct(){
        $this->dbc = new \PDO('mysql:host=' . $this->linkName . ';dbname=' . $this->dbName, $this->user, $this->pw);
    }

	   // terminates connection
    public function disconnect(){
        $this->dbc = null;
    }
	
    public function linkDB() 
    {
        $db = new \Mysqli($this->linkName, $this->user, $this->pw, $this->dbName);
 
        if ($db->connect_error) {
            die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        } else {
            return $db;
        }
    }
	
	public function queryMR($sql)
	{
		$db = $this->linkDB();
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
		$db = $this->linkDB();
        $res = $db->query($sql);
        if ($db->error) {
            echo "Fehler: " . $db->error;
        }
        
        $db->close();
        
        return count($res) == 1 ? $res->fetch_object() : false;  
	}
	
	public function getLEAs_pdo()
	{
		
		$sql = "SELECT * FROM LEA ORDER BY startdate";
        
        try {
            $stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            echo "Fehler: " . $e;
        }
        
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $res = $stmt->fetchAll();
        
        
        return $res;
	}
	
	
		public function getLEAs(){	
		$sql = "SELECT * FROM LEA ORDER BY startdate";
		return $this->queryMR($sql);
	}
	
	 public function getClasses(){
        $sql = "SELECT * FROM CLASS ORDER BY name";
		return $this->queryMR($sql);
    }
	
	public function writeLEA($postArray)
	{
		$ID = $this->createUUID();
		$title= "Lernaufgabe: ".$postArray['from']." bis ".$postArray['till'];
		$startdate = $postArray['from'];
		$enddate = $postArray['till'];
		
		$db = $this->linkDB();
		
		//check wether a date is picked and if the enddate is beyond the startdate
		if(empty($startdate)==true OR empty($enddate)==true OR $enddate < $startdate)
		{
			echo 'Please pick a valid date!';
		}
		//else write into db
		else
		{
			$sql = "INSERT INTO LEA (ID, title, startdate,enddate)
			VALUES ('$ID', '$title', '$startdate','$enddate');";
			
		
			try{
				$db->query($sql);
				echo 'lea successful created';
			}  
			
			catch(Exception $e)
			{
				$e->getMessage();
				echo 'crashed';
			}
            
            //write values in LEA_HAS_CLASS
            foreach($postArray['selectedClasses'] as $class){
                $sql = "INSERT INTO LEA_HAS_CLASS (LEAID, CLASSID) VALUES ('".$ID."', (SELECT ID FROM CLASS WHERE name = '".$class."'));";
                
            try{
				$db->query($sql);
			}  
			
			catch(Exception $e)
			{
				$e->getMessage();
				echo 'crashed';
			}
            }
            
            
		}
	}
	
	public function getLeaID($UserID){	
		$sql = "SELECT LEAID 
			    FROM LEA_HAS_CLASS 
			    WHERE CLASSID = (SELECT CLASSID 
								 FROM STUDENT 
								 WHERE USERID = '".$UserID."')";
		return $this->querySR($sql);	
	}
		public function getProject($userID){	
		$sql = "SELECT * 
				FROM PROJECT 
				WHERE ID = (SELECT PROJECTID 
							FROM STUDENT_HAS_PROJECT 
							WHERE STUDENTID = '".$userID."')";
		return $this->querySR($sql);
	}
	
	public function getMilestones($LeaID){
		
		$sql = "SELECT * FROM MILESTONE WHERE LEAID = '".$LeaID."'";
		return $this->queryMR($sql);
	}
	
	
	public function getProjectMembers($UserID, $ProjectID){
		
		$sql = "SELECT STUDENTID FROM STUDENT_HAS_PROJECT WHERE PROJECTID='".$ProjectID."'";
        return $this->queryMR($sql);
	}
	
		public function getLogbook($ProjectID)
	{
		$sql = "SELECT entry FROM LOGBOOK_ENTRY WHERE PROJECTID='".$ProjectID."'";
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
	
	public function checkUserGroup($userID){
		
	}
    
	// ---------------------------Team erstellen-------------------------------
	
	// Returns all available team members for a project
	// (students within the same LEA and without a team)
	public function getPossibleTeamMembers($studentID){
	
		$sql = "SELECT DISTINCT USER.username, USER.firstname, USER.lastname
				FROM STUDENT, CLASS, LEA, LEA_HAS_CLASS, USER
				WHERE STUDENT.CLASSID = CLASS.ID
					AND STUDENT.USERID = USER.ID
					AND LEA_HAS_CLASS.LEAID = LEA.ID
					AND LEA_HAS_CLASS.CLASSID = CLASS.ID
					AND LEA.ID IN(SELECT LEA.ID
						FROM STUDENT, CLASS, LEA, LEA_HAS_CLASS
						WHERE STUDENT.CLASSID = CLASS.ID
							AND LEA_HAS_CLASS.LEAID = LEA.ID
							AND LEA_HAS_CLASS.CLASSID = CLASS.ID
							AND STUDENT.USERID = '" . $studentID . "')
					AND STUDENT.USERID NOT IN (SELECT STUDENTID FROM STUDENT_HAS_PROJECT)
					AND STUDENT.USERID <> '". $studentID . "';";
		//TODO check LEA date
        try {
			$stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }

        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
		
		$this->disconnect();
		
		return $res;
	}
	
	public function createProject($teamMembers){
		$ids = array();
		for($i = 0; $i < count($teamMembers); $i++){
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
			$stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
			$this->disconnect();
			return;
        }
		
		$this->addMembersToProject($projectID, $ids);
		
		$this->disconnect();
		
	}
	
	private function addMembersToProject($projectID, $memberIDs){
		for($i = 0; $i < count($memberIDs); $i++){
		
			$sql = "INSERT INTO `STUDENT_HAS_PROJECT`(`STUDENTID`, `PROJECTID`)
					VALUES ('" . $memberIDs[$i]
					. "', '" . $projectID . "')";
			
			try {
				$stmt = $this->dbc->query($sql);
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
			}
		
		}
	}
	
	private function getIDFromUsername($name){
		echo $name;
		$sql = "SELECT ID FROM USER WHERE username = '" . $name . "';";
		
        try {
			$stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
		
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
		
		var_dump($res);
		return $res[0]["ID"];
	}
	
	private function getLEAIDFromStudent($studentID){
		$sql = "SELECT LEA_HAS_CLASS.LEAID
				FROM LEA_HAS_CLASS, CLASS, STUDENT
				WHERE LEA_HAS_CLASS.CLASSID = CLASS.ID
					AND STUDENT.CLASSID = CLASS.ID
					AND STUDENT.USERID = '" . $studentID . "';";
					
		//TODO check LEA date
		
		try {
			$stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
		
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
		
		return $res[0]["LEAID"];
	}
	
	//Returns the ID of the dummy intructor:
	private function getDummyID(){
		$sql = "SELECT ID
				FROM USER
				WHERE USERNAME = 'Dummy'";
		
		try {
			$stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
		
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
		
		return $res[0]["ID"];
		
	}
	
	public function getUserGroup($username){

		// select instructor with $username
		$sql = "SELECT i.isManager FROM INSTRUCTOR i,USER u WHERE  i.USERID = u.ID AND u.username = '" . $username ."'";

		try {
			$stmt = $this->dbc->query($sql);
		} catch (PDOException $e) {
			echo "Fehler: " . $e;
		}

		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$res = $stmt->fetchAll();

		// if username is in instructor
		if($res){
			if($res[0]['isManager'] == 1){
				echo 'Leamanager';
				return 0;
			}else{
				echo 'dozent';
				return 1;
			}
		} else{
			// try to select student with $username
			$sql = "SELECT * FROM STUDENT i,USER u WHERE  i.USERID = u.ID AND u.username = '" . $username ."'";

			try {
				$stmt = $this->dbc->query($sql);
			} catch (PDOException $e) {
				echo "Fehler: " . $e;
			}

			$stmt->setFetchMode(\PDO::FETCH_ASSOC);
			$res = $stmt->fetchAll();

			if($res){
				echo 'Student';
				return 2;
			}else{
				// user not found in instructors or student
				echo 'does not exist';
				return -1;
			}
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