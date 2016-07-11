<?php

namespace App\Presenters;

require_once('../vendor/fineuploader/php-traditional-server/handler.php');
//TODO: prilinkovat nejak lip:

use Nette\Application\ForbiddenRequestException;
use Nette\Neon\Exception;
use Tracy\Debugger;


class AdministrationPresenter extends BasePresenter {

	protected $dataService;
	public $httpRequest;

	//TODO: data do neonu
	protected $uploadDir = __DIR__ . '/../../www/uploads/data';
	protected $uploadDirUsers = __DIR__ . '/../../www/uploads/users';

	public function __construct(\App\Service\DataService $dataService, \App\Service\UsersService $usersService, \Nette\Http\Request $httpRequest) {
		$this->dataService = $dataService;
		$this->usersService = $usersService;
		$this->httpRequest = $httpRequest;
	}

	public function startup() {
		parent::startup();

		if (!$this->user->isInRole('admin')) {
			throw new ForbiddenRequestException();
		}
	}

	public function handleUploadUsers() {
		$uploader = new \UploadHandler();
		$uploader->allowedExtensions = array('txt');
		$result = $uploader->handleUpload($this->uploadDirUsers);
		$usersFile = $this->dataService->getUploadedFiles($this->uploadDirUsers)[0];
		$this->usersService->prepareUsersForSave($usersFile->path);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($result));
	}

	public function handleUsersLoginsSave() {
		if ($this->dataService->getUploadedFiles($this->uploadDirUsers)) {
			$files = $this->dataService->getUploadedFiles($this->uploadDirUsers);
			$users = array();
			foreach($files as $usersFile) {
				$users = array_merge($users, $this->usersService->getPreparedUsers($usersFile->path));
			}

			$this->usersService->saveUsersToDB($users);
			$this->usersService->deletePreparedUsers($this->uploadDirUsers);
		}
	}

	public function handleExportUsers() {
		$usersFile = $this->dataService->getUploadedFiles($this->uploadDirUsers)[0];
		$this->sendResponse(new \Nette\Application\Responses\FileResponse($usersFile->path.'.generated', 'logins.txt', 'text/plain'));
	}

	public function handleUploadData() {
		$uploader = new \UploadHandler();
		$uploader->allowedExtensions = array('txt', 'points');
		$result = $uploader->handleUpload($this->uploadDir);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($result));
	}

	public function handleDeleteFile($filename) {
		$filepath = $this->dataService->getUploadedFileByName($filename, $this->uploadDir);
		$this->dataService->removeDataFile($filepath);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success' => 'ok')));
	}

	public function handleDeleteData($id) {
		try {
			$this->dataService->deleteData($id);
		} catch(\Exception $e) {
			$this->flashMessage($this->translator->translate('administration.delete_referenced'), 'danger');
			$this->redirect('Administration:default');
		}
		$this->flashMessage($this->translator->translate('administration.delete_ok', ['id' => $id]), 'success');
		$this->redirect('Administration:default');
	}

	public function handleLoadFilesToDB($filename) {
		$filepath = $this->dataService->getUploadedFileByName($filename, $this->uploadDir);
		$tags = json_decode($this->httpRequest->getPost('tags'));
		$this->dataService->saveDataFileToDB($filepath, $tags);
		$this->dataService->removeDataFile($filepath);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success' => 'ok')));
	}

	public function renderDefault() {
		$this->template->uploadedFiles = $this->dataService->getUploadedFiles($this->uploadDir);
		$this->template->allDataInfo = $this->dataService->getAllDataInfo();
		$this->template->usersInfo = $this->usersService->getAllUsersInfo();
		$this->template->hashTags = $this->dataService->getAllHashtags();

		$this->template->usersUploadInfo =array();

		if ($this->dataService->getUploadedFiles($this->uploadDirUsers)) {
			$files = $this->dataService->getUploadedFiles($this->uploadDirUsers);
			$users = array();
			foreach($files as $usersFile) {
				$users = array_merge($users, $this->usersService->getPreparedUsers($usersFile->path));
			}

			$this->template->usersUploadInfo = $users;
		}
	}

}
