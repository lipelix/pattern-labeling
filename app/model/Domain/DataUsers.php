<?php

namespace App\DomainObject;

use Tracy\Debugger;

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

		$result = [];
		foreach($points as $point) {
			array_push($result, [(float)$point[0], (float)$point[1], (int)$point[2]]);
		}

		return $result;
	}

	public function export() {
		$points = json_decode(stream_get_contents($this->marked_data), true);
		$export = "";

		foreach($points as $point) {
			$export .= implode(" ", $point) . PHP_EOL;
		}

		return $export;
	}
}