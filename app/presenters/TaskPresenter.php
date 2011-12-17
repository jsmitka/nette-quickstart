<?php

use Nette\Application\UI\Form;

/**
 * Presenter, který zajišťuje výpis seznamů úkolů.
 */
class TaskPresenter extends BasePresenter
{
	/** @var \Nette\Database\Table\ActiveRow */
	private $taskList;

	/**
	 * Výchozí akce presenteru. Zajistí výběr informací o seznamu úkolů z databáze.
	 * @param $id ID seznamu úkolů.
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
	 * @param $id ID seznamu úkolů.
	 */
	public function renderDefault($id)
	{
		$this->template->taskList = $this->taskList;
		$this->template->tasks = $this->taskList->related('task')->order('id');
	}



	protected function createComponentTaskForm()
	{
		$form = new Form();
		$form->addText('text', 'Úkol:', 40, 100)
			->addRule(Form::FILLED, 'Je nutné zadat text úkolu.');
		$form->addSelect('user_id', 'Pro:', $this->model->getUsers()->fetchPairs('id', 'name'))
			->setPrompt('- Vyberte -')
			->addRule(Form::FILLED, 'Je nutné vybrat, komu je úkol přiřazen.');
		$form->addSubmit('create', 'Vytvořit');
		return $form;
	}
}