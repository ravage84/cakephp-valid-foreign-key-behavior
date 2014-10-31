<?php
/**
 * ValidForeignKey Behavior
 *
 * Licensed under The MIT License.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Marc Würth
 * @author Marc Würth <ravage@bluewin.ch>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link https://github.com/ravage84/ValidForeignKeyBehavior
 */

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