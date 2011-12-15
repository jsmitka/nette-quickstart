<?php

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
}