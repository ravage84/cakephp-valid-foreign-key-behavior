<?php
/**
 * AllTests file
 */

/**
 * AllTests class
 *
 * This test group will run all tests.
 */
class AllTests extends PHPUnit_Framework_TestSuite {

	/**
	 * Defines tests for this suite
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All ValidForeignKeyBehavior tests');

		$path = dirname(__FILE__);
		$suite->addTestDirectory($path . DS . 'Model' . DS . 'Behavior');

		return $suite;
	}
}