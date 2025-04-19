<?php

	require_once '../models/repositories/Icrud_student_imp.php';

	// * service focus on login/reegister data/input (AJAX) validations

	class ValidationService {

		private $studentRepo;

		public function __construct() {
			$this->studentRepo = new Icrud_student_imp();
		}

		// * AJAX, log and sign

		public function validateUsername($username) {

			// Check if the username is empty or shorter than 4 char or larger than 15 chars
			if ( strlen($username) < 4 || strlen($username) > 15 ) {
				return "Username is not valid. It must be between 4 and 15 characters.";
			}

			// Check if the username contains only letters, numbers, and underscores
			if ( !preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $username) ) {
				return "Username must start with a letter and can only contain letters (A-z) numbers (0-9) and underscores (_).";
			}

			try {

				if ( $this->studentRepo->queryID($username) == "Student not found" ) {
					return "Username is valid!";
				} else {
					return "Username already exists.";
				}

			} catch (Exception $e) {

				return "Error validating username (" . $e->getMessage();

			}

		}

		public function validateEmail($email) {
			// Check if the email is empty or invalid
			if ( empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
				return "Email format is not valid.";
			}

			try {
				// Get all students
				$students = $this->studentRepo->selectAll();

				// Search for existing email
				foreach ( $students as $student ) {
					if ( $student->getEmail() === $email ) {
						return "Email already exists.";
					}
				}

				// extra return if bucle fails
				return "Email is valid!";

			} catch (Exception $e) {
				return "Email is valid!";
			}

		}

		public function matchPasswords($pass1, $pass2) {
			// Check if the passwords are empty or shorter than 8 char or larger than 30 chars
			if (strlen($pass1) < 8 || strlen($pass1) > 30 || strlen($pass2) < 8 || strlen($pass2) > 30) {
				return "Password is not valid. It must be between 8 and 30 characters.";
			}

			// Check if the passwords match
			if ($pass1 !== $pass2) {
				return "Passwords don't match.";
			}

			return "Passwords match!";
		}

		// * log

		public function findUsername($username) {

			// Check if the username is empty or shorter than 4 char or larger than 15 chars
			if ( strlen($username) < 4 || strlen($username) > 15 ) {
				return "Username is not valid. It must be between 4 and 15 characters.";
			}

			// Check if the username contains only letters, numbers, and underscores
			if ( !preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $username) ) {
				return "Username must start with a letter and can only contain letters (A-z) numbers (0-9) and underscores (_).";
			}

			try {

				if ( $this->studentRepo->queryID($username) == "Student not found" ) {
					return "Username not found.";
				} else {
					return "Username found!";
				}

			} catch (Exception $e) {

				return "Error validating username (" . $e->getMessage();

			}

		}

		public function checkPassword($id, $pass) {

			try {

				if ( $this->studentRepo->queryID($id) == "Student not found" ) {
					return "Username not found.";
				} else {
					$student = $this->studentRepo->queryID($id);
					return password_verify($pass, $student->getPassword()) ? "Password is valid!" : "Password is not valid.";
				}

			} catch (Exception $e) {

				return "Error validating password (" . $e->getMessage();

			}

		}

		// * get user id for verification

		public function getUserID($username) {

			try {

				return $this->studentRepo->queryID($username);

			} catch (Exception $e) {

				return "Error getting username (" . $e->getMessage();

			}

		}

	}

?>