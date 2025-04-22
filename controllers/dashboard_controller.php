<?php

	// * controller for input validations also via AJAX

	session_start();

	require_once '../models/services/dashboard_service.php';

	// * only if controller is called via AJAX
	if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {

		class DashboardController {

			private $dashboard_service;

			public function __construct() {
				$this->dashboard_service = new DashboardService();
			}

			// - deal with dashboard AJAX request
			// * needs JSON
			// ! returns JSON
			public function AJAXhandler($data) {

				try {

					header('Content-Type: application/json');

					$action = $_GET['action'] ?? null;

					$JSONresponse = [

						'success' => false,
						'title' => "Request error.",
						'message' => "AJAX action couldn't be processed."

					];

					if ( $action === 'createCourse' ) {

						// validate inputs
						$response = $this->dashboard_service->validateDataCourse($data);

						if ($response !== true) {

							$JSONresponse['success'] = false;
							$JSONresponse['title'] = $response;
							$JSONresponse['message'] = "Error on value input.";
							echo json_encode($JSONresponse);
							return;

						}

						// create course
						$response = $this->dashboard_service->createCourse($data, $_SESSION['user_id']);

						if ($response !== true) {

							$JSONresponse['success'] = false;
							$JSONresponse['title'] = $response;
							$JSONresponse['message'] = "Server error, sorry for the inconvenience.";
							echo json_encode($JSONresponse);
							return;

						}

						$JSONresponse['success'] = true;
						$JSONresponse['title'] = "Course created successfully!";
						$JSONresponse['message'] = "Now link your course with an note rule.";

					} else if ( $action === 'createNoteRule' ) {

						// validate inputs
						$response = $this->dashboard_service->validateDataNoteRule($data);

						if ($response !== true) {

							$JSONresponse['success'] = false;
							$JSONresponse['title'] = $response;
							$JSONresponse['message'] = "Error on value input.";
							echo json_encode($JSONresponse);
							return;

						}

						// course exist?
						$response = $this->dashboard_service->existCourse($data);

						if ($response !== true) {

							$JSONresponse['success'] = false;
							$JSONresponse['title'] = "Course doesn't exists.";
							$JSONresponse['message'] = "Put a different course ID.";
							echo json_encode($JSONresponse);
							return;

						}

						// create note rule
						$response = $this->dashboard_service->createNoteRule($data);

						if ($response !== true) {

							$JSONresponse['success'] = false;
							$JSONresponse['title'] = $response;
							$JSONresponse['message'] = "Server error, sorry for the inconvenience.";
							echo json_encode($JSONresponse);
							return;

						}

						// $JSONresponse['success'] = true;
						// $JSONresponse['title'] = "Note Rule created successfully!";
						// $JSONresponse['message'] = "Dashboard will be updated in a few moments";

						// link course
						$response = $this->dashboard_service->createUserRegistration($_SESSION['user_id'], $data['course_id']);

						if ($response !== true) {

							$JSONresponse['success'] = false;
							$JSONresponse['title'] = $response;
							$JSONresponse['message'] = "Server error, sorry for the inconvenience.";
							echo json_encode($JSONresponse);
							return;

						}

						$JSONresponse['success'] = true;
						$JSONresponse['title'] = "User and course created and linked successfully!";
						$JSONresponse['message'] = "Dashboard will be updated in a few moments";

					} else if ( $action === 'accountFetch' ) {

						// get courses

					} else {

						$JSONresponse['title'] = "Invalid request.";
						$JSONresponse['message'] = "AJAX \"" . $action . "\" request doesn't exists.";

					}

					echo json_encode($JSONresponse);
					return;

				} catch (Exception $e) {

					echo json_encode([

						'success' => false,
						'error' => $e->getMessage()

					]);

					return;

				}

			}

		}

		// create controller and call AJAX handler
		$controller = new DashboardController();
		$data = json_decode(file_get_contents('php://input'), true);
		$controller->AJAXhandler($data);

	} else {

		$_SESSION['error'] = "Invalid access.";
		header("Location: ../dashboard.php");

	}

?>