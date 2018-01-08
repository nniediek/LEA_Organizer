<?php 
	include 'classes/database.php';
	
	class User{
		
		private $username; // sting with username
		private $firstName; // string with first name of user
		private $lastName; // string with lst name of user
		private $eMail; // string with the email of the user
		protected $DB;
		
		// set user variables to the given parameters
		public function __construct($username, $firstName, $lastName, $eMail){
			$this->username = $username;
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->eMail = $eMail;
			$this->DB = new Database();
		}
		
		// returns the username
		public function getUsername() {
			return $this->username;
		}
		
		// returns the first name
		public function getFirstName() {
			return $this->username;
		}
		
		// returns the last name
		public function getLastName() {
			return $this->latName;
		}
		
		// returns the email
		public function getEMail() {
			return $this->eMail;
		}
		
	}
?>