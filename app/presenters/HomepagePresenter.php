<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Tracy\Debugger;


class HomepagePresenter extends BasePresenter {

	protected $dataService;

	public function __construct(\App\Service\DataService $dataService) {
		$this->dataService = $dataService;
	}

	public function renderDefault() {
		$data = $this->dataService->getRandomData();
		if ($data == null) {
			$this->flashMessage('No data', 'warning');
		} else
			$this->template->points = $data->getPointsArray();
	}
}
