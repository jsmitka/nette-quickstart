<?php

/**
 * Presenter pro úvodní stránku. Obsahuje seznam úkolů, které jsou uživateli přiřazeny.
 */
class HomepagePresenter extends SecuredPresenter
{

	public function createComponentIncompleteTasks()
	{
		$tasks = $this->model->getTasks()->where(array('done' => false))->order('created ASC');
		$taskList = new TaskList($tasks, $this->model);
		$taskList->setDisplayTaskList(TRUE);
		return $taskList;
	}


	public function createComponentUserTasks()
	{
		$tasks = $this->model->getTasks()->where(array(
			'done' => false, 'user_id' => $this->getUser()->getId()
		));
		$taskList = new TaskList($tasks, $this->model);
		$taskList->setDisplayTaskList(TRUE);
		$taskList->setDisplayUser(FALSE);
		return $taskList;
	}

}
