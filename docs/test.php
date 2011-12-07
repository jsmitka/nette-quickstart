<?php

/**
 * @property int $property
 */
class PropertyTest extends \Nette\Object {
	private $property;

	public function getProperty() {
		return $this->property;
	}
}

$test = new PropertyTest();
echo $test->property;