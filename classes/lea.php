<?php 
	class LEA{
		
		private $classes; // array with all classes of the lea
		private $title; // string with title of the lea
		private $startDate; // start date of the lea
		private $endDate; // end date of the lea
		private $projects; // array with all projects of the lea
		private $milestones; // array with all milestones of the lea
		
		// constructor of the user
		public __construct($start, $end, $title){
			$this->startDate = $start;
			$this->endDate = $end;
			$this->title = $title;
		}
		
		// TODO
		// sends an email to user that did not completed the milestones
		// $email is an EMail class object
		public function sendEmail($eMail) {
			
		}
		
		// TODO
		// exports the lea into an excel file
		public function exportIntoExcel() {
			
		}
		
		// TODO
		// returns an array with all students that have no team/project
		public function getStudentsWithoutTeam() {
			
		}
		
		// returns an array of all classes of a lea
		public function getClasses() {
			return $this->classes;
		}
		
		// TODO
		// adds an class to this lea
		public function addClass($class) {
			
		}
		
		// TODO
		// removes an given class
		public function removeClass($class) {
			
		}
		
		// returns the title of a lea
		public function getTitle() {
			return $this->title;
		}
		
		// sets the title of a lea
		public function setTitle($title) {
			$this->title = $title;
		}
		
		// returns the start date of a lea
		public function getStartDate() {
			return $this->startDate;
		}
		
		// sets the start date of a lea
		public function setStartDate($date) {
			$this->startDate = $date;
		}
		
		// returns the end date of a lea
		public function getEndDate() {
			return $this->endDate;
		}
		
		// sets the end date of a lea
		public function setEndDate($date) {
			$this->endDate = $date;
		}
		
		// TODO
		// adds a project to the lea
		public function addProject($project) {
			
		}
		
		// TODO
		// removes a project of the lea
		public function removeProject($project) {
			
		}
		
		// returns an array of all projects of a lea
		public function getProjects() {
			return $this->project;
		}
		
		// TODO
		// adds a milestone to the lea
		public function addMilestone($milestone) {
			
		}
		
		// TODO
		// removes a milestone of the lea
		public function removeMilestone($milestone) {
			
		}
		
		// returns an array of all milestones of a lea
		public function getMilestones() {
			return $this->milestones;
		}
		
	}
?>