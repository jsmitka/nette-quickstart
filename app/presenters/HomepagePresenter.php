<?php

/**
 * Presenter pro úvodní stránku. Obsahuje seznam úkolů, které jsou uživateli přiřazeny.
 */
class HomepagePresenter extends BasePresenter
{
	/** @var Nette\Database\Table\Selection */
	private $tasks;

	public function startup()
	{
		parent::startup();
		$this->tasks = $this->getService('model')->getTasks();
	}


	public function renderDefault()
	{
		$this->template->list = $this->tasks->order('created ASC');
	}

}
