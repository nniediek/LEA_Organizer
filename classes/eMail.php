<?php
	
	class EMail{
		
		private $receiver; // Array of Users 
		private $sender; // User
		private $date; // Date
		private $subject; // String
		private $content; // String
		private $documents; // Array of Documents
		
		public __construct($receiver, $sender, $subject, $content, $documents){
			$this->receiver = $receiver;
			$this->sender = $sender;
			$this->date = $date;
			$this->subject = $subject;
			$this->content = $content;
			$this->documents = $documents;
		}
		
		// TO-DO
		public function send(){
			
		}
	}