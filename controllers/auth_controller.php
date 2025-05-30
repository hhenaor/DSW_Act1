<?php

	// * method for register and login

	session_start();

	require_once '../models/services/validation_service.php';
	require_once '../models/services/account_service.php';
	require_once '../models/services/verification_service.php';

	// * only if controller is called on login/sign up submit or if header navigation is clicked
	if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_SERVER['HTTP_X_NAVIGATION']) && $_SERVER['HTTP_X_NAVIGATION'] === 'header-nav')) {

		class AuthController {

			private $validation_service;
			private $account_service;
			private $verification_service;

			public function __construct() {

				$this->validation_service = new ValidationService();
				$this->account_service = new AccountService();
				$this->verification_service = new VerificationService();

			}

			// - when trying to register
			// ! returns redirection
			public function register() {

				try {

					// validate inputs
					$response = $this->validation_service->registerValidation(

						$_POST['username_form'],
						$_POST['email_form'],
						$_POST['password_form'],
						$_POST['password_check_form']

					);

					if ($response !== true) {

						$_SESSION['error'] = $response;
						header("Location: ../register.php");
						return;

					}

					// create user
					$response = $this->account_service->createUser(

						$_POST['username_form'],
						$_POST['password_form'],
						"", // - to be set on verification
						$_POST['email_form']

					);

					if ($response !== true) {

						$_SESSION['error'] = $response;
						header("Location: ../register.php");
						return;

					}

					// create verification
					$response = $this->verification_service->createVerification(
						$_POST['username_form']
					);

					if ($response !== true) {

						$_SESSION['error'] = $response;
						header("Location: ../register.php");
						return;

					}

					$_SESSION['success'] = "User registration successful! Now you can login.";
					header("Location: ../login.php");
					return;

				} catch (Exception $e) {
					return $e;
				}


			}

			// - when trying login
			// ! returns redirection
			public function login() {

				try {

					// validate username
					$response = $this->validation_service->findUsername(
						$_POST['username_form']
					);

					if ( $response !== "Username found!" ) {
						$_SESSION['error'] = $response;
					}

					// validate password
					$response = $this->account_service->checkPassword(
						$_POST['username_form'],
						$_POST['password_form']
					);

					if ( $response !== true ) {
						$_SESSION['error'] = $response;
					}

					// Set user session
					$_SESSION['user_id'] = $_POST['username_form'];

					// get username
					$userID = $this->account_service->setAccountID(
						$_POST['username_form']
					);

					if ( $userID == null ) {
						$_SESSION['error'] = "User not found.";
					}

					if ( isset($_SESSION['error']) ) {
						header("Location: ../login.php");
						return;
					}

					header("Location: account_controller.php?action=verification");
					return;

				} catch (Exception $e) {
					return $e;
				}

			}

			// - when trying to logout
			// ! returns redirection
			public function logout() {

				// destroy session
				session_destroy();

				// redirect to login page
				$_SESSION['success'] = "Logout successful!";
				header("Location: ../login.php");
				return;

			}

		}

		// create controller and get action
		$controller = new AuthController();
		$action = $_GET['action'] ?? null;

		if ( $action === 'register' ) {
			$controller->register();
		} else if ( $action === 'login' ) {
			$controller->login();
		} else if ( $action === 'logout' ) {
			$controller->logout();
		} else {

			$_SESSION['error'] = "Invalid request.";
			header("Location: ../login.php");

		}

	}  else {

		$_SESSION['error'] = "Invalid request.";
		header("Location: ../login.php");

	}

?>