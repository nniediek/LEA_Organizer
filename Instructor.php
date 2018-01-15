<?php

namespace LEO;

use LEO\Model\Database;

class Instructor
{
	private $db;
	
	public function __construct() 
    {
        $this->db = new Database();
    }
}

?>