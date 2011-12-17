<?php

/**
 * Presenter pro úvodní stránku. Obsahuje seznam úkolů, které jsou uživateli přiřazeny.
 */
class HomepagePresenter extends BasePresenter
{

	public function createComponentIncompleteTasks()
	{
		return new TaskList($this->model->getTasks()
			->where(array('done' => false))->order('created ASC'));
	}

}
