<?php 
	class Project {
		
		
		public function showHome() {
			echo '<p>
                   <h1> TEAM Home </h1>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. 
				 </p>';
		}
		
		public function showSite2() {
			echo '<p>
                    <h1> site 2 </h1>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. 
                  </p>';
		}
		
		public function showSite3() {
			echo '<p>
                    <h1> site 3 </h1>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. 
                  </p>';
		}
		
		public function error() {
			echo 'SOMETHING WENT WRONG';
		}
		
		
		//--------------------------------------------------------------------------------
		
		private $title;	//a String containing the title of the Project
		private $task; //a String containing the task of the Project
		private $description; //a String describing the Project
		
		private $entries; //array containing all Entries of a log book
		private $members; //array containing the Members of the team
		
		
		public function __construct($title, $task, $description){
			$this->title = $title;
			$this->task = $task;
			$this->description = $description;
		}
		
		public function getDocuments(){
			//TODO
		}
		
		/*
		$doc: Document
		$ms: Milestone
		*/
		public function addDocument($doc, $ms){
			//TODO
		}
		
		/*
		$doc: Document
		*/
		public function removeDocument($doc){
			//TODO
		}
		
		/*
		$student: Student
		*/
		public function addMember($student){
			//TODO
		}
		
		/*
		$name: String
		*/
		public function removeMember($name){
			//TODO			
		}
		
		/*
		Return type: Document array
		*/
		public function getStatus(){
			//TODO
		}
		
		/*
		Return type: String
		*/
		public function getTitle(){
			return $this->title;
		}
		
		/*
		$title: String
		*/
		public function setTitle($title){
			$this->title = $title;
		}
		
		/*
		Return type: String
		*/
		public function getTask(){
			return $this->task;
		}
		
		/*
		$task: String
		*/
		public function setTask($task){
			$this->task = $task;
		}
		
		/*
		Return type: String
		*/
		public function getDescription(){
			return $this->description;
		}
		
		/*
		$description: String
		*/
		public function setDescription($description){
			$this->description = $description;
		}
		
		/*
		Return type: Student array
		*/
		public function getMembers(){
			//TODO
		}
		
		/*
		Return type: Entry array
		*/
		public function getEntries(){
			//TODO
		}
		
		/*
		$entry: Entry
		*/
		public function addEntry($entry){
			//TODO
		}
		
		/*
		$entry: Entry
		*/
		public function removeEntry($entry){
			//TODO
		}
		
		
	}
?>













