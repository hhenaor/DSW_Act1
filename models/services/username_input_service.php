<?php

	require_once '../models/repositories/Icrud_student_imp.php';

	class UsernameInputService {

		private $studentRepo;

		public function __construct() {
			$this->studentRepo = new Icrud_student_imp();
		}

		public function validateUsername($username) {

			// Check if the username is empty or shorter than 4 char or larger than 15 chars
			if (strlen($username) < 4 || strlen($username) > 15) {
				return "Username is not valid. It must be between 4 and 15 characters.";
			}

			// Check if the username contains only letters, numbers, and underscores
			if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
				return "Username can only contain letters(A-z), numbers(0-9), and underscores(_).";
			}

			try {
				$this->studentRepo->queryID($username);
				return "Username already exists.";
			} catch (Exception $e) {
				return "Username is valid!";
			}

		}

	}

?>