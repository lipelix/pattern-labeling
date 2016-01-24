<?php

namespace App\Presenters;

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
	}
}
