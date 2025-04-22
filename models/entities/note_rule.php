<?php

	// DTO
	class NoteRule {

		private $note_rule_id;
		private $course_id;
		private $note_count;
		private $max_value;

		// Constructor
		public function __construct($course_id, $note_count, $max_value, $note_rule_id = null) {

			$this->note_rule_id = $note_rule_id;
			$this->course_id = $course_id;
			$this->note_count = $note_count;
			$this->max_value = $max_value;

		}

		// getters
		public function getNoteRuleId() { return $this->note_rule_id; }
		public function getCourseId() { return $this->course_id; }
		public function getNoteCount() { return $this->note_count; }
		public function getMaxValue() { return $this->max_value; }

		// setters
		// public function setNoteRuleId($note_rule_id) { $this->note_rule_id = $note_rule_id; }
		// ! Disabled note_rule_id setter to prevent overwriting the primary key
		public function setCourseId($course_id) { $this->course_id = $course_id; }
		public function setNoteCount($note_count) { $this->note_count = $note_count; }
		public function setMaxValue($max_value) { $this->max_value = $max_value; }

	}

?>