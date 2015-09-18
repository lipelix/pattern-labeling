<?php

namespace App\Service;


class UsersService extends DbService {

	/** @var \Nette\Database\Context */
	public $db;

	/** @var \Nette\Security\Passwords */
	public $passwords;

	public function __construct(\Nette\Database\Context $context, \Nette\Security\Passwords $passwords) {
		$this->db = $context;
		$this->passwords = $passwords;
	}

	public function createUser($login, $password, $firstName, $lastName, $gender, $age) {
		if ($this->userExist($login))
			return false;

		$this->db->query('INSERT INTO users', array(
			'login' => $login,
			'password' => $this->passwords->hash($password),
			'first_name' => $firstName,
			'last_name' => $lastName,
			'gender' => $gender,
			'age' => $age,
			'role' => 'member'
		));

		return true;
	}

	public function login($login, $password) {
		if ($this->userExist($login))
			return false;

		return true;
	}

	public function userExist($login) {
		$result = $this->db->query('SELECT * FROM users WHERE login=?', $login);

		if ($result->getRowCount() >= 1)
			return true;

		return false;
	}
}