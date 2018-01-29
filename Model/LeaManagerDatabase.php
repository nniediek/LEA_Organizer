<?php
namespace LEO\Model;
class LeaManagerDatabase extends Database
{
    public function __construct()
    {
    }

    public function getLEAs(){
		/*
        $sql = "SELECT * FROM LEA ORDER BY startdate";
        return $this->queryMR($sql);
		*/
		
		try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		
		$sql = "SELECT * FROM  LEA order by startdate desc"  ;
		$stmt = $db->prepare($sql);
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);
     
		$result = array();
        while ($row = $stmt->fetch()) {
           $result[] = $row;
        }
		
		$db = null;
		return count($stmt)> 0 ? $result : false;	
	
	}
	
    public function getClasses()
    {
		/*
        $sql = "SELECT * FROM CLASS ORDER BY name";
		return $this->queryMR($sql);
		*/
		try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		
		$sql = "SELECT * FROM CLASS ORDER BY name";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);
     
		$result = array();
        while ($row = $stmt->fetch()) {
           $result[] = $row;
        }
		
		$db = null;
		return count($stmt)> 0 ? $result : false;	
    }
    
    public function getMilestones($LeaID){
		/*
		$sql = "SELECT * FROM MILESTONE WHERE LEAID = '".$LeaID."'";
		return $this->queryMR($sql);
		*/
		
		try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		
		$sql = "SELECT * FROM MILESTONE WHERE LEAID = :LeaID ";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':LeaID', $LeaID);		
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);    
		$result = array();
        while ($row = $stmt->fetch()) {
           $result[] = $row;
        }
		
		$db = null;
		return count($stmt)> 0 ? $result : false;
		
	}
    
    public function writeMilestone($postArray)
	{
		
		$milestoneID = $this->createUUID();
		
		$description = $postArray['description'];
		$deadline = $postArray['deadline'];
		$Lea = $this->getLeaByID($postArray['LeaID']);
		$title = $Lea->title;
		
		$LEAID = $postArray['LeaID'];
		
		//check if deadline is empty
		if(empty($deadline)==false )
		{
			try {
				$pdo = $this->linkDB_PDO();
			} catch(PDOException $e) {
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
		return $ID;
	}
    
    public function updateLEA($postArray)
	{
		$ID = $postArray['LeaID'];
		$title= "Lernaufgabe: ".$postArray['from']." bis ".$postArray['till'];
		$startdate = $postArray['from'];
		$enddate = $postArray['till'];
		
		$db = $this->linkDB_mysqli();
		
		//check wether a date is picked and if the enddate is beyond the startdate
		if(empty($startdate)==true OR empty($enddate)==true OR $enddate < $startdate)
		{
			echo 'Please pick a valid date!2';
		}
		//else write into db
		else
		{
			$sql = "UPDATE LEA
                    SET Title='".$title."', Startdate='".$startdate."', Enddate='".$enddate."'
                    WHERE ID = '".$ID."';
                    ";
		
			try{
				$db->query($sql);
			}  
			
			catch(Exception $e)
			{
				$e->getMessage();
				echo 'crashed';
			}
            
            //update values in LEA_HAS_CLASS
            
            $sql="DELETE FROM LEA_HAS_CLASS
                  WHERE LEAID = '".$ID."'
            
            ";
            
            try{
				$db->query($sql);
			}  
			
			catch(Exception $e)
			{
				$e->getMessage();
				echo 'crashed';
			}
            
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
		/*
		$sql = "SELECT LEAID 
			    FROM LEA_HAS_CLASS 
			    WHERE CLASSID = (SELECT CLASSID 
								 FROM STUDENT 
								 WHERE USERID = '".$UserID."')";
		return $this->querySR($sql);
		*/

		
		try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		$sql = "SELECT LEAID 
			    FROM LEA_HAS_CLASS 
			    WHERE CLASSID = (SELECT CLASSID 
								 FROM STUDENT 
								 WHERE USERID = :UserID)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':UserID', $UserID);		
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);    
		$result = $stmt->fetch();
		$db = null;
		return count($stmt) == 1 ? $result : false;
	}
    
    public function getLeaByID($LeaID){
        $sql = "SELECT *
                FROM LEA
                WHERE ID = '".$LeaID."'";
        return $this->querySR($sql); 
		
	/*	try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		$sql = "SELECT *
                FROM LEA
                WHERE ID = :LeaID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':LeaID', $LeaID);		
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);   
		$result = $stmt->fetch();
		
		$db = null;
		return count($stmt) == 1 ? $result : false; */
    }
    
    public function getClassesByLeaID($LeaID){
      /*  $sql = "SELECT * 
                FROM CLASS 
                WHERE ID IN (SELECT CLASSID 
                             FROM LEA_HAS_CLASS 
                             WHERE LEAID = '".$LeaID."')";
        
        return $this->queryMR($sql);*/
		
		
		try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		
		$sql = "SELECT * 
                FROM CLASS 
                WHERE ID IN (SELECT CLASSID 
                             FROM LEA_HAS_CLASS 
                             WHERE LEAID = :LeaID)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':LeaID', $LeaID);		
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);    
		$result = array();
        while ($row = $stmt->fetch()) {
           $result[] = $row;
        }
		
		$db = null;
		return count($stmt)> 0 ? $result : false;
		
    }
	
	//--------------Lea Status--------------
	
	public function getTeamless($LeaID){
		/*$sql = "SELECT firstname, lastname
				FROM USER
				WHERE (ID NOT IN (SELECT STUDENTID
								  FROM STUDENT_HAS_PROJECT) AND ID IN (SELECT USERID 
																	   FROM STUDENT 
																	   WHERE CLASSID IN (SELECT CLASSID 
																						 FROM LEA_HAS_CLASS 
																						 WHERE LEAID = '".$LeaID."')))";
		return $this->queryMR($sql);*/

		try {
			$db = $this->linkDB_PDO();
		} catch(PDOException $e) {
			echo "Fehler bei der Verbindung: " . $e->getMessage();
			
		}
		
		$sql = "SELECT firstname, lastname
				FROM USER
				WHERE (ID NOT IN (SELECT STUDENTID
								  FROM STUDENT_HAS_PROJECT) AND ID IN (SELECT USERID 
																	   FROM STUDENT 
																	   WHERE CLASSID IN (SELECT CLASSID 
																						 FROM LEA_HAS_CLASS 
																						 WHERE LEAID = :LeaID)))";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':LeaID', $LeaID);		
		$stmt->execute();
		
		$stmt->setFetchMode(\PDO::FETCH_OBJ);    
		$result = array();
        while ($row = $stmt->fetch()) {
           $result[] = $row;
        }
		
		$db = null;
		return count($stmt)> 0 ? $result : false;
	}
	
	public function getDocsForMilestone($MilestoneID){
		$sql = "SELECT COUNT(ID) as c FROM DOCUMENT WHERE MILESTONEID = '".$MilestoneID."'";
		
		return $this->querySR($sql);
	}

	public function getMilestoneCount($LeaID){
		$sql = "SELECT COUNT(ID) as c FROM MILESTONE WHERE LEAID = '".$LeaID."'";
		
		return $this->querySR($sql);
	}

	public function getDocumentCount($ProjectID){
		$sql = "SELECT COUNT(ID) as c FROM DOCUMENT WHERE PROJECTID = '".$ProjectID."'";
		
		return $this->querySR($sql);
	}

	public function getProjects($LeaID){
		$sql = "SELECT ID 
				FROM PROJECT 
				WHERE LEAID = '".$LeaID."'";
		
		return $this->queryMR($sql);
	}

	public function getProjectMembers($ProjectID){
		$sql = "SELECT firstname, lastname 
				FROM USER 
				WHERE ID IN (SELECT STUDENTID 
							 FROM STUDENT_HAS_PROJECT 
							 WHERE PROJECTID = '".$ProjectID."')";
							 
		return $this->queryMR($sql);
	}
    
    public function uploadCSV()
	{		
			$row = 0;
			if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {

			  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				if($row != 0)
				{	
					$user = $data[1];
					$class = $data[0];
					$group = $this->checkUser($user);
					
					if($this->checkData("USER",$user) == false)
						$this->regUser($data);
					else
						echo "Nutzer ".$user." bereits vorhanden!<br/>";	
					
					if($this->checkData("CLASS",$class) == false)
						$this->regClass($class);
					else
						echo "Klasse ".$class." bereits vorhanden!<br/>";
					
					$this->setGroup($data,$group);
					
				}
				$row++;
			  }
			  fclose($handle);
			}else{
				echo 'Something went wrong! ';
			}
		
	}

	//Check if a Table already contains certain Data (for example a User or a Class)
	public function checkData($table, $data)
	{
		$col = "";
		
		switch($table)
		{
			case "USER": $col = "username";
				break;
			case "CLASS": $col = "name";
				break;
		}
		
		$sql = "SELECT * FROM ".$table." WHERE ".$col."='".$data."'";
		$res = $this->querySR($sql);
		
		if($res != null)
			return true;
		else 
			return false;
	}
	
	//Check which group the user has to be assigned to 
	public function checkUser($data)
	{
		if(preg_match("/^ib/",$data))
			return "STUDENT";
		else if(preg_match("/^doz/",$data))
			return "INSTRUCTOR";
		else
			return "LEAMANAGER";
	}
	
	//Get UserID
	public function getUID($user)
	{
		$sql = "SELECT ID FROM USER WHERE username='".$user."'";
		$res = $this->querySR($sql);
		return $res->ID;
	}
	
	//Get ClassID
	public function getCID($class)
	{
		$sql = "SELECT ID FROM CLASS WHERE name='".substr($class, 0, -2)."'";
		$res = $this->querySR($sql);
		return $res->ID;
	}

	//Register user
	public function regUser($data)
	{	
		$uuid = $this->createUUID();
		$sql = "INSERT INTO USER(ID,email,firstname,lastname,username) VALUES('".$uuid."','".$data[1]."@bi.bib.de','".utf8_encode($data[3])."','".utf8_encode($data[2])."','".$data[1]."')";
		$this->querySR($sql);
		
		echo "Nutzer ".$data[1]." angelegt!<br/>";
	}
	
	//Register class
	public function regClass($data)
	{
		$uuid = $this->createUUID();
		$sql = "INSERT INTO CLASS(ID, name) VALUES('".$uuid."','".$data."')";
		$this->querySR($sql);
		
		echo "Klasse ".$data." angelegt!<br/>";
	}
	
	//Assigns a user to a specific group
	public function setGroup($userdata, $group)
	{
		$uid = $this->getUID($userdata[1]);
		$cid = $this->getCID($userdata[1]);
		$sql = "";
		
		switch($group)
		{
			case "STUDENT": $sql = "INSERT INTO STUDENT (USERID,CLASSID) VALUES('".$uid."','".$cid."')";
				break;
			case "INSTRUCTOR": $sql = "INSERT INTO INSTRUCTOR (USERID) VALUES('".$uid."')";
				break;
			default: echo "Error at setGroup!";
		}
		echo $sql;
		
		$this->querySR($sql);
		
		echo "Nutzer ".$userdata[1]." zu Gruppe ".$group." hinzugefuegt!<br/>";	
		
	}

}