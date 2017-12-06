<?php
class DBConnect{
	
	public $hostname;
	public $user;
	public $pass;
	
	public function __construct(){
		
		$this->hostname='https://mysqlbi.pb.bib.de/phpMyAdmin/';
		$this->user='ibd2h16abo';
		$this->pass='7zyp4Tq6';
			
	}
	
	 // generate a random id for the db entries
    public function generateID(){
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
	
	public function connect(){
		echo 'connect';
		$title = 'trolo';
		$startdate= '2017-05-08';
		$enddate= '2017-08-09';
		
		try {
				$dbh = new PDO('host=https://mysqlbi.pb.bib.de/phpMyAdmin/'.'mysql:dbname=ibd2h16abo_LEO', $this->user, $this->pass);
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				echo 'Connected to Database<br/>';
				
				//insert into db
				$ID = $this->generateID();
				
				$sqlCode = "INSERT INTO LEA(`ID`, `titel`, `startdate`, `enddate`)
				VALUES('$ID','$title', '$startdate', '$enddate')";
				
				
				
				$dbh->exec($sqlCode);
			   
			   echo 'submition succesful';
			   
			
			
			}
		catch(PDOException $e)
			{
				echo $e->getMessage();
			}
    
   
    
    }
    
    
    

}

?> 