<?php

use Nette\Application\UI\Form;

/**
 * Základní třída pro všechny presentery aplikace.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var Model */
	protected $model;

	/**
	 * Inicializace presenteru. Zajišťuje získání instance modelu.
	 */
	public function startup()
	{
		parent::startup();
		$this->model = $this->getService('model');
	}


	/**
	 * Před vykreslením. Zajišťuje získání seznamů úkolů.
	 */
	public function beforeRender()
	{
		$this->template->taskLists = $this->model->getTaskLists()->order('title ASC');
		if ($this->isAjax()) {
			$this->invalidateControl('flashMessages');
		}
	}


	/**
	 * Vytvoří formulář pro založení nového seznamu úkolů.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentNewTasklistForm()
	{
		if (!$this->user->isLoggedIn())
			return NULL;
		$form = new Form();
		$form->addText('title', 'Název:', 15, 50)
			->addRule(Form::FILLED, 'Musíte zadat název seznamu úkolů.');
		$form->addSubmit('create', 'Vytvořit');
		$form->onSuccess[] = callback($this, 'newTasklistFormSubmitted');
		return $form;
	}

	/**
	 * Zpracování formuláře newTasklistForm. Založí nový seznam úkolů.
	 * @param Nette\Application\UI\Form $form
	 */
	public function newTasklistFormSubmitted(Form $form)
	{
		$this->model->getTaskLists()->insert(array(
			'title' => $form->values->title
		));
		$this->flashMessage('Seznam úkolů založen.', 'success');
		$this->redirect('this');
	}
}
