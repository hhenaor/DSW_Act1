<?php

	// * controller for account verification

	session_start();

	require_once '../models/services/verification_service.php';
	require_once '../models/services/account_service.php';

	// * only if on process of login
	if ( isset($_SESSION['user_id']) ) {

		class AccountController {

			private $verification_service;
			private $account_service;

			public function __construct() {
				$this->verification_service = new VerificationService();
				$this->account_service = new AccountService();
			}

			// - when trying login
			// * ban = 0, verified = 1, not verified [A-Za-z0-9]{6}
			// ! returns redirection
			public function checkVerificationStatus() {

				try {

					// get user status
					$response = $this->verification_service->getVerificationStatus(
						$_SESSION['user_id']
					);

					if ( $response == '0' ) {
						$_SESSION['error'] = $response;
					} else if ( $response == '1' ) {
						header("Location: ../dashboard.php");
					} else if ( preg_match('/^[A-Za-z0-9]{6}$/', $response) ) {

						// send via email verification code
						$result = $this->verification_service->sendVerificationMail(
							$_SESSION['user_id']
						);

						if ( $result !== true ) {

							$_SESSION['error'] = $result;
							unset($_SESSION['verified']);

						}

						header("Location: ../checkpoint.php");

					} else {
						$_SESSION['error'] = "Invalid account status! Please contact support.";
					}

					if ( isset($_SESSION['error']) ) {
						header("Location: ../login.php");
					}

					return;

				} catch (Exception $e) {
					return $e;
				}

			}

			// - check if code is correct
			// ! retuns redirection
			public function validateInputs() {

				try {

					// check if code is valid
					$response = $this->verification_service->checkVerificationCode(
						$_SESSION['user_id'],
						$_POST['usercode_form']
					);

					if ( $response !== true ) {
						$_SESSION['error'] = $response;
					}

					$_SESSION['verified'] = true;

					// set nickname
					$response = $this->account_service->setNickname(
						$_SESSION['user_id'],
						$_POST['nickname_form']
					);

					if ( $response !== true ) {
						$_SESSION['error'] = $response;
					}

					if ( isset($_SESSION['error']) ) {
						header("Location: ../checkpoint.php");
					}

					header("Location: ../dashboard.php");

					return;

				} catch (Exception $e) {
					return $e;
				}

			}

		}

		// create controller and get action
		$controller = new AccountController();
		$action = $_GET['action'] ?? null;

		if ( $action === 'verification' ) {
			$controller->checkVerificationStatus();
		} else if ( $action === 'verify' ) {
			$controller->validateInputs();
		} else {

			$_SESSION['error'] = "Invalid request.";
			header("Location: ../login.php");

		}

	}  else {

		$_SESSION['error'] = "Invalid login action.";
		header("Location: ../login.php");

	}

?>