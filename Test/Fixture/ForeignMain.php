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

/**
 * ForeignMain table
 */
class ForeignMainFixture extends CakeTestFixture {

/**
 * Table schema
 *
 * 1. A pair of integer fields
 * 2. A pair of string fields
 * 3. A pair of boolean fields
 * 4. A pair of datetime fields
 * 5. A pair of float fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'foreign_one_id' => array('type' => 'integer', 'null' => true),
		'foreign_two_id' => array('type' => 'integer', 'null' => true),
		'another_foreign_one_id' => array('type' => 'integer', 'null' => true),
		'another_foreign_two_id' => array('type' => 'integer', 'null' => true),
	);

/**
 * Fixture records
 *
 * @var array
 */
	public $records = array(
		// Empty foreign keys.
		array(
			'id' => 1,
			'foreign_one_id' => null,
			'foreign_two_id' => null,
			'another_foreign_one_id' => null,
			'another_foreign_two_id' => null,
		),
		// Valid foreign k
		array(
			'id' => 2,
			'foreign_one_id' => 2,
			'foreign_two_id' => null,
			'another_foreign_one_id' => null,
			'another_foreign_two_id' => null,
		),
		// Empty foreign keys.
		array(
			'id' => 3,
			'foreign_one_id' => null,
			'foreign_two_id' => 20,
			'another_foreign_one_id' => null,
			'another_foreign_two_id' => null,
		),
		// Empty foreign keys.
		array(
			'id' => 4,
			'foreign_one_id' => null,
			'foreign_two_id' => null,
			'another_foreign_one_id' => 3,
			'another_foreign_two_id' => null,
		),
		// Empty foreign keys.
		array(
			'id' => 5,
			'foreign_one_id' => null,
			'foreign_two_id' => null,
			'another_foreign_one_id' => 30,
			'another_foreign_two_id' => null,
		),
		// Empty foreign keys.
		array(
			'id' => 6,
			'foreign_one_id' => 1,
			'foreign_two_id' => 10,
			'another_foreign_one_id' => 1,
			'another_foreign_two_id' => 00,
		),
	);

}
