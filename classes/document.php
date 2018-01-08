<?php
	
	class Document{
		
		private $name; // String
		private $path; // String
		
		public __construct($name, $path){
			$this->name = $name;
			$this->path = $path;
		}
		
		public function getName(){
			return $this->name;
		}
		
		public function setName($name){
			$this->name = $name;
		}
		
		public function getPath(){
			return $this->path;
		}
		
		public function setPath($path){
			$this->path = $path;
		}
	}