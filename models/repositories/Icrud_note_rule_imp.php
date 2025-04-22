<?php

	require_once 'Icrud.php';
	require_once __DIR__ . '/../entities/note_rule.php';
	require_once '../utils/database/Iconn_imp.php';

	class Icrud_note_rule_imp implements Icrud {

		public function queryID($id) {

			$sql = "SELECT * FROM note_rules WHERE note_rule_id = $id";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if ( $result === false ) {
				throw new Exception("Query failed → " . $conn->getError());
			}

			if ( $result->num_rows > 0 ) {

				while ( $row = $result->fetch_assoc() ) {

					$note_rule = new NoteRule(

						$row['note_rule_id'],
						$row['course_id'],
						$row['note_count'],
						$row['max_value']

					);

					return $note_rule;

				}

			} else {
				return null;
			}

		}

		public function selectAll() {

			$sql = "SELECT * FROM note_rules";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if( $result === false ) {
				throw new Exception("Query failed → " . $conn->getError());
			}

			$note_rules = array();

			if ( $result->num_rows > 0 ) {

				while ( $row = $result->fetch_assoc() ) {

					$note_rule = new NoteRule(

						$row['note_rule_id'],
						$row['course_id'],
						$row['note_count'],
						$row['max_value']

					);

					array_push($note_rules, $note_rule);

				}

				return $note_rules;

			} else {
				throw new Exception("No note rules found");
			}

		}

		public function insert($object) {

			try {

				$sql = "INSERT INTO note_rules (course_id, note_count, max_value) VALUES (

					" . $object->getCourseId() 	. ",
					" . $object->getNoteCount() . ",
					" . $object->getMaxValue() 	. "

				)";

				$conn = conn_imp::getInstance();
				$conn->connect();

				if ($conn->update($sql) === false) {
					throw new Exception("Insert failed → " . $conn->getError());
				}

				return true;

			} catch (Exception $e) {
				throw new Exception("Error inserting note rule: " . $e->getMessage());
			}

		}

		public function deleteID($id) {

			$sql = "DELETE FROM note_rules WHERE note_rule_id = $id";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function update($object) {

			$sql = "UPDATE note_rules SET
				course_id = '". $object->getCourseId() . "',
				note_count = ". $object->getNoteCount() . ",
				max_value = ". $object->getMaxValue() . "
				WHERE note_rule_id = ". $object->getNoteRuleId() . "
			";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$conn->update($sql);

		}

		public function total() {

			$sql = "SELECT COUNT(*) FROM note_rules";

			$conn = conn_imp::getInstance();
			$conn->connect();

			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				return $row['COUNT(*)'];
			} else {
				throw new Exception("No note rules found");
			}

		}

	}

?>