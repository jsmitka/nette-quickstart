<?php

use Nette\Application\UI;
use Nette\Database\Table\Selection;

/**
 * Komponenta pro výpis seznam úkolů.
 */
class TaskList extends UI\Control
{
	/** @var \Nette\Database\Table\Selection */
	private $tasks;

	/** @var \Model */
	private $model;

	/** @var bool */
	private $displayUser = TRUE;

	/** @var bool */
	private $displayTaskList = FALSE;

	/**
	 * @param Nette\Database\Table\Selection $selection Model, jehož výpis se bude provádět.
	 * @param Model $model
	 */
	public function __construct(Selection $tasks, \Model $model)
	{
		parent::__construct();

		$this->tasks = $tasks;
		$this->model = $model;
	}

	/**
	 * Nastaví, zda se má zobrazovat sloupeček se seznamem úkolů.
	 * @param boolean $displayTaskList
	 */
	public function setDisplayTaskList($displayTaskList)
	{
		$this->displayTaskList = (bool)$displayTaskList;
	}

	/**
	 * Nastaví, zda se má zobrazovat sloupeček s uživatelem, kterému je úkol přiřazen.
	 * @param boolean $displayUser
	 */
	public function setDisplayUser($displayUser)
	{
		$this->displayUser = (bool)$displayUser;
	}


	/**
	 * Vykreslí komponentu. Šablonou komponenty je TaskList.latte.
	 */
	public function render()
	{
		$this->template->setFile(__DIR__ . '/TaskList.latte');
		$this->template->tasks = $this->tasks;
		$this->template->displayUser = $this->displayUser;
		$this->template->displayTaskList = $this->displayTaskList;
		$this->template->userId = $this->presenter->getUser()->getId();
		$this->template->render();
	}

	/**
	 * Načtení tasku podle ID. V případě neexistnece zobrazi flashmessage.
	 * @param $taskId ID úkolu
	 */
	protected function loadTask($taskId)
	{
		$task = $this->model->getTasks()->find($taskId)->fetch();

		if (!$task) {
			if (!$this->presenter->isAjax()) {
				$this->flashMessage('Task does not exist', 'error');
				$this->redirect('this');
			} else {
				$this->template->flashes[] = (object)array(
					'message' => 'Task does not exist', 'type' => 'error'
				);
				$this->invalidateControl();
			}
		}

		return $task;
	}

	/**
	 * Signál, který označí zadaný úkol jako splněný.
	 * @param $taskId ID úkolu.
	 */
	public function handleMarkDone($taskId)
	{
		$task = $this->loadTask($taskId);
		// ověření, zda je tento úkol uživateli skutečně přiřazen
		if ($task && $task->user_id === $this->presenter->getUser()->getId()) {
			$this->model->getTasks()->where(array('id' => $taskId))->update(array('done' => 1));
			// přesměrování nebo invalidace snippetu
			if (!$this->presenter->isAjax()) {
				$this->presenter->redirect('this');
			} else {
				$this->invalidateControl();
			}
		}
	}

}