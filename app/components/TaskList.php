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
	 * @param \Model $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}

	/**
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
		$this->template->render();
	}


	/**
	 * Signál, který označí zadaný úkol jako splněný.
	 * @param $taskId ID úkolu.
	 */
	public function handleMarkDone($taskId)
	{
		$this->model->getTasks()->where(array('id' => $taskId))->update(array('done' => 1));
		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		} else {
			$this->invalidateControl();
		}
	}

	/**
	 * @param boolean $displayTaskList
	 */
	public function setDisplayTaskList($displayTaskList)
	{
		$this->displayTaskList = $displayTaskList;
	}

	/**
	 * @return boolean
	 */
	public function getDisplayTaskList()
	{
		return $this->displayTaskList;
	}

	/**
	 * @param boolean $displayUser
	 */
	public function setDisplayUser($displayUser)
	{
		$this->displayUser = $displayUser;
	}

	/**
	 * @return boolean
	 */
	public function getDisplayUser()
	{
		return $this->displayUser;
	}
}