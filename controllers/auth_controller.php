<?php

	session_start();

	require_once '../models/services/validation_service.php';
	require_once '../models/services/verification_service.php';

	// * method focus on register and login

	// * only if controller is called on log/sign submit
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

		class AuthController {

			private $validation_service;
			private $verification_service;
			private $studentRepo;

			public function __construct() {
				$this->validation_service = new ValidationService();
				$this->verification_service = new VerificationService();
				$this->studentRepo = new Icrud_student_imp();
			}

			public function login() {

				// array of errors
				$errors = [];

				// validate username
				$response = $this->validation_service->findUsername(isset($_POST['username_form']) ? trim($_POST['username_form']) : '');
				if ($response !== 'Username found!') {
					array_push($errors, $response);
				} else {
					// validate passwords
					$response = $this->validation_service->checkPassword(isset($_POST['username_form']) ? trim($_POST['username_form']) : '', isset($_POST['password_form']) ? $_POST['password_form'] : '');
					if ($response !== 'Password is valid!') {
						array_push($errors, $response);
					}
				}

				if (count($errors) > 0) {
					// If there are errors, redirect to the login page
					$_SESSION['error'] = implode(", ", $errors);
					header("Location: ../login.php");
					return;
				}

				// no errors? login user
				else {

					$userID = $this->validation_service->getUserID($_POST['username_form']);
					$_SESSION['user_id'] = $userID instanceof student ? $userID->getUsername() : null;
					header("Location: account_controller.php");
					return;

				}

			}

			public function register() {

				// array of errors
				$errors = [];

				// validate username
				$response = $this->validation_service->validateUsername(isset($_POST['username_form']) ? trim($_POST['username_form']) : '');
				if ($response !== 'Username is valid!') {
					array_push($errors, $response);
				}

				// validate email
				$response = $this->validation_service->validateEmail(isset($_POST['email_form']) ? trim($_POST['email_form']) : '');
				if ($response !== 'Email is valid!') {
					array_push($errors, $response);
				}

				// validate passwords
				$response = $this->validation_service->matchPasswords(isset($_POST['password_form']) ? $_POST['password_form'] : '', isset($_POST['password_check_form']) ? $_POST['password_check_form'] : '');
				if ($response !== 'Passwords match!') {
					array_push($errors, $response);
				}

				if (count($errors) > 0) {
					// If there are errors, redirect to the registration page
					header("Location: ../register.php");
					$_SESSION['error'] = implode(", ", $errors);
					return;
				}

				// no errors? try register user
				try {

					// create student
					$newStudent = new student(
						$_POST['username_form'],
						password_hash($_POST['password_form'], PASSWORD_BCRYPT),
						"", // set on verification
						$_POST['email_form']
					);

					$this->studentRepo->insert($newStudent);

					// add verification code
					$this->verification_service->createVerification($newStudent);

					// TODO send mail

					$_SESSION['success'] = "User registration successful! Now you can login.";
					header("Location: ../login.php");

				} catch (Exception $e) {

					$_SESSION['error'] = "Error creating account (" . $e->getMessage();
					header("Location: ../register.php");

				}

			}

			public function logout() {
				// Maneja logout
			}

		}

		$controller = new AuthController();
		$action = $_GET['action'] ?? null;

		if ($action === 'login') { $controller->login(); }

		elseif ($action === 'register') { $controller->register(); }

		elseif ($action === 'logout') { $controller->logout(); }

		else {

			$_SESSION['error'] = "Invalid request.";
			header("Location: ../login.php");

		}

	}  else {

		$_SESSION['error'] = "Invalid request.";
		header("Location: ../login.php");

	}

?>