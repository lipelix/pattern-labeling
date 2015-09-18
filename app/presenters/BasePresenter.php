<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI\Form;

class BasePresenter extends Nette\Application\UI\Presenter {
	/** @persistent */
	public $locale;

	/** @var \Kdyby\Translation\Translator @inject */
	public $translator;

	/** @var \Nette\Security\User @inject */
	public $user;

	protected function createComponentLoginForm() {
		$form = new Form;
		$form->addText('login');
		$form->addPassword('password');
		$form->addSubmit('submit');
		$form->onSuccess[] = array($this, 'loginFormSucceeded');
		return $form;
	}

	public function loginFormSucceeded(Form $form, $values) {
		$this->user->login($values['login'], $values['password']);
		$this->template->test = 'srandakov';
		$this->flashMessage($this->translator->translate('layout.login_ok'), 'success');
	}

	protected function createComponentLogoutForm() {
		$form = new Form;
		$form->addSubmit('submit');
		$form->onSuccess[] = array($this, 'logoutFormSucceeded');
		return $form;
	}

	public function logoutFormSucceeded(Form $form, $values) {
		$this->user->logout();
		$this->flashMessage($this->translator->translate('layout.logout_ok'), 'success');
	}
}