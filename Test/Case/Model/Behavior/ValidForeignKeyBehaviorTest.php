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
	protected $_model;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.valid_foreign_key.foreign_main',
		'plugin.valid_foreign_key.foreign_one',
		'plugin.valid_foreign_key.foreign_two',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_model = ClassRegistry::init('ForeignMain');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->_model);

		parent::tearDown();
	}

	/**
	 * Tests the default config (no config given)
	 *
	 * @return void
	 * @coversNothing
	 */
	public function testDefaultConfig() {
		$this->_loadBehavior();

		$settings = $this->_model->Behaviors->ValidForeignKey->settings['ForeignMain'];
		$expected = array(
			'autoValidate' => false,
			'errMsg' => 'The key/ID for %s must exist.',
			'exclude' => array(),
		);
		$this->assertSame($expected, $settings);
	}

	/**
	 * Tests overwriting the default config.
	 *
	 * @return void
	 * @covers ::setup
	 */
	public function testOverwritingConfig() {
		$this->_loadBehavior(
			array(
				'autoValidate' => true,
				'errMsg' => 'Error Message',
				'exclude' => array('some_field'),
			)
		);

		$settings = $this->_model->Behaviors->ValidForeignKey->settings['ForeignMain'];
		$expected = array(
			'autoValidate' => true,
			'errMsg' => 'Error Message',
			'exclude' => array('some_field'),
		)
		;
		$this->assertSame($expected, $settings);
	}

	/**
	 * Tests overwriting the default config using the model name.
	 *
	 * @return void
	 * @covers ::setup
	 */
	public function testOverwritingConfigWithModelName() {
		$this->_loadBehavior(
			array(
				'ForeignMain' => array(
					'autoValidate' => true,
					'errMsg' => 'Another Error Message',
					'exclude' => array('some_other_field'),
				)
			)
		);

		$settings = $this->_model->Behaviors->ValidForeignKey->settings['ForeignMain'];
		$expected = array(
			'ForeignMain' => array(
				'autoValidate' => true,
				'errMsg' => 'Another Error Message',
				'exclude' => array('some_other_field'),
			)
		)
		;
		$this->assertSame($expected, $settings);
	}

	/**
	 * Tests that beforeValidate calls validateAllForeignKeys when autoValidate = true.
	 *
	 * @return void
	 * @covers ::beforeValidate
	 */
	public function testAutoValidateCallsMethod() {
		$this->_loadBehavior(
			array(
				'autoValidate' => true,
			)
		);

		// Mock validateAllForeignKeys method
		// Expect it to be called once with the model as parameter.

		$this->markTestIncomplete(
			'This test has not been fully implemented yet.'
		);
	}

} 