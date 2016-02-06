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

	public function renderDefault() {
		$this->template->hashTags = $this->dataService->getAllHashtags();

		if ($this->httpRequest->getQuery('tags') != null) {
			$tags = explode(",",$this->httpRequest->getQuery('tags'));
			$this->template->markedDataInfo = $this->dataService->getFilteredMarkedDataInfo($tags);
		}
		else {
			$this->template->markedDataInfo = $this->dataService->getAllMarkedDataInfo();
		}

		$this->template->queryTags = $this->httpRequest->getQuery('tags');
	}

	public function renderDataDetail() {
		$id = $this->httpRequest->getQuery('id');

		if ($id) {
			$this->template->data = $this->dataService->getMarkedData($id);
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
