<?php

namespace App\Presenters;

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class DataExplorerPresenter extends BasePresenter {

	public $dataService;
	public $httpRequest;

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

	public function renderDefault() {
		$this->template->hashTags = $this->dataService->getAllHashtags();
		$this->template->markedDataInfo = $this->dataService->getAllMarkedDataInfo();
		$this->template->queryTags = $this->httpRequest->getQuery('tags');
	}

	public function renderDataDetail() {
		$id = $this->httpRequest->getQuery('id');

		if ($id) {
			$this->template->data = $this->dataService->getMarkedData($id);

//			$this->template->points = $this->dataService->getData(4)->getPointsArray();
		}
	}

	public function handleDelete($id) {
		$this->dataService->deleteMarkedData($id);
		$this->redirect('DataExplorer:default');
	}

	public function handleExport($id) {
		$name = 'points-' . $id. '.txt';
		$content = $this->dataService->exportData($id);
		$httpResponse = $this->presenter->getHttpResponse();
		$httpResponse->setContentType('text/plain');
		$httpResponse->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
		$httpResponse->setHeader('Content-Length', strlen($content));
		$this->presenter->sendResponse(new TextResponse($content));
	}
}
