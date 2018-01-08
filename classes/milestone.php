<?php

	class Milestone{
		
		private $description; // String
		private $deadline; // Date
		
		public __construct($description, $deadline){
			$this->description = $description;
			$this->deadline = $deadline;
		}
		
		public function getDescription(){
			return $this->description;
		}
		
		public function setDescription($description){
			$this->description = $description;
		}
		
		public function getDeadline(){
			return $this->deadline;
		}
		
		public function setDeadline($deadline){
			$this->deadline = $deadline;
		}
	}