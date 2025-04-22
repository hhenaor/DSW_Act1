<?php

	// * service for header actions

	class HeaderNavigationService {

		// - for header actions
		// * needs bool
		// ! returns array
		public function getNavigationState($status) {

			if ( $status  ) {

				return [

					'<a href="login.php" class="btn-sec">Login</a>',
					'<a href="register.php" class="btn-sec">Register</a>'

				];

			} else {

				return [
					'<a href="Controllers/auth_controller.php?action=logout" class="btn-sec" data-nav="auth">Logout</a>',
				];

			}

		}

	}

?>