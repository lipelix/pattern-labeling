<?php

namespace App\Service;


class UsersService {

	/** @var \Nette\Database\Context */
	public $db;

	/** @var \Nette\Security\Passwords */
	public $passwords;

	/** @var \Nette\Security\User */
	public $user;


	public function __construct(\Nette\Database\Context $context, \Nette\Security\Passwords $passwords, \Nette\Security\User $user) {
		$this->db = $context;
		$this->passwords = $passwords;
		$this->user = $user;
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
		return $this->user->login($login, $password);
	}

	public function logout() {
		return $this->user->logout();
	}
}