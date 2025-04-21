<?php

	session_start();

	require_once '../models/services/verification_service.php';

	// * method focus on login process

	// * only if on process of login
	if ( isset($_SESSION['user_id']) === true ) {

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

			// send via email verification code
			public function sendVerificationCode() {

				try {

					return $this->verification_service->sendVerificationMail($_SESSION['user_id']);

				} catch (Exception $e) {

					return $e;

				}

			}

			// check if code is correct
			public function checkVerificationCode() {

				try {

					return $this->verification_service->checkVerificationCode($_SESSION['user_id'], $_POST['usercode_form']);

				} catch (Exception $e) {

					return $e;

				}

			}

			// set nickname
			public function setNickname() {

				try {

					return $this->verification_service->setNickname($_SESSION['user_id'], $_POST['nickname_form']);

				} catch (Exception $e) {

					return $e;

				}

			}

		}

		$accountController = new AccountController();

		if ( !empty($_GET['action']) && $_GET['action'] == "verify" ) {

			try {

			    $statusCode = $accountController->checkVerificationCode();

			    if ($statusCode !== true) {

			        $_SESSION['error'] = $statusCode;
			        header("Location: ../checkpoint.php");
			        return;

			    }

			    $statusNick = $accountController->setNickname();

			    if ($statusNick !== true) {

			        $_SESSION['error'] = $statusNick;
			        header("Location: ../checkpoint.php");
			        return;

			    }

				// header("Location: ../dashboard.php");

				$_SESSION['success'] = "GO TO DASHBOARD.";
				header("Location: ../login.php");

				return;

			} catch (Exception $e) {

			    $_SESSION['error'] = "An error occurred: " . $e->getMessage();
			    header("Location: ../checkpoint.php");
			    return;

			}

		}

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

			$_SESSION['success'] = "GO TO DASHBOARD.";
			header("Location: ../login.php");

		} else if (preg_match('/^[A-Za-z0-9]{6}$/', $status)) {

			try {

				$result = $accountController->sendVerificationCode();

				if (filter_var($result, FILTER_VALIDATE_EMAIL)) {

					$_SESSION['special'] = $result;

				} else {

					// TODO fix uncatched exception
					$_SESSION['error'] = "CATASTROPHIC ERROR: API KEY RELATED.";
					header("Location: ../login.php");
					return;

				}

			} catch (Exception $e) {

				$_SESSION['error'] = "An error occurred: " . $e->getMessage();
				header("Location: ../login.php");

			}

			header("Location: ../checkpoint.php");

		} else {

			$_SESSION['error'] = "Invalid account status! Please contact support.";
			header("Location: ../login.php");

		}

	}  else {

		$_SESSION['error'] = "Invalid login action.";
		header("Location: ../login.php");

	}

?>