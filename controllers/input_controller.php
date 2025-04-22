<?php

	// * controller for input validations also via AJAX

	session_start();

	require_once '../models/services/validation_service.php';

	// * only if controller is called via AJAX
	if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {

		class InputController {

			private $validation_service;

			public function __construct() {
				$this->validation_service = new ValidationService();
			}

			// - deal with validations for service
			// ! returns JSON
			public function AJAXhandler() {

				try {

					header('Content-Type: application/json');

					$action = $_GET['action'] ?? null;
					$value1 = $_GET['value1'] ?? null;
					$value2 = $_GET['value2'] ?? null;

					if ( $action === 'userRegister' ) {
						echo json_encode($this->validation_service->existUsername($value1));
					} else if ( $action === 'userLogin' ) {
						echo json_encode($this->validation_service->findUsername($value1));
					} else if ( $action === 'email' ) {
						echo json_encode($this->validation_service->existEmail($value1));
					} else if ( $action === 'pass' ) {
						echo json_encode($this->validation_service->matchPasswords($value1, $value2));
					} else {
						echo json_encode('Invalid request.');
					}

				} catch (Exception $e) {
					return $e;
				}

			}

		}

		// create controller and call AJAX handler
		$controller = new InputController();
		$controller->AJAXhandler();

	} else {

		$_SESSION['error'] = "Invalid access.";
		header("Location: ../login.php");

	}

?>