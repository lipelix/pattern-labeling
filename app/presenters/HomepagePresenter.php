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
//		Debugger::dump($this->dataService->getRandomData()->getPointsArray());
		$this->template->points = $this->dataService->getRandomData()->getPointsArray();
	}
}
