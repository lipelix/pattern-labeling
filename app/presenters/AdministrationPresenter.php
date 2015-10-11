<?php

namespace App\Presenters;

use Nette\Application\ForbiddenRequestException;


class AdministrationPresenter extends BasePresenter {

	protected $dataService;

	public function __construct(\App\Service\DataService $dataService) {
		$this->dataService = $dataService;
	}

	public function startup() {
		parent::startup();

		if (!$this->user->isInRole('admin')) {
			throw new ForbiddenRequestException();
		}
	}

	public function uploadData() {
		$uploader = new \UploadHandler();
		$uploader->allowedExtensions = array("txt", "jpg");
		$result = $uploader->handleUpload(__DIR__ . '/../../www/uploads');
		$this->sendResponse(new Nette\Application\Responses\JsonResponse($result));
	}

	public function handleSaveData() {
		$res = $this->dataService->saveDumbFile();
		$this->flashMessage($res, 'success');
		$this->redirect("Administration:");
	}

}
