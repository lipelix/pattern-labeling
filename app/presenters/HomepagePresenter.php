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
		if ($this->user->isLoggedIn()) {
			$userId = $this->user->getId();
			$data = $this->dataService->getRandomUnmarkedData($userId);
		}
		else {
			$data = $this->dataService->getRandomData();
		}
		if ($data == null) {
			$this->flashMessage($this->translator->translate('home.no_data'), 'warning');
		} else {
			$this->template->points = $data->getPointsArray();
			$this->template->dataId = $data->id;
		}
	}

	public function handleSend() {
		$points = $this->httpRequest->getPost('points');
		$polygons = $this->httpRequest->getPost('polygons');
		$dataId = $this->httpRequest->getPost('dataId');

		$userId = null;
		if ($this->user->isLoggedIn())
			$userId = $this->user->getId();

		if ($this->dataService->saveUserPolygons($points, $polygons, $dataId, $userId)) {
			$this->flashMessage($this->translator->translate('home.data_send_ok'), 'success');
		} else {
			if (isset($userId)) {
				$this->flashMessage($this->translator->translate('home.already_marked'), 'danger');
			} else {
				$this->flashMessage($this->translator->translate('home.marked_error'), 'danger');
			}
		}

		$this->redirect('Homepage:');
	}
}
