<?php

App::uses('ValidForeignKeyBehavior', 'ValidForeignKey.Model/Behavior');

/**
 * Class ValidForeignKeyBehaviorTest
 */
class ValidForeignKeyBehaviorTest {

	/**
	 * Model under test
	 *
	 * @var
	 */
	public $Model;

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array();

	/**
	 * setUp method
	 *
	 * @return void
	 * @todo Choose two associated tables from the core fixtures to use or create own
	 */
	public function setUp() {
		parent::setUp();
		//$this->Model = ClassRegistry::init('ValidForeignKeyBehaviorModel');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Model);

		parent::tearDown();
	}
} 