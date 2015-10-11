<?php

namespace App\Service;

use Tracy\Debugger,
	App\DomainObject as DObject;

class DataService {

	/** @var \Nette\Database\Context */
	public $db;

	/** @var \Nette\Security\Passwords */
	public $passwords;

	public function __construct(\Nette\Database\Context $context, \Nette\Security\Passwords $passwords) {
		$this->db = $context;
		$this->passwords = $passwords;
	}

	public function getRandomData() {
		$result = $this->db->query('SELECT * FROM data ORDER BY RANDOM() LIMIT 1');

		$fetchResult = $result->fetchAll()[0];

		$data = new DObject\Data();
		$data->id = $fetchResult->id;
		$data->created_at = $fetchResult->created_at;
		$data->content = $fetchResult->content;

		return $data;
	}

	public function saveDumbFile() {
		$file = fopen('nette.safe://uploads/test.txt', 'r');
		$buffer = file_get_contents('nette.safe://uploads/test.txt');

		$this->db->query('INSERT INTO data', array(
			'content' => $buffer
		));

		return $buffer;
	}
}