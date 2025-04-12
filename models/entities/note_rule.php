<?php

	// DTO
	class NoteRule {

		private $note_rule_id;
		private $course_id;
		private $note_count;
		private $min_value;
		private $max_value;

		// Constructor
		public function __construct($note_rule_id, $course_id, $note_count, $min_value, $max_value) {
			$this->note_rule_id = $note_rule_id;
			$this->course_id = $course_id;
			$this->note_count = $note_count;
			$this->min_value = $min_value;
			$this->max_value = $max_value;
		}

		// getters
		public function getNoteRuleId() { return $this->note_rule_id; }
		public function getCourseId() { return $this->course_id; }
		public function getNoteCount() { return $this->note_count; }
		public function getMinValue() { return $this->min_value; }
		public function getMaxValue() { return $this->max_value; }

		// setters
		// public function setNoteRuleId($note_rule_id) { $this->note_rule_id = $note_rule_id; }
		// ! Disabled note_rule_id setter to prevent overwriting the primary key
		public function setCourseId($course_id) { $this->course_id = $course_id; }
		public function setNoteCount($note_count) { $this->note_count = $note_count; }
		public function setMinValue($min_value) { $this->min_value = $min_value; }
		public function setMaxValue($max_value) { $this->max_value = $max_value; }

	}

?>