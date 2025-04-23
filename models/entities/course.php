<?php

	// DTO
	class course {

		private $course_id;
		private $user_id;
		private $name;
		private $full_name;
		private $description;
		private $knowledge_area;
		private $career;
		private $credits;
		private $thematic_content;
		private $semester;
		private $professor;

		// Constructor
		public function __construct($course_id, $user_id, $name, $full_name, $description, $knowledge_area, $career, $credits, $thematic_content, $semester, $professor) {
			$this->course_id = $course_id;
			$this->user_id = $user_id;
			$this->name = $name;
			$this->full_name = $full_name;
			$this->description = $description;
			$this->knowledge_area = $knowledge_area;
			$this->career = $career;
			$this->credits = $credits;
			$this->thematic_content = $thematic_content;
			$this->semester = $semester;
			$this->professor = $professor;
		}

		// getters
		public function getCourseId() { return $this->course_id; }
		public function getUserID() { return $this->user_id; }
		public function getName() { return $this->name; }
		public function getFullName() { return $this->full_name; }
		public function getDescription() { return $this->description; }
		public function getKnowledgeArea() { return $this->knowledge_area; }
		public function getCareer() { return $this->career; }
		public function getCredits() { return $this->credits; }
		public function getThematicContent() { return $this->thematic_content; }
		public function getSemester() { return $this->semester; }
		public function getProfessor() { return $this->professor; }

		// setters
		// ! Disabled course_id setter to prevent overwriting the primary key
		// public function setCourseId($course_id) { $this->course_id = $course_id; }
		public function setUserID($user_id) { $this->user_id = $user_id; }
		public function setName($name) { $this->name = $name; }
		public function setFullName($full_name) { $this->full_name = $full_name; }
		public function setDescription($description) { $this->description = $description; }
		public function setKnowledgeArea($knowledge_area) { $this->knowledge_area = $knowledge_area; }
		public function setCareer($career) { $this->career = $career; }
		public function setCredits($credits) { $this->credits = $credits; }
		public function setThematicContent($thematic_content) { $this->thematic_content = $thematic_content; }
		public function setSemester($semester) { $this->semester = $semester; }
		public function setProfessor($professor) { $this->professor = $professor; }

	}

?>