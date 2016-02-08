<?php

namespace App\Service;

use Tracy\Debugger,
	App\DomainObject as DObject,
	App\Utils\DataParser,
	Nette\Utils\Strings as String;

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

		if ($result->getRowCount() == 0) return null;
		$fetchResult = $result->fetchAll()[0];

		$data = new DObject\Data();
		$data->id = $fetchResult->id;
		$data->created_at = $fetchResult->created_at;
		$data->content = $fetchResult->content;

		return $data;
	}

	public function getRandomUnmarkedData($userId) {
		$result = $this->db->query('SELECT * FROM data WHERE id NOT IN (SELECT data_id FROM data_users WHERE data_users.user_id = ?) LIMIT 1', $userId);

		if ($result->getRowCount() == 0) return null;
		$fetchResult = $result->fetchAll()[0];

		$data = new DObject\Data();
		$data->id = $fetchResult->id;
		$data->created_at = $fetchResult->created_at;
		$data->content = $fetchResult->content;

		return $data;
	}

	public function getData($id) {
		$result = $this->db->query('SELECT * FROM data WHERE id=?', $id);

		if ($result->getRowCount() == 0) return null;
		$fetchResult = $result->fetchAll()[0];

		$data = new DObject\Data();
		$data->id = $fetchResult->id;
		$data->created_at = $fetchResult->created_at;
		$data->content = $fetchResult->content;

		return $data;
	}

	public function getUploadedFiles($uploadDir) {
		$dir = scandir($uploadDir);
		$files = array();

		foreach($dir as $subdir) {
			if ($subdir == '.' || $subdir == '..' || $subdir == '.gitignore') continue;

			$subdirFiles = scandir($uploadDir. DIRECTORY_SEPARATOR .$subdir);

			foreach($subdirFiles as $file) {
				if ($file == '.' || $file == '..' || $subdir == '.gitignore') continue;

				$fileObject = new \stdClass();
				$fileObject->name = $file;
				$fileObject->size = filesize($uploadDir. DIRECTORY_SEPARATOR .$subdir. DIRECTORY_SEPARATOR .$file);
				$fileObject->path = $uploadDir. DIRECTORY_SEPARATOR .$subdir. DIRECTORY_SEPARATOR .$file;
				array_push($files, $fileObject);
			}
		}

		return $files;
	}

	public function getUploadedFileByName($filename, $uploadDir) {
		$dir = scandir($uploadDir);

		foreach($dir as $subdir) {
			if ($subdir == '.' || $subdir == '..' || $subdir == '.gitignore') continue;

			if ($filename == $subdir) return $uploadDir. DIRECTORY_SEPARATOR .$subdir;

			$subdirFiles = scandir($uploadDir. DIRECTORY_SEPARATOR .$subdir);

			foreach($subdirFiles as $file) {
				if ($file == '.' || $file == '..' || $subdir == '.gitignore') continue;

				if ($filename == $file) return $uploadDir. DIRECTORY_SEPARATOR .$subdir. DIRECTORY_SEPARATOR .$file;
			}
		}

		return null;
	}

	public function removeDataFile($filePath) {
		if (file_exists($filePath)) {
			unlink($filePath);
			rmdir(dirname($filePath));
		}
	}

	public function deleteData($id) {
		$this->db->query('DELETE FROM tags_data WHERE data_id=?',$id);
		$this->db->query('DELETE FROM data WHERE id=?',$id);
	}

	public function saveDataFileToDB($filePath, $tags) {
		$dp = new DataParser();
		$content = $dp->parse($filePath);

		$row = $this->db->table('data')->insert(array('content' => $content));
		$dataId = $row->id;

		foreach ($tags as $tag) {
			if (String::length(String::trim($tag)) < 1) continue;

			$tag = String::lower($tag);
			$result = $this->db->query('SELECT id FROM tags WHERE name=?', $tag);

			if ($result->getRowCount() == 0) {
				$this->db->query('INSERT INTO tags', array('name' => $tag));
				$result = $this->db->query('SELECT id FROM tags WHERE name=?', $tag);
			}
			$fetchResult = $result->fetchAll()[0];
			$tagId = $fetchResult->id;

			$this->db->query('INSERT INTO tags_data', array('tag_id' => $tagId, 'data_id' => $dataId));
		}
	}

	public function getAllHashtags() {
		$tags = $this->db->table('tags');
		return $tags;
	}

	public function getAllDataInfo() {
		$dataInfoArray = array();

		$dataRows = $this->db->table('data')->order('created_at DESC');
		foreach ($dataRows as $data) {
			$dataInfo = new \stdClass();
			$dataInfo->id = $data->id;
			$dataInfo->created_at = $data->created_at;

			$tags = array();
			foreach ($this->db->table('tags_data')->where('data_id', $data->id) as $tagData) {
				$result = $this->db->query('SELECT name FROM tags WHERE id=?', $tagData->tag_id);
				$fetchResult = $result->fetchAll()[0];
				$tagName = $fetchResult->name;
				array_push($tags, $tagName);
			}

			$dataInfo->tags = $tags;
			array_push($dataInfoArray, $dataInfo);
		}

		return $dataInfoArray;
	}


	public function getAllMarkedDataInfo() {
		$dataInfoArray = array();

		$dataUsers = $this->db->table('data_users')->order('created_at DESC');

		foreach($dataUsers as $dataUser) {
			$dataInfo = new \stdClass();
			$dataInfo->id = $dataUser->id;
			$dataInfo->data_id = $dataUser->data_id;
			$dataInfo->created_at = $dataUser->created_at;

			$dataInfo->login = '-';
			if ($dataUser->ref('users', 'user_id'))
				$dataInfo->login = $dataUser->ref('users', 'user_id')->login;

			$dataInfo->tags = array();
			$tagsData = $this->db->table('tags_data')->where('data_id', $dataUser->data_id);

			foreach ($tagsData as $tagData) {
				array_push($dataInfo->tags, $tagData->ref('tags', 'tag_id')->name);
			}

			array_push($dataInfoArray, $dataInfo);
		}

		return $dataInfoArray;
	}

	public function getMarkedData($id) {

		$dataRows = $this->db->table('data_users')->where('id', $id);

		$dataUsers = new DObject\DataUsers();
		$dataUsers->login = "-";
		foreach ($dataRows as $data) {
			$dataUsers->id = $data->id;
			$dataUsers->data_id = $data->data_id;
			$dataUsers->created_at = $data->created_at;
			$dataUsers->polygons = json_decode($data->polygon);
			$dataUsers->marked_data = $data->marked_data;

			if ($data->ref('users', 'user_id'))
				$dataUsers->login = $data->ref('users', 'user_id')->login;;
		}

		$dataUsers->points = $dataUsers->getPointsArray();

		return $dataUsers;
	}

	public function exportData($id) {
		$dataRows = $this->db->table('data_users')->where('id', $id);

		$dataUsers = new DObject\DataUsers();
		foreach ($dataRows as $data) {
			$dataUsers->marked_data = $data->marked_data;
		}
		$result = $dataUsers->export();

		return $result;
	}

	public function deleteMarkedData($id) {
		if (isset($id)) {
			$result = $this->db->query('DELETE FROM data_users WHERE id=?', $id);
		}
	}

	public function saveUserPolygons($points, $polygons, $dataId, $userId) {
		if (isset($userId)) {
			$result = $this->db->query('SELECT id FROM data_users WHERE data_id=? AND user_id=?', $dataId, $userId);
			if ($result->getRowCount() != 0) return false;
		}

		$row = $this->db->table('data_users')->insert(array(
					'data_id' => $dataId,
					'user_id' => $userId,
					'polygon' => $polygons,
					'marked_data' => $points
		));

		return true;
	}
}