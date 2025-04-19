<?php

	require_once '../models/repositories/Icrud_user_status_imp.php';

	class VerificationService {
		private $userStatusRepo;

		public function __construct() {
			$this->userStatusRepo = new Icrud_user_status_imp();
		}

		// create verification code
		public function createVerification($user) {

			// generate random code
			$code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);

			// create user status object
			$userStatus = new UserStatus($user->getUsername(), $code);

			// insert user status into database
			try {

				$this->userStatusRepo->insert($userStatus);

			} catch (Exception $e) {

				throw new Exception("Error creating verification code: " . $e->getMessage());

			}

		}

		// get verification status
		public function getVerificationStatus($user) {

			try {

				$result = $this->userStatusRepo->queryID($user);
				return $result->getStatus();

			} catch (Exception $e) {

				throw new Exception("Error getting verification status: " . $e->getMessage());

			}

		}

		// verify user
		public function verifyUser($user) {

		}


		// check verification code

		// send verification code


	}

?>