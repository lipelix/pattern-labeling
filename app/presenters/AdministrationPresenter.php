<?php

namespace App\Presenters;

require_once('../vendor/fineuploader/php-traditional-server/handler.php');  /** TODO - prilinkovat nejak lip */

use Nette\Application\ForbiddenRequestException;


class AdministrationPresenter extends BasePresenter {

	protected $dataService;

	protected $uploadDir =  __DIR__ . '/../../www/uploads/data'; /** TODO - data do neonu */

	public function __construct(\App\Service\DataService $dataService) {
		$this->dataService = $dataService;
	}

	public function startup() {
		parent::startup();

		if (!$this->user->isInRole('admin')) {
			throw new ForbiddenRequestException();
		}
	}

	public function handleUploadData() {
		$uploader = new \UploadHandler();
		$uploader->allowedExtensions = array('txt','points');
		$result = $uploader->handleUpload($this->uploadDir);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($result));
	}

	public function handleUploadedData() {
		$this->dataService->getUploadedDataInfo($this->uploadDir);

		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('ok'=>'jo')));
	}

	public function handleDeleteFile($filename) {
		$filepath = $this->dataService->getUploadedFileByName($filename, $this->uploadDir);
		$this->dataService->removeDataFile($filepath);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success'=>'ok')));
	}

	public function handleLoadFilesToDB($filename) {
		$filepath = $this->dataService->getUploadedFileByName($filename, $this->uploadDir);
		$this->dataService->saveDataFileToDB($filepath);
		$this->dataService->removeDataFile($filepath);
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success'=>'ok')));
	}

	public function renderDefault() {
		$this->template->uploadedFiles = $this->dataService->getUploadedFiles($this->uploadDir);
	}

}
