<?php

	session_start();

	echo "Waiting for server response...<br>";

	require_once '../models/services/validation_service.php';
	require_once '../models/repositories/Icrud_student_imp.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		class AuthController {

			private $validation_service;
			private $studentRepo;

			public function __construct() {
				$this->validation_service = new ValidationService();
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
					header("Location: ../login.php");
					$_SESSION['error'] = implode(", ", $errors);
					return;
				}

				// no errors? register user

				else {
					header("Location: ../login.php");
					$_SESSION['success'] = "login now!";
					return;
				}

				// try {

				// 	$newStudent = new student(
				// 		$_POST['username_form'],
				// 		password_hash($_POST['password_form'], PASSWORD_BCRYPT),
				// 		"",
				// 		$_POST['email_form']
				// 	);

				// 	$this->studentRepo->insert($newStudent);

				// 	// add verification code

				// 	// send mail

				// 	$_SESSION['success'] = "User registration successful! Now you can login.";
				// 	header("Location: ../login.php");

				// } catch (Exception $e) {

				// 	$_SESSION['error'] = "Error creating account (" . $e->getMessage();
				// 	header("Location: ../register.php");

				// }

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

				// no errors? register user
				try {

					$newStudent = new student(
						$_POST['username_form'],
						password_hash($_POST['password_form'], PASSWORD_BCRYPT),
						"",
						$_POST['email_form']
					);

					$this->studentRepo->insert($newStudent);

					// add verification code

					// send mail

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

			public function verifyEmail($code) {
				// Verifica código de email
			}

		}

		$controller = new AuthController();
		$action = $_GET['action'] ?? null;

		if ($action === 'login') { $controller->login(); }

		elseif ($action === 'register') { $controller->register(); }

		elseif ($action === 'logout') { $controller->logout(); }

		elseif ($action === 'verifyEmail') {

			$code = $_GET['code'] ?? null;
			if ($code) {
				$controller->verifyEmail($code);
			} else {
				echo json_encode('Invalid request.');
			}

		} else {

			$_SESSION['error'] = "Invalid request.";
			header("Location: ../login.php");

		}

	}  else {

		$_SESSION['error'] = "Invalid request.";
		header("Location: ../login.php");

	}

?>