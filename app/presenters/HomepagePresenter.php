<?php

/**
 * Presenter pro úvodní stránku. Obsahuje seznam úkolů, které jsou uživateli přiřazeny.
 */
class HomepagePresenter extends BasePresenter
{

	/**
	 * Výchozí view. Zobrazí všechny nesplněné úkoly.
	 */
	public function renderDefault()
	{
		$this->template->tasks = $this->model->getTasks()
			->where(array('done' => false))->order('created ASC');
	}

}
