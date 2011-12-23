<?php

use Nette\Application\UI\Form;

/**
 * Presenter, který zajišťuje výpis seznamů úkolů.
 */
class TaskPresenter extends SecuredPresenter
{
	/** @var \Nette\Database\Table\ActiveRow */
	private $taskList;

	/**
	 * Výchozí akce presenteru. Zajistí výběr informací o seznamu úkolů z databáze.
	 * @param $id int ID seznamu úkolů.
	 */
	public function actionDefault($id)
	{
		$this->taskList = $this->model->getTaskLists()->find($id)->fetch();
		if ($this->taskList === FALSE) {
			$this->setView('notFound');
		}
	}

	/**
	 * Výchozí view presenteru. Zajistí zobrazení zadaného seznamu úkolů.
	 * @param $id int ID seznamu úkolů.
	 */
	public function renderDefault($id)
	{
		$this->template->taskList = $this->taskList;
	}


	/**
	 * Vytvoří komponentu se seznamem úkolů.
	 * @return TaskList
	 */
	protected function createComponentTaskList()
	{
		$tasks = $this->taskList->related('task')->order('done, created');
		return new TaskList($tasks, $this->model);
	}


	/**
	 * Vytvoří formulář pro zakládání úkolů.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentTaskForm()
	{
		$form = new Form();
		$form->addText('text', 'Úkol:', 40, 100)
			->addRule(Form::FILLED, 'Je nutné zadat text úkolu.');
		$form->addSelect('userId', 'Pro:', $this->model->getUsers()->fetchPairs('id', 'name'))
			->setPrompt('- Vyberte -')
			->addRule(Form::FILLED, 'Je nutné vybrat, komu je úkol přiřazen.')
			->setDefaultValue($this->getUser()->getId());
		$form->addSubmit('create', 'Vytvořit');
		$form->onSuccess[] = callback($this, 'taskFormSubmitted');
		return $form;
	}


	/**
	 * Zpracování odeslaného formuláře taskForm. Vytvoří nový úkol v aktuálním seznamu úkolů.
	 * @param Nette\Application\UI\Form $form
	 */
	public function taskFormSubmitted(Form $form)
	{
		// vložení nového úkolu
		$this->model->getTasks()->insert(array(
			'text' => $form->values->text,
			'user_id' => $form->values->userId,
			'created' => new DateTime(),
			'tasklist_id' => $this->taskList->id
		));
		// flash zprávička, přesměrování, invalidace.
		$this->flashMessage('Úkol přidán.', 'success');
		if (!$this->isAjax()) {
			$this->redirect('this');
		} else {
			$form->setValues(array('userId' => $form->values->userId), TRUE);
			$this->invalidateControl('form');
			$this['taskList']->invalidateControl();
		}
	}
}