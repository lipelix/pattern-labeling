<?php

namespace App\Presenters;

require_once('../vendor/fineuploader/php-traditional-server/handler.php');
//TODO: prilinkovat nejak lip:

use Nette\Application\ForbiddenRequestException;
use Tracy\Debugger;


class AdministrationPresenter extends BasePresenter {

	protected $dataService;
	public $httpRequest;

	//TODO: data do neonu
	protected $uploadDir = __DIR__ . '/../../www/uploads/data';

	public function __construct(\App\Service\DataService $dataService, \Nette\Http\Request $httpRequest) {
		$this->dataService = $dataService;
		$this->httpRequest = $httpRequest;
	}

	public function startup() {
		parent::startup();

		if (!$this->user->isInRole('admin')) {
			throw new ForbiddenRequestException();
		}
	}

	public function handleUploadData() {
		$uploader = new \UploadHandler();
		$uploader->allowedExtensions = array('txt', 'points');
		$result = $uploader->handleUpload($this->uploadDir);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($result));
	}

	public function handleUploadedData() {
		$this->dataService->getUploadedDataInfo($this->uploadDir);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('ok' => 'jo')));
	}

	public function handleDeleteFile($filename) {
		$filepath = $this->dataService->getUploadedFileByName($filename, $this->uploadDir);
		$this->dataService->removeDataFile($filepath);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success' => 'ok')));
	}

	public function handleLoadFilesToDB($filename) {
		$filepath = $this->dataService->getUploadedFileByName($filename, $this->uploadDir);
		$tags = json_decode($this->httpRequest->getPost('tags'));
		$this->dataService->saveDataFileToDB($filepath, $tags);
//		$this->dataService->removeDataFile($filepath);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success' => 'ok')));
	}

	public function renderDefault() {
		$this->template->uploadedFiles = $this->dataService->getUploadedFiles($this->uploadDir);
		$this->template->allDataInfo = $this->dataService->getAllDataInfo();
		$this->template->hashTags = $this->dataService->getAllHashtags();
//		Debugger::dump($this->template->allDataInfo);
	}

}
