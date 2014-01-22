<?php
/**
 * A CakePHP behavior to add data validation for foreign keys
 *
 * Inspired by dogmatic69's and iamFIREcracker's solutions.
 * But instead of looping through all bound associations,
 * you can selectively validate one foreign key with this behavior.
 *
 * @author Marc Würth
 * @license http://www.opensource.org/licenses/mit-license.php) MIT License
 *
 * @link https://github.com/infinitas/infinitas/blob/dev/Core/Libs/Model/Behavior/ValidationBehavior.php#L101-L147 dogmatic69's solution.
 * @link https://gist.github.com/iamFIREcracker/1307191 iamFIREcracker's solution.
 **/

App::uses('ModelBehavior', 'Model');

/**
 * A CakePHP behavior to add data validation for foreign keys
 */
class ValidForeignKeyBehavior extends ModelBehavior{

	/**
	 * Validate if a key exists in the associated table.
	 *
	 * @param Model $Model The model to validate.
	 * @param array $data The key/value pair to validate.
	 * @param null|string $associatedModelName
	 * @param string $associatedFieldName
	 * @return bool True if valid, else false.
	 */
	public function validForeignKey(Model $Model, $data, $associatedModelName = null, $associatedFieldName = 'id') {
		switch (func_num_args()) {
			case 3:
				$associatedModelName = null;
				$associatedFieldName = null;
				break;
			case 4:
				$associatedFieldName = null;
				break;
			case 5:
				break;
			default:
				throw new InvalidArgumentException('Invalid amount of arguments to validForeignKey().');
		}

		reset($data);
		$associatedFieldValue = current($data);

		if ($associatedModelName === null) {
			$associatedModelName = key($data);
			$associatedModelName = substr($associatedModelName, 0, -3);
			$associatedModelName = Inflector::camelize($associatedModelName);
		}
		if ($associatedFieldName === null) {
			$associatedFieldName = 'id';
		}
		$foreignModel = ClassRegistry::init($associatedModelName);

		$found = $foreignModel->find('count',
			array('conditions' => array($associatedFieldName => $associatedFieldValue))
		);
		return ($found > 0) ? true : false;
	}
} 