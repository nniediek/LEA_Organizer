<?php
namespace LEO\Model;
class LeaManagerDatabase extends Database
{
    public function __construct()
    {
    }

    public function getLEAs(){
        $sql = "SELECT * FROM LEA ORDER BY startdate";
        return $this->queryMR($sql);
    }
    
    public function getClasses()
    {
        $sql = "SELECT * FROM CLASS ORDER BY name";
		return $this->queryMR($sql);
    }
    
    public function getMilestones($LeaID){
		
		$sql = "SELECT * FROM MILESTONE WHERE LEAID = '".$LeaID."'";
		return $this->queryMR($sql);
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
		$sql = "SELECT LEAID 
			    FROM LEA_HAS_CLASS 
			    WHERE CLASSID = (SELECT CLASSID 
								 FROM STUDENT 
								 WHERE USERID = '".$UserID."')";
		return $this->querySR($sql);	
	}
    
    public function getLeaByID($LeaID){
        $sql = "SELECT *
                FROM LEA
                WHERE ID = '".$LeaID."'";
        return $this->querySR($sql);
    }
    
    public function getClassesByLeaID($LeaID){
        $sql = "SELECT * 
                FROM CLASS 
                WHERE ID IN (SELECT CLASSID 
                             FROM LEA_HAS_CLASS 
                             WHERE LEAID = '".$LeaID."')";
        
        return $this->queryMR($sql);
    }
	
	//--------------Lea Status--------------
	
	public function getTeamless($LeaID){
		$sql = "SELECT firstname, lastname
				FROM USER
				WHERE (ID NOT IN (SELECT STUDENTID
								  FROM STUDENT_HAS_PROJECT) AND ID IN (SELECT USERID 
																	   FROM STUDENT 
																	   WHERE CLASSID IN (SELECT CLASSID 
																						 FROM LEA_HAS_CLASS 
																						 WHERE LEAID = '".$LeaID."')))";
		return $this->queryMR($sql);																				 																					 
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
  
}