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
 * Foreign table two
 */
class ForeignTwoFixture extends CakeTestFixture {

/**
 * Table schema
 *
 * Just needs an ID.
 *
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'length' => 10, 'null' => true),
	);

	/**
	 * Fixture records
	 *
	 * Does not have one overlapping record with the other foreign table.
	 *
	 * @var array
	 */
	public $records = array(
		array(
			'id' => 10,
			'name' => 'Ten',
		),
		array(
			'id' => 20,
			'name' => 'Twenty',
		),
		array(
			'id' => 30,
			'name' => 'Thirty',
		),
	);

}
