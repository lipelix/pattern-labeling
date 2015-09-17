<?php

namespace App\Presenters;

use Nette\Application\UI\Form;


class LoginPresenter extends BasePresenter {

	private $httpRequest;
	public $usersService;

	public function __construct(\Nette\Http\Request $httpRequest, \App\Service\DbService $usersService) {
		$this->httpRequest = $httpRequest;
		$this->usersService = $usersService;
	}

	public function login() {}

	protected function createComponentLoginForm() {
		$form = new Form;
		$form->addText('login');
		$form->addPassword('password');
		$form->addSubmit('submit');
		$form->onSuccess[] = array($this, 'loginFormSucceeded');
		return $form;
	}

	public function loginFormSucceeded(Form $form, $values) {
		$login = $this->usersService->createUser(
			$values['login'],
			$values['password']
		);

		if (!$login) {
			$this->flashMessage('Chybný login nebo heslo. ', 'danger');
		} else {
			$this->flashMessage('Přihlášení proběhlo úspěšně', 'success');
		}

		$this->redirect('Homepage:');
	}

	public function renderDefault() {}
}