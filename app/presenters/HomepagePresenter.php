<?php

/**
 * Presenter pro úvodní stránku. Obsahuje seznam úkolů, které jsou uživateli přiřazeny.
 */
class HomepagePresenter extends BasePresenter
{
	/** @var Model */
	private $model;

	public function startup()
	{
		parent::startup();
		$this->model = $this->getService('model');
	}


	public function renderDefault()
	{
		$this->template->list = $this->model->getTasks()
			->where(array('done' => 0))->order('created ASC');
	}

}
