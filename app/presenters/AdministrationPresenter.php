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
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse(array('success' => 'ok')));
	}

	public function renderDefault() {
		$this->template->uploadedFiles = $this->dataService->getUploadedFiles($this->uploadDir);
		$this->template->allDataInfo = $this->dataService->getAllDataInfo();
		$this->template->hashTags = $this->dataService->getAllHashtags();
	}

}
