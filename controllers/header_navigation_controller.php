<?php

	// * controller for header actions

	require_once 'models/services/header_navigation_service.php';


	class HeaderNavigationController {

		private $header_navigation_service;

		public function __construct() {
			$this->header_navigation_service = new HeaderNavigationService();
		}

		public function getHeaderButtons() {

			try {

				// check if user is logged in
				$page = !isset($_SESSION['user_id']) ? 'login' : false;

				// check if user is logged in and verified
				$page = basename($_SERVER['PHP_SELF']) === 'index.php' && isset($_SESSION['user_id']) ? 'dashboard' : $page;

				// get navigation elements from service
				$navElements = $this->header_navigation_service->getNavigationState($page);

				return $navElements;

			} catch (Exception $e) {
				return $e;
			}

		}
	}

?>