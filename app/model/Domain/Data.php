<?php

namespace App\DomainObject;

class Data {

	public $id;
	public $created_at;
	public $content;

	public function getPointsArray() {
		if (!$this->content) return null;

		$result = array();
		$arr = explode(PHP_EOL, stream_get_contents($this->content));
		foreach ($arr as $pointData) {
			array_push($result, explode(';', $pointData));
		}

		return $result;
	}
}