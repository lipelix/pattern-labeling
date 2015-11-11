<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI\Form,
	Nette\Security as NS;
use Tracy\Debugger;

class BasePresenter extends Nette\Application\UI\Presenter {
	/** @persistent */
	public $locale;

	/** @var \Kdyby\Translation\Translator @inject */
	public $translator;

	public $httpRequest;

	/** @var \App\Service\UsersService @inject */
	public $usersService;

	public function __construct(\Nette\Http\Request $httpRequest) {
		$this->httpRequest = $httpRequest;
	}

	protected function createComponentLoginForm() {
		$form = new Form;
		$form->addText('login');
		$form->addPassword('password');
		$form->addSubmit('submit');
		$form->onSuccess[] = array($this, 'loginFormSucceeded');
		return $form;
	}

	public function loginFormSucceeded(Form $form, $values) {
		try {
			$this->usersService->login($values['login'], $values['password']);
			$this->flashMessage($this->translator->translate('layout.login_ok'), 'success');
			$this->redirect('Homepage:');
		} catch (NS\AuthenticationException $e) {
			$this->flashMessage($e->getMessage(), 'danger');
			return;
		}
	}

	protected function createComponentLogoutForm() {
		$form = new Form;
		$form->addSubmit('submit');
		$form->onSuccess[] = array($this, 'logoutFormSucceeded');
		return $form;
	}

	public function logoutFormSucceeded(Form $form, $values) {
		$this->usersService->logout();
		$this->flashMessage($this->translator->translate('layout.logout_ok'), 'success');
		$this->redirect('Homepage:');
	}
}