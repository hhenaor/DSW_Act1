<?php

	// DAO
	interface Icrud {

		public function queryID($id);

		public function selectAll();

		public function insert($object);

		public function deleteID($id);

		public function update($object);

		public function total();

	}


?>