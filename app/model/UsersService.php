<?php

namespace App\Service;

use	Nette\Utils\Strings as String;
use Tracy\Debugger;


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

	public function createUser($login, $password, $email, $firstName, $lastName, $gender, $age, $groups) {
		if ($this->userExist($login))
			return false;

		$this->db->query('INSERT INTO users', array(
			'login' => $login,
			'password' => $this->passwords->hash($password),
			'email' => $email,
			'first_name' => $firstName,
			'last_name' => $lastName,
			'gender' => $gender,
			'age' => intval($age),
			'groups' => $groups,
			'role' => 'member'
		));

		return true;
	}

	public function createAdmin() {
		$this->db->query('INSERT INTO users', array(
			'login' => 'admin',
			'password' => $this->passwords->hash('admin'),
			'email' => 'admin@datamarker.info',
			'first_name' => 'admin',
			'last_name' => 'admin',
			'gender' => '',
			'age' => 0,
			'role' => 'admin'
		));
	}

	public function getAllUsersInfo() {
		$usersInfoArray = array();

		$dataRows = $this->db->table('users');
		foreach ($dataRows as $data) {
			$userInfo = new \stdClass();
			$userInfo->id = $data->id;
			$userInfo->login = $data->login;
			$userInfo->firstName = $data->first_name;
			$userInfo->lastName = $data->last_name;

			$userInfo->age = "-";
			if ($data->age != 0)
				$userInfo->age = $data->age;
			$userInfo->gender = $data->gender;
			$userInfo->role = $data->role;
			$userInfo->groups = $data->groups;

			array_push($usersInfoArray, $userInfo);
		}

		return $usersInfoArray;
	}

	public function prepareUsersForSave($filePath) {
		$logins = $this->getLoginsFromFile($filePath);
		$file = fopen('nette.safe://'.$filePath.'.generated', 'a');

		$users = array();
		foreach ($logins as $login) {
			$user = new \stdClass();
			$user->name = $login['name'];
			$user->email = $login['email'];
			$user->gender = $login['gender'];
			$user->age = $login['age'];
			$user->login = String::webalize(String::fixEncoding($login['name']));
			$user->password = String::random(6);
			$user->groups = $login['groups'];
			array_push($users, $user);

			$output = $user->name . "/" . $user->login . "/" . $user->password . "/" . $user->email . "/" . $user->gender . "/" . $user->age .  "/" . $user->groups . PHP_EOL;
			fputs($file, $output);
		}

		return $users;
	}

	public function getPreparedUsers($filePath) {
		if (!strpos($filePath, '.generated'))
			return array();

		$file = fopen('nette.safe://'.$filePath, 'r');
		$users = array();

		while (!feof($file)) {
			$line = fgets($file);
			$linePars = explode("/", trim($line));

			while (!isset($linePars[6]))
				array_push($linePars, "");

			$user = new \stdClass();
			$user->name = $linePars[0];
			$user->login = $linePars[1];
			$user->password = $linePars[2];
			$user->email = $linePars[3];
			$user->gender = $linePars[4];
			$user->age = $linePars[5];
			$user->groups = $linePars[6];

			array_push($users, $user);
		}

		return $users;
	}

	public function savePreparedUsers($filePath) {
		$file = fopen('nette.safe://'.$filePath.'.generated', 'r');
		$users = array();

		while (!feof($file)) {
			$line = fgets($file);

			$linePars = explode("/", trim($line));

			while (!isset($linePars[6]))
				array_push($linePars, "");

			$user = new \stdClass();
			$name = explode(" ", $linePars[0]);
			$user->first_name = $name[0];
			$user->last_name = $name[1];
			$user->login = $linePars[1];
			$user->password = $linePars[2];
			$user->email = $linePars[3];
			$user->gender = $linePars[4];
			$user->age = $linePars[5];
			$user->groups = $linePars[6];
		}

		return $users;
	}

	public function saveUsersToDB($users) {
		foreach ($users as $user) {
			$name = explode(" ", $user->name);
			$this->createUser($user->login, $user->password, $user->email, $name[0], $name[1], $user->gender, intval($user->age), $user->groups);
		}
	}

	public function exportPreparedUsers($filePath) {
		$file = fopen('nette.safe://'.$filePath.'.generated', 'r');
		$users = array();

		while (!feof($file)) {
			$line = fgets($file);
			$linePars = explode(" / ", trim($line));

			$user = new \stdClass();
			$user->name = $linePars[0];
			$user->login = $linePars[1];
			$user->password = $linePars[2];
			$user->groups = $linePars[3];
			array_push($users, $user);
		}

		return $users;
	}

	public function deletePreparedUsers($usersDir) {
		$files = glob($usersDir.'/*');
		foreach ($files as $file) {
			is_dir($file) ? $this->removeDirectory($file) : unlink($file);
		}

		return;
	}

	private function removeDirectory($path) {
		$files = glob($path . '/*');
		foreach ($files as $file) {
			is_dir($file) ? removeDirectory($file) : unlink($file);
		}
		rmdir($path);
		return;
	}

	protected function getLoginsFromFile($filePath) {
		$file = fopen('nette.safe://'.$filePath, 'r');
		$logins = array();

		while (!feof($file)) {
			$line = fgets($file);
			if (trim($line)[0] == '#') continue;

			$login = explode('|', trim($line));
//			Debugger::dump($login);
			while(!isset($login[4])) {
				array_push($login, "");
			}
			array_push($logins, array(
				'name' => $login[0],
				'email' => $login[1],
				'gender' => $login[2],
				'age' => $login[3],
				'groups' => $login[4],
			));
		}

		return $logins;
	}

	public function userExist($login) {
		$result = $this->db->query('SELECT * FROM users WHERE login=?', $login);

		if ($result->getRowCount() == 0)
			return false;
		else
			return true;
	}

	public function login($login, $password) {
		return $this->user->login($login, $password);
	}

	public function logout() {
		return $this->user->logout();
	}
}