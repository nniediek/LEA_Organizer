<?php
	
	class Entry{
		
		private $title; // String 
		private $date; // Date
		private $content; // String
		private $creator; // String
		
		public __construct($title, $date, $content, $creator){
			$this->title = $title;
			$this->date = $date;
			$this->content = $content;
			$this->creator = $creator;
		}
		
		public function getTitle(){
			return $this->title;
		}
		
		public function setTitle($title){
			$this->title = $title;
		}
		
		public function getDate(){
			return $this->date;
		}
		
		public function getContent(){
			return $this->content
		}
		
		public function setContent($content){
			$this->content = $content;
		}
		
		public function getCreator(){
			return $this->creator;
		}
	}