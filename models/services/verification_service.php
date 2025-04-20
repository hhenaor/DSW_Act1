<?php

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
		}

		// create verification code
		public function createVerification($user) {

			// generate random code
			$code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);

			// create user status object
			$userStatus = new UserStatus($user->getUsername(), $code);

			// insert user status into database
			try {

				$this->userStatusRepo->insert($userStatus);

			} catch (Exception $e) {

				throw new Exception("Error creating verification code: " . $e->getMessage());

			}

		}

		// get verification status
		public function getVerificationStatus($user) {

			try {

				$result = $this->userStatusRepo->queryID($user);
				return $result->getStatus();

			} catch (Exception $e) {

				throw new Exception("Error getting verification status: " . $e->getMessage());

			}

		}

		// send verification mail
		public function sendVerificationMail($user) {

			try {

				$mailersend = new MailerSend(['api_key' => ""]);

				// get student mail
				$student = $this->studentRepo->queryID($user);
				$studentMail = $student->getEmail();
				$studentUsername = $student->getUsername();

				// get student code
				$code = $this->userStatusRepo->queryID($user);
				$code = $code->getStatus();

				$recipients = [
					new Recipient($studentMail, $studentUsername),
				];

				$email = (new EmailParams())
					->setFrom('verification@test-69oxl5eev1kl785k.mlsender.net')
					->setFromName('mpNotes')
					->setRecipients($recipients)
					->setSubject('mpNotes Verification')
					->setHtml('<code>'.$code.'</code> is your verification code.')
					->setText( $code . ' is your verification code.');

				try {

					$mailersend->email->send($email);

				} catch (MailerSendException  $e) {

					throw new Exception("Error sending verification mail: " . $e->getMessage());

				}

				return $studentMail;

			} catch (Exception $e) {

				throw new Exception("Error sending verification mail: " . $e->getMessage());

			}

		}


		// check verification code


	}

?>