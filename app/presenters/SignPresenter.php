<?php

use Nette\Application\UI\Form,
	Nette\Security;


/**
 * Presenter, který se stará o přihlašování uživatelů.
 */
class SignPresenter extends BasePresenter
{

	protected function createComponentSignInForm()
	{
		$form = new Form();
		$form->addText('username', 'Uživatelské jméno:', 30, 20);
		$form->addPassword('password', 'Heslo:', 30);
		$form->addCheckbox('persistent', 'Pamatovat si mě na tomto počítači');
		$form->addSubmit('login', 'Přihlásit se');
		$form->onSuccess[] = callback($this, 'signInFormSubmitted');
		return $form;
	}

	public function signInFormSubmitted(Form $form)
	{
		try {
			$user = $this->getUser();
			$values = $form->getValues();
			if ($values->persistent) {
				$user->setExpiration('+30 days', FALSE);
			}
			$user->login($values->username, $values->password);
			$this->flashMessage('Přihlášení bylo úspěšné.', 'success');
			$this->redirect('Homepage:');
		} catch (Security\AuthenticationException $e) {
			$form->addError('Neplatné uživatelské jméno nebo heslo.');
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->redirect('in');
	}

}
