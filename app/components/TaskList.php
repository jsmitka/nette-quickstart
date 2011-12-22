<?php

use Nette\Application\UI;
use Nette\Database\Table\Selection;

/**
 * Komponenta pro výpis seznam úkolů.
 */
class TaskList extends UI\Control
{
	/** @var \Nette\Database\Table\Selection */
	private $selection;

	/** @var Model */
	private $model;

	/** @var bool */
	private $displayUser = TRUE;

	/** @var bool */
	private $displayTaskList = FALSE;

	/**
	 * @param Nette\Database\Table\Selection $selection Model, jehož výpis se bude provádět.
	 * @param Nette\ComponentModel\IContainer|null $parent Rodičovská komponenta.
	 * @param string $name Jméno komponenty.
	 */
	public function __construct(Selection $selection, Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->selection = $selection;
	}


	/**
	 * Nastaví model. Je nutný pro aktualizaci úkolů.
	 * @param \Model $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}

	/**
	 * Získá model.
	 * @return \Model
	 */
	public function getModel()
	{
		return $this->model;
	}


	/**
	 * Vykreslí komponentu. Šablonou komponenty je TaskList.latte.
	 */
	public function render()
	{
		$this->template->setFile(__DIR__ . '/TaskList.latte');
		$this->template->tasks = $this->selection;
		$this->template->displayUser = $this->displayUser;
		$this->template->displayTaskList = $this->displayTaskList;
		$this->template->userId = $this->presenter->getUser()->getId();
		$this->template->render();
	}


	/**
	 * Signál, který označí zadaný úkol jako splněný.
	 * @param $taskId ID úkolu.
	 */
	public function handleMarkDone($taskId)
	{
		$task = $this->model->getTasks()->find($taskId)->fetch();
		// ověření, zda je tento úkol uživateli skutečně přiřazen
		if ($task !== NULL && $task->user_id = $this->presenter->getUser()->getId()) {
			$this->model->getTasks()->where(array('id' => $taskId))->update(array('done' => 1));
			// přesměrování nebo invalidace snippetu
			if (!$this->presenter->isAjax()) {
				$this->presenter->redirect('this');
			} else {
				$this->invalidateControl();
			}
		}
	}

	/**
	 * Nastaví, zda se má zobrazovat sloupeček se seznamem úkolů.
	 * @param boolean $displayTaskList
	 */
	public function setDisplayTaskList($displayTaskList)
	{
		$this->displayTaskList = $displayTaskList;
	}

	/**
	 * Zjistí, zda se zobrazuje sloupeček se seznamem úkolů.
	 * @return boolean
	 */
	public function getDisplayTaskList()
	{
		return $this->displayTaskList;
	}

	/**
	 * Nastaví, zda se má zobrazovat sloupeček s uživatelem, kterému je úkol přiřazen.
	 * @param boolean $displayUser
	 */
	public function setDisplayUser($displayUser)
	{
		$this->displayUser = $displayUser;
	}

	/**
	 * Zjistí, zda se zobrazuje sloupeček s uživatelem.
	 * @return boolean
	 */
	public function getDisplayUser()
	{
		return $this->displayUser;
	}
}