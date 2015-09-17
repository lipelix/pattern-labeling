<?php

namespace App\Service;

class DbService extends \Nette\Object {

	/** @var \Nette\Database\Context */
	public $db;

	public function __construct(\Nette\Database\Context $context) {
		$this->db = $context;
	}

}