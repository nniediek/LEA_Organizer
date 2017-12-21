<?php
class Database
{
    // saves the connection to the db
    private $dbc;
	
	// connection parameters
	private $host = "mysqlbi.pb.bib.de";
	private $db = "ibd2h16abo_LEO";
	private $user = "ibd2h16abo";
	private $pass = "7zyp4Tq6";

    // constructor for db connection
    public function __construct(){
        $this->dbc = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass);
    }

    // terminates connection
    public function disconnect(){
        $this->dbc = null;
    }

    // get all entries from a table
    public function selectAll($table){
        $sql = "SELECT * FROM " . $table;
		
        try {
			$stmt = $this->dbc->query($sql);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
		
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
		
		$this->disconnect();
		
		return $res;
    }

	public function alert($msg) 
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}
	
	public function writeLEA($postArray)
	{
		$ID = $this->generateID();
		$title= "Lernaufgabe: ".$postArray['from']." bis ".$postArray['till'];
		$startdate = $postArray['from'];
		$enddate = $postArray['till'];
		
		//check wether a date is picked and if the enddate is beyond the startdate
		if(empty($startdate)==true OR empty($enddate)==true OR $enddate < $startdate)
		{
			echo 'Please pick a valid date!';
		}
		//else write into db
		else
		{
			$sql = "INSERT INTO LEA (ID, title, startdate,enddate)
			VALUES ('$ID', '$title', '$startdate','$enddate')";
			
			try{
				$this->dbc->exec($sql);
				echo 'new record successful created';
			}
			
			catch(Exception $e)
			{
				$e->getMessage();
				echo 'crashed';
			}
		}
	}
	
	public function displayLEA()
	{
			try
			{
				$sql = $this->dbc->prepare("SELECT title FROM LEA ORDER BY title ASC");
				$sql->execute();
				$result = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $result;
			}
			catch(Exception $e)
			{
				$e->getMessage();
			}
	}
	
	public function getLEAtitles()
	{
		
	}
	
    // generate a random id for the db entries
    public function generateID(){
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
	
	// TO-DO
	public function login($username, $password){
		
	}
}

?>