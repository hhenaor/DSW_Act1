<?php

	// * service for input validations also via AJAX

	require_once '../models/repositories/Icrud_student_imp.php';

	class ValidationService {

		private $studentRepo;

		public function __construct() {
			$this->studentRepo = new Icrud_student_imp();
		}

		// - validates username format
		// * needs string
		// ! returns string or true
		private function validateUsernameFormat($username) {

			// Check if the username is empty or shorter than 4 char or larger than 15 chars
			if ( strlen($username) < 4 || strlen($username) > 15 ) {
				return "Username is not valid. It must be between 4 and 15 characters.";
			}

			// Check if the username contains only letters, numbers, and underscores
			if ( !preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $username) ) {
				return "Username must start with a letter and can only contain letters (A-z) numbers (0-9) and underscores (_).";
			}

			return true;

		}

		// - checks if username is free
		// * needs string
		// ! returns string
		public function existUsername($username) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// format validation
				$result = $this->validateUsernameFormat($username);
				if ($result !== true) {
					return $result;
				}

				// Get student via username
				$result = $this->studentRepo->queryID($username);
				if ( $result == null ) {
					return "Username is valid!";
				} else {
					return "Username already exists.";
				}

			} catch (Exception $e) {
				return "Error validating username (" . $e->getMessage();
			}

		}

		// - checks if username exists
		// * needs string
		// ! returns string
		public function findUsername($username) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// format validation
				$result = $this->validateUsernameFormat($username);
				if ($result !== true) {
					return $result;
				}

				// Get student via username
				$result = $this->studentRepo->queryID($username);
				if ( $result == null ) {
					return "Username not found.";
				} else {
					return "Username found!";
				}

			} catch (Exception $e) {
				return "Error validating username (" . $e->getMessage();
			}

		}

		// - checks if email exists
		// * needs string
		// ! returns string
		public function existEmail($email) {

			try {

				// sanitize inputs
				$email = filter_var($email, FILTER_SANITIZE_EMAIL);

				// Check if the email is empty or invalid
				if ( empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
					return "Email format is not valid.";
				}

				// Get all students
				$result = $this->studentRepo->selectAll();

				if ( $result != null ) {

					// Search for existing email
					foreach ( $result as $student ) {

						if ( $student->getEmail() === $email ) {
							return "Email already exists.";
						}

					}

				}

				return "Email is valid!";

			} catch (Exception $e) {
				return "Error validating email (" . $e->getMessage();
			}

		}

		// - validate matching passwords
		// * needs 2 strings
		// ! returns string
		public function matchPasswords($pass1, $pass2) {

			// try {

				// Check if the password is empty or shorter than 8 characters
				if ( empty($pass1) || strlen($pass1) < 8 ||  empty($pass2) || strlen($pass2) < 8 ) {
					return "Passwords must be at least 8 characters long.";
				}

				// Check if the passwords match
				if ($pass1 !== $pass2) {
					return "Passwords don't match.";
				}

				return "Passwords match!";

			// } catch (Exception $e) {

			// 	return "Error validating passwords (" . $e->getMessage();

			// }

		}

		// - validates all registration fields and returns array of results
		// * needs 4 strings
		// ! returns array of strings or bool
		public function registerValidation($username, $email, $pass1, $pass2) {

			// try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
				$email = filter_var($email, FILTER_SANITIZE_EMAIL);

				$results = [];

				$results['username'] = $this->existUsername($username);
				$results['email'] = $this->existEmail($email);
				$results['passwords'] = $this->matchPasswords($pass1, $pass2);

				// Remove entries containing "!" from results
				foreach ($results as $key => $value) {

					if (strpos($value, '!') !== false) {
						unset($results[$key]);
					}

				}

				// return true if no error
				if (count($results) === 0) {
					return true;
				}

				return $results;

			// } catch (Exception $e) {

			// 	return "Error validating fields (" . $e->getMessage();

			// }

		}

	}

?>