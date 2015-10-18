<?php

namespace App\Service;

use Tracy\Debugger,
	App\DomainObject as DObject,
	App\Utils\DataParser;

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
			if ($subdir == '.' || $subdir == '..') continue;

			$subdirFiles = scandir($uploadDir. DIRECTORY_SEPARATOR .$subdir);

			foreach($subdirFiles as $file) {
				if ($file == '.' || $file == '..') continue;

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
			if ($subdir == '.' || $subdir == '..') continue;

			if ($filename == $subdir) return $uploadDir. DIRECTORY_SEPARATOR .$subdir;

			$subdirFiles = scandir($uploadDir. DIRECTORY_SEPARATOR .$subdir);

			foreach($subdirFiles as $file) {
				if ($file == '.' || $file == '..') continue;

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

	public function saveDataFileToDB($filePath) {
		$dp = new DataParser();
		$content = $dp->parse($filePath);

		$this->db->query('INSERT INTO data', array(
			'content' => $content
		));

		return $content;
	}
}