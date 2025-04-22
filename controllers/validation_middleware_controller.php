<?php

	session_start();

	class ValidationMiddleware {

		// user can access without login
		private static $publicPages = [
			'login.php',
			'register.php',
			'recover.php'
		];

		// user must be logged
		private static $privatePages = [
			'dashboard.php'
		];

		// user must be logged and verified
		private static $verificationPages = [
			'checkpoint.php'
		];

		public static function validateAccess() {

			$currentPage = basename($_SERVER['PHP_SELF']);

			// user not logged in trying to access private/verification pages
			if (
				!isset($_SESSION['user_id']) &&
				(in_array($currentPage, self::$privatePages) ||
				in_array($currentPage, self::$verificationPages))
			) {

				$_SESSION['error'] = "Please login first.";
				header("Location: login.php");
				exit;
			}

			// logged in but not verified trying to access private pages
			if (
				isset($_SESSION['user_id']) &&
				!isset($_SESSION['verified']) &&
				in_array($currentPage, self::$privatePages)
			) {
				header("Location: checkpoint.php");
				exit;
			}

			// logged in and verified trying to access public/verification pages
			if (
				isset($_SESSION['user_id']) &&
				isset($_SESSION['verified']) &&
				(in_array($currentPage, self::$publicPages) ||
				in_array($currentPage, self::$verificationPages))
			) {
				header("Location: dashboard.php");
				exit;
			}

			// logged in but not verified trying to access public pages
			if (
				isset($_SESSION['user_id']) &&
				!isset($_SESSION['verified']) &&
				in_array($currentPage, self::$publicPages)
			) {
				header("Location: checkpoint.php");
				exit;
			}

		}

	}

?>