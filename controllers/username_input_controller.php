<?php

	require_once '../models/services/username_input_service.php';

	class UsernameInputController {

		private $username_input_service;

		public function __construct() {
			$this->username_input_service = new UsernameInputService();
		}

		public function handler() {

			header('Content-Type: application/json');

			$username = $_GET['username'] ?? null;

			if (empty($username)) {

				echo json_encode('Invalid request.');
				return;

			}

			echo json_encode($this->username_input_service->validateUsername($username));

		}

	}

	$controller = new UsernameInputController();
	$controller->handler();

?>