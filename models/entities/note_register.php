<?php

	// DTO
	class NoteRegister {

		private $note_id;
		private $note_rule_id;
		private $note_value;
		private $comment;

		public function __construct($note_id, $note_rule_id, $note_value, $comment) {
			$this->note_id = $note_id;
			$this->note_rule_id = $note_rule_id;
			$this->note_value = $note_value;
			$this->comment = $comment;
		}

		// getters
		public function getNoteId() { return $this->note_id; }
		public function getNoteRuleId() { return $this->note_rule_id; }
		public function getNoteValue() { return $this->note_value; }
		public function getComment() { return $this->comment; }

		// setters
		// public function setNoteId($note_id) { $this->note_id = $note_id; }
		// ! Disabled note_id setter to prevent overwriting the primary key
		public function setNoteRuleId($note_rule_id) { $this->note_rule_id = $note_rule_id; }
		public function setNoteValue($note_value) { $this->note_value = $note_value; }
		public function setComment($comment) { $this->comment = $comment; }

	}

?>