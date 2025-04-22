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
				$isPublic = !isset($_SESSION['user_id']);

				// get navigation elements from service
				$navElements = $this->header_navigation_service->getNavigationState($isPublic);

				return $navElements;

			} catch (Exception $e) {
				return $e;
			}

		}
	}

?>