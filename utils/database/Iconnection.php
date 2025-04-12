<?php

	interface Iconnection {

		public function disconnect();

		public function query($sql_query);

		public function update($sql_query, $type = "");

	}

?>