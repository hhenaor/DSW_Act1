<?php

	// * service for create, update and delete data from/or accounts

	require_once '../models/repositories/Icrud_student_imp.php';

	class AccountService {

		private $studentRepo;

		public function __construct() {
			$this->studentRepo = new Icrud_student_imp();
		}

		// - create user
		// * needs 4 strings
		// ! returns a bool
		public function createUser($username, $password, $nickname, $email) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
				$nickname = htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8');
				$email = filter_var($email, FILTER_SANITIZE_EMAIL);

				// create student obj
				$newStudent = new Student(
					$username,
					password_hash($password, PASSWORD_DEFAULT),
					$nickname,
					$email
				);

				// insert student into database
				$this->studentRepo->insert($newStudent);

				return true;

			} catch (Exception $e) {
				return "Error creating user (" . $e->getMessage();
			}

		}

		// - validate user password
		// * needs 2 strings
		// ! returns string or bool
		public function checkPassword($username, $pass) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// Get student via username
				$result = $this->studentRepo->queryID($username);

				if ( $result == null ) {
					return "Username not found.";
				} else {
					return password_verify($pass, $result->getPassword()) ? true : "Invalid password.";
				}

			} catch (Exception $e) {
				return "Error validating password (" . $e->getMessage();
			}

		}

		// - get username
		// * needs string
		// ! returns student
		public function setAccountID($username) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// get username from session
				$result = $this->studentRepo->queryID($username);
				if ( $result == null ) {
					return "Username not found.";
				} else {
					$_SESSION['user_id'] = $result->getUsername();
				}

				return true;

			} catch (Exception $e) {
				return "Error getting username (" . $e->getMessage();
			}

		}

		// - set nickname
		// * needs string
		// ! returns string or bool
		public function setNickname($username, $nickname) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
				$nickname = htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8');

				// check if nickname is valid (only alphanumeric, 4-30 chars)
				if ( preg_match('/^[A-Za-z0-9]{4,30}$/', $nickname) ) {

					// get student and set nickname
					$student = $this->studentRepo->queryID($username);
					$student->setName($nickname);
					$this->studentRepo->update($student);

					return true;

				} else {
					return "Invalid nickname.";
				}

			} catch (Exception $e) {
				return "Error setting nickname (" . $e->getMessage();
			}

		}

	}

?>