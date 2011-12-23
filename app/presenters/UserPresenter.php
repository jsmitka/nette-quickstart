<?php

use Nette\Application\UI\Form,
	Nette\Security as NS;


class UserPresenter extends SecuredPresenter
{
	/** @var Authenticator */
	private $authenticator;

	/**
	 * Inicializace.
	 */
	public function startup()
	{
		parent::startup();
		$this->authenticator = $this->getService('authenticator');
	}

	/**
	 * Továrna na vytvoření formuláře pro změnu hesla.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentPasswordForm()
	{
		$form = new Form();
		$form->addPassword('oldPassword', 'Staré heslo:', 30)
			->addRule(Form::FILLED, 'Je nutné zadat staré heslo.');
		$form->addPassword('newPassword', 'Nové heslo:', 30)
			->addRule(Form::MIN_LENGTH, 'Nové heslo musí mít alespoň %d znaků.', 6);
		// obě pole se musejí shodovat
		$form->addPassword('confirmPassword', 'Potvrzení hesla:', 30)
			->addRule(Form::FILLED, 'Nové heslo je nutné zadat ještě jednou pro potvrzení.')
			->addRule(Form::EQUAL, 'Zadná hesla se musejí shodovat.', $form['newPassword']);
		$form->addSubmit('set', 'Změnit heslo');
		$form->onSuccess[] = callback($this, 'passwordFormSubmitted');
		return $form;
	}


	/**
	 * Zpracuje odeslaný formulář. Mění heslo uživatele.
	 * @param Nette\Application\UI\Form $form
	 */
	public function passwordFormSubmitted(Form $form)
	{
		$values = $form->getValues();
		$user = $this->getUser();
		try {
			// ověření správnosti starého hesla
			$this->authenticator->authenticate(array($user->getIdentity()->username, $values->oldPassword));
			// změna hesla
			$this->authenticator->setPassword($user->getId(), $values->newPassword);
			// flash zprávička a přesměrování
			$this->flashMessage('Heslo bylo změněno.', 'success');
			$this->redirect('Homepage:');
		} catch (NS\AuthenticationException $e) {
			$form->addError('Zadané heslo není správné.');
		}
	}
}