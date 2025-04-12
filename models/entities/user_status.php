<?php

	//DTO
	class UserStatus {

		private $user_id;
		private $status;

		public function __construct($user_id, $status) {
			$this->user_id = $user_id;
			$this->status = $status;
		}

		// getters
		public function getUserId() { return $this->user_id; }
		public function getStatus() { return $this->status; }

		// setters
		// public function setUserId($user_id) { $this->user_id = $user_id; }
		// ! Disabled user_id setter to prevent overwriting the primary key
		public function setStatus($status) { $this->status = $status; }

	}

?>