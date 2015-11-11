<?php

namespace App\Presenters;

use Nette\Application\UI\Form;


class RegistrationPresenter extends BasePresenter {

	protected function createComponentRegistrationForm() {
		$form = new Form;
		$form->addText('login')->setRequired();
		$form->addPassword('password')->setRequired();
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
			$this->user->login($values['login'], $values['password']);
			$this->redirect('Homepage:');
		} else {
			$this->flashMessage($this->translator->translate('registration.login_exist', ['name' => $values['login']]), 'danger');
		}
	}

	public function renderDefault() {
	}
}