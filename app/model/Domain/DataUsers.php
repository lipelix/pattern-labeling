<?php

namespace App\DomainObject;

class DataUsers {

	public $id;
	public $data_id;
	public $user_id;
	public $created_at;
	public $polygon;
	public $marked_data;

	public function getPointsArray() {
		if (!$this->marked_data) return null;
		$points = json_decode(stream_get_contents($this->marked_data), true);
		return $points;
	}

	public function export() {
		if (!$this->marked_data) return null;
		$points = stream_get_contents($this->marked_data);
		return $points;
	}
}