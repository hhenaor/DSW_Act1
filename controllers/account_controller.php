<?php

	session_start();

	require_once '../models/services/verification_service.php';

	// * method focus on login process

	// * only if on process of login
	if (isset($_SESSION['user_id']) === true) {

		class AccountController {

			private $verification_service;

			public function __construct() {
				$this->verification_service = new VerificationService();
			}

			// check if user is ban 0, verified 1, not verified [A-Za-z0-9]{6}, else error
			public function getUserStatus() {

				try {

					return $this->verification_service->getVerificationStatus($_SESSION['user_id']);

				} catch (Exception $e) {

					return $e;

				}

			}

			public function sendVerificationCode() {

				try {

					return $this->verification_service->sendVerificationMail($_SESSION['user_id']);

				} catch (Exception $e) {

					return $e;

				}

			}

		}

		$accountController = new AccountController();

		try {

			$status = $accountController->getUserStatus();

		} catch (Exception $e) {

			$_SESSION['error'] = "An error occurred: " . $e->getMessage();
			header("Location: ../login.php");
		}

		if ($status === '0') {

			$_SESSION['error'] = "Your account has been banned.";
			header("Location: ../login.php");

		} else if ($status === '1') {

			// header("Location: ../dashboard.php");

			$_SESSION['success'] = "Log now.";
			header("Location: ../login.php");

		} else if (preg_match('/^[A-Za-z0-9]{6}$/', $status)) {

			try {

				// if special is not mail return error to login
				$_SESSION['special'] = $accountController->sendVerificationCode();

			} catch (Exception $e) {

				$_SESSION['error'] = "An error occurred: " . $e->getMessage();
				header("Location: ../login.php");

			}

			header("Location: ../checkpoint.php");

		} else {

			$_SESSION['error'] = "Invalid account status! Please contact support.";
			header("Location: ../login.php");

		}

	}   else {

		$_SESSION['error'] = "Invalid login action.";
		header("Location: ../login.php");

	}

?>