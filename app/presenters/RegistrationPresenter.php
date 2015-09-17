<?php

namespace App\Presenters;

use Nette\Application\UI\Form;


class RegistrationPresenter extends BasePresenter {

	private $httpRequest;
	public $usersService;

	public function __construct(\Nette\Http\Request $httpRequest, \App\Service\DbService $usersService) {
		$this->httpRequest = $httpRequest;
		$this->usersService = $usersService;
	}

	protected function createComponentRegistrationForm() {
		$form = new Form;
		$form->addText('login');
		$form->addPassword('password');
		$form->addText('email');
		$form->addText('firstName');
		$form->addText('lastName');
		$form->addText('age');
		$form->addRadioList('gender', 'Pohlaví:', array(
			'm' => 'Muž',
			'f' => 'Žena'
		));
		$form->addSubmit('submit');
		$form->onSuccess[] = array($this, 'registrationFormSucceeded');
		return $form;
	}

	public function registrationFormSucceeded(Form $form, $values) {
		$userResult = $this->usersService->createUser(
			$values['login'],
			$values['password'],
			$values['email'],
			$values['firstName'],
			$values['lastName'],
			intval($values['age']),
			$values['gender']
		);

		if ($userResult) {
			$this->flashMessage($this->translator->translate('registration.reg_ok'), 'success');
		} else {
			$this->flashMessage($this->translator->translate('registration.login_exist', ['name' => $values['login']]), 'danger');
		}

		$this->redirect('Registration:');
	}

	public function renderDefault() {}
}