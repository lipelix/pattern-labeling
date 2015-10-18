<?php

namespace App\DomainObject;

class Data {

	public $id;
	public $created_at;
	public $content;

	public function getPointsArray() {
		if (!$this->content) return null;

		$points = array();
		$parts = explode(';', stream_get_contents($this->content));

		foreach($parts as $part) {
			$arrayCoord = explode(" ", $part);
			array_push($points, [(float)$arrayCoord[0], (float)$arrayCoord[1]]);
		}

		return $points;
	}
}