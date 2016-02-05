<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Tracy\Debugger;


class HomepagePresenter extends BasePresenter {

	public $dataService;
	public $httpRequest;

	public function __construct(\App\Service\DataService $dataService, \Nette\Http\Request $httpRequest) {
		$this->dataService = $dataService;
		$this->httpRequest = $httpRequest;
	}

	public function renderDefault() {
		$data = $this->dataService->getRandomData();
		if ($data == null) {
			$this->flashMessage('No data', 'warning');
		} else
			$this->template->points = $data->getPointsArray();
			$this->template->dataId = $data->id;
	}

	public function handleSend() {
		$points = $this->httpRequest->getPost('points');
		$dataId = $this->httpRequest->getPost('dataId');

		$userId = null;
		if ($this->user->isLoggedIn())
			$userId = $this->user->getId();

//		$this->dataService->saveUserPaths($paths, $dataId, $userId);
		$this->flashMessage($this->translator->translate('home.data_send_ok'), 'success');
//		Debugger::dump($userId);
//		$this->redirect('Homepage:');
	}
}
