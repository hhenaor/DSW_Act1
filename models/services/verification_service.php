<?php

	// * method for verification process only

	require_once __DIR__ . '/../../vendor/autoload.php';

	require_once '../models/repositories/Icrud_user_status_imp.php';
	require_once '../models/repositories/Icrud_student_imp.php';

	// use MailSender via composer

	use MailerSend\MailerSend;
	use MailerSend\Helpers\Builder\Recipient;
	use MailerSend\Helpers\Builder\EmailParams;
	use MailerSend\Exceptions\MailerSendException;

	class VerificationService {

		private $userStatusRepo;
		private $studentRepo;

		public function __construct() {

			$this->userStatusRepo = new Icrud_user_status_imp();
			$this->studentRepo = new Icrud_student_imp();
			$this->loadEnvVariables();

		}

		// - create env loader
		// * needs nothing
		// ! returns bool
		public function loadEnvVariables() {

			$envFile = __DIR__ . '/../../.env';

			if (file_exists($envFile)) {

				$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

				foreach ($lines as $line) {

					if (strpos($line, '=') !== false) {

						list($name, $value) = explode('=', $line, 2);
						$name = trim($name);
						$value = trim(str_replace('"', '', $value));
						putenv("$name=$value");

					}

				}

				return true;
			}

			return false;
		}

		// - create verification code
		// * needs string
		// ! returns a bool
		public function createVerification($username) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// generate random code
				$code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);

				// get user
				$username = $this->studentRepo->queryID($username);
				if ( $username == null ) {
					return "User not found.";
				}

				// create user status object
				$userStatus = new UserStatus($username->getUsername(), $code);

				// insert user status into database
				$this->userStatusRepo->insert($userStatus);

				return true;

			} catch (Exception $e) {
				throw new Exception("Error creating verification code: " . $e->getMessage());
			}

		}

		// - create verification code
		// * needs string
		// ! returns string
		public function getVerificationStatus($username) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// get user
				$result = $this->userStatusRepo->queryID($username);
				return $result->getStatus();

			} catch (Exception $e) {
				return "Error getting verification status: " . $e->getMessage();
			}

		}

		// - send verification mail via MailSender
		// * needs string
		// ! returns string
		public function sendVerificationMail($username) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// load env variables
				$apiKey = getenv('MAILSEND_API_KEY');
				$domainName = getenv('MAILERSEND_DOMAIN_NAME');

				// create MailSender object
				$mailersend = new MailerSend(['api_key' => $apiKey]);

				// get student mail
				$student = $this->studentRepo->queryID($username);
				$studentMail = $student->getEmail();
				$studentUsername = $student->getUsername();

				// get student code
				$code = $this->userStatusRepo->queryID($username);
				$code = $code->getStatus();

				$recipients = [
					new Recipient($studentMail, $studentUsername),
				];

				$email = (new EmailParams())
					->setFrom('verification@' . $domainName)
					->setFromName('mpNotes')
					->setRecipients($recipients)
					->setSubject('mpNotes Verification')
					->setHtml('<code>'.$code.'</code> is your verification code.')
					->setText( $code . ' is your verification code.');

				try {

					$mailersend->email->send($email);

					return true;

				} catch (MailerSendException  $e) {
					return "Error sending verification mail: " . $e->getMessage();
				}

			} catch (Exception $e) {
				return "Error sending verification mail: " . $e->getMessage();
			}

		}

		// - check verification code
		// * needs 2 strings
		// ! returns string or bool
		public function checkVerificationCode($username, $usercode) {

			try {

				// sanitize inputs
				$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

				// get student code
				$code = $this->userStatusRepo->queryID($username);
				$code = $code->getStatus();

				// get student
				$username = $this->studentRepo->queryID($username);

				// check if code is valid
				if ($code === $usercode) {

					// update user status to verified 1
					$codeStatus = new UserStatus($username->getUsername(), '1');

					$this->userStatusRepo->update($codeStatus);

					return true;

				} else {
					return "Invalid verification code.";
				}

			} catch (Exception $e) {
				return "Error checking verification code: " . $e->getMessage();
			}

		}

	}

?>