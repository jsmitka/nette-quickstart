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
	 * Vykreslí komponentu. Šablonou komponenty je TaskList.latte.
	 */
	public function render()
	{
		$this->template->setFile(__DIR__ . '/TaskList.latte');
		$this->template->tasks = $this->selection;
		$this->template->render();
	}
}