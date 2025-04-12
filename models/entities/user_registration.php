<?php

	// DTO
	class UserRegistration {

		private $user_id;
		private $course_id;

		// Constructor
		public function __construct($user_id, $course_id) {
			$this->user_id = $user_id;
			$this->course_id = $course_id;
		}

		// getters
		public function getUserId() { return $this->user_id; }
		public function getCourseId() { return $this->course_id; }

		// setters
		// public function setUserId($user_id) { $this->user_id = $user_id; }
		// ! Disabled user_id setter to prevent overwriting the primary key
		// public function setCourseId($course_id) { $this->course_id = $course_id; }
		// ! Disabled course_id setter to prevent overwriting the primary key

	}

?>