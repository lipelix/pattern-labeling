<?php

namespace App\Service;


class UsersService extends DbService {

	public function createUser($login, $password, $firstName, $lastName, $gender, $age) {
		if ($this->userExist($login))
			return false;

		$this->db->query('INSERT INTO users', array(
			'login' => $login,
			'password' => $this->getPassHash($password),
			'first_name' => $firstName,
			'last_name' => $lastName,
			'gender' => $gender,
			'age' => $age
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

	protected function getPassHash($password) {
		return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
	}
}