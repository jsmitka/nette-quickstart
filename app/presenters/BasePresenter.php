<?php

/**
 * Základní třída pro všechny presentery aplikace.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var Model */
	protected $model;

	/**
	 * Inicializace presenteru. Zajišťuje získání instance modelu.
	 */
	public function startup()
	{
		parent::startup();
		$this->model = $this->getService('model');
	}


	/**
	 * Před vykreslením. Zajišťuje získání seznamů úkolů.
	 */
	public function beforeRender()
	{
		$this->template->taskLists = $this->model->getTaskLists()->order('title ASC');
	}
}
