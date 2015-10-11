<?php

namespace App\Service;

use Nette\Security as NS,
	Nette;

class LoginAuthenticator extends Nette\Object implements NS\IAuthenticator {

	protected $db;


	public function __construct(\Nette\Database\Context $context) {
		$this->db = $context;
	}


	function authenticate(array $credentials) {
		list($login, $password) = $credentials;
		$row = $this->db->table('users')
			->where('login', $login)->fetch();

		if (!$row) {
			throw new NS\AuthenticationException('User not found');
		}

		if (!NS\Passwords::verify($password, $row->password)) {
			throw new NS\AuthenticationException('Invalid password');
		}

		return new NS\Identity($row->id, $row->role, array('login' => $row->login, 'role' => $row->role));
	}
}