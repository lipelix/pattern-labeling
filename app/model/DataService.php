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

	public function saveDataFileToDB($filePath, $tags) {
		$dp = new DataParser();
		$content = $dp->parse($filePath);

		$this->db->query('INSERT INTO data', array('content' => $content));

		$row = $this->db->table('data')->insert(array('content' => $content));
		$dataId = $row->id;

		foreach ($tags as $tag) {
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

		$dataRows = $this->db->table('data');
		foreach ($dataRows as $data) {
			$dataInfo = new \stdClass();
			$dataInfo->id = $data->id;
			$dataInfo->created_at = $data->created_at;

			$tags = array();
			foreach ($this->db->table('tags_data')->where('data_id', $data->id) as $tagData) {
				$result = $this->db->query('SELECT name FROM tags WHERE id=?', $tagData->tag_id);
				$fetchResult = $result->fetchAll()[0];
				$tagName = $fetchResult->name;

//				Debugger::dump($tagRow);
				array_push($tags, $tagName);
			}

			$dataInfo->tags = $tags;
			array_push($dataInfoArray, $dataInfo);
		}

		return $dataInfoArray;
	}

	public function saveUserPaths($paths, $dataId, $userId) {
		$pathString = "";
		foreach ($paths as $path) {
			$pathString .= $path;
		}

		$row = $this->db->table('data_users')->insert(array(
					'data_id' => $dataId,
					'user_id' => $userId,
					'path' => $pathString));
	}
}