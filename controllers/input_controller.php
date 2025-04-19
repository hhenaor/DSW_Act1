<?php

	session_start();

	require_once '../models/services/validation_service.php';

	// * controller focus on inputs on register and login mostly fo AJAX requests

	if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {

		class InputController {

			private $validation_service;

			public function __construct() {
				$this->validation_service = new ValidationService();
			}

			public function handler() {

				header('Content-Type: application/json');

				$value = $_GET['value'] ?? null;
				$action = $_GET['action'] ?? null;
				$login = $_GET['l'] ?? null;

				if ( !empty($login) && !empty($value) ) {

					// validate login
					echo json_encode($this->validation_service->findUsername($value));
					return;

				}

				// Check if the value and action are empty
				if ( (empty($value) || empty($action)) && $action !== 'pass'  ) {

					echo json_encode('Invalid request.' . $action );
					return;

				}

				if ( $action === 'user' ) {

					// validate username
					echo json_encode($this->validation_service->validateUsername($value));

				} else if ( $action === 'email' ) {

					// validate email
					echo json_encode($this->validation_service->validateEmail($value));

				} else if ( $action === 'pass' ) { // password case

					$pass1 = $_GET['pass1'] ?? null;
					$pass2 = $_GET['pass2'] ?? null;

					if (empty($pass1) || empty($pass2)) {

						echo json_encode('Invalid request.');
						return;

					}

					echo json_encode($this->validation_service->matchPasswords($pass1, $pass2));

				} else {

					// unknow action
					echo json_encode('Invalid action.');

				}

			}

		}

		$controller = new InputController();
		$controller->handler();

	} else {

		$_SESSION['error'] = "Invalid request.";
		header("Location: ../login.php");

	}

?>