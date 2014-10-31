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

App::uses('ModelBehavior', 'Model');

/**
 * A CakePHP behavior to add data validation for foreign keys
 *
 * Inspired by dogmatic69's and iamFIREcracker's solutions.
 * But instead of looping through all bound associations,
 * you can selectively validate one foreign key with this behavior.
 *
 * Provides two ways of validation:
 * 1. Validate all foreign keys
 * 2. Validate a certain foreign key
 *
 * 1. This needs to be set through the behavior settings
 * once per model (or only once app wide in AppModel).
 *
 * <code>
 * public $actsAs = array(
 *     'ValidForeignKeyBehavior.ValidForeignKey' => array(
 *         'autoValidate' => true,
 *         'errMsg' => '',
 *         'exclude' => array()
 *     )
 * );
 * </code>
 *
 * Keep in mind that this could be overly costly because
 * the keys will be checked every time the application
 * saves or updates records.
 * Also the same behavior can be achieved by using foreign key
 * constraints in your RDBMS.
 *
 * 2. This allows to only validate foreign keys selectively and
 * must be added just like a normal data validation rule.
 *
 * <code>
 * public $validate = array(
 *     'product_id' => array(
 *         'validForeignKey' => array(
 *         'rule' => array('validForeignKey', true),
 *         'message' => 'Product ID must exist.'
 *     ),
 * ));
 * </code>
 *
 * @link https://github.com/infinitas/infinitas/blob/v0.9b/Core/Libs/Model/Behavior/ValidationBehavior.php#L147-L185 dogmatic69's solution.
 * @link https://gist.github.com/iamFIREcracker/1307191 iamFIREcracker's solution.
 */
class ValidForeignKeyBehavior extends ModelBehavior {

	/**
	 * Default settings for a model that has this behavior attached
	 *
	 * 'autoValidate' = Check all foreign keys automatically.
	 * Defaults to false, use the validForeignKey data validation rule instead.
	 * 'errMsg' = The error message to display.
	 * 'exclude = Fields you want to exclude from the validation.
	 *
	 * @var array
	 */
	protected $_defaults = array(
		'autoValidate' => false,
		'errMsg' => 'The key/ID for %s must exist.',
		'exclude' => array(),
	);

	/**
	 * Setup the behavior
	 *
	 * Checks if the configuration settings are set in the model,
	 * merges them with the the defaults.
	 *
	 * @param Model $model Model using this behavior
	 * @param array $config Configuration settings for $model
	 * @return void
	 */
	public function setup(Model $model, $config = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->_defaults;
		}
		$this->settings[$model->alias] = array_merge(
			$this->settings[$model->alias], (array)$config);
	}

	/**
	 * Adds the validateAllForeignKeys data validation rule dynamically
	 *
	 * If 'autoValidate' is set to true
	 *
	 * @param Model $model Model using this behavior
	 * @param array $options Options passed from Model::save() (unused).
	 * @return boolean True if validate operation should continue, false to abort
	 */
	public function beforeValidate(Model $model, $options = array()) {
		if ($this->settings[$model->alias]['autoValidate']) {
			$this->validateAllForeignKeys($model);
		}

		return parent::beforeValidate($model, $options);
	}

	/**
	 * Validate all foreign keys of the model.
	 *
	 * Only checks the keys if they are present in the data of the model array.
	 *
	 * @param Model $model The model to test.
	 * @return boolean True if valid, else false.
	 * @todo Write a test where we have only one foreign key field in $model->data with a valid key
	 * @todo Write a test where we have only one foreign key field in $model->data with an invalid key
	 * @todo Write a test where we have more than one foreign key field in $model->data with a valid key
	 * @todo Write a test where we have more than one foreign key field in $model->data with an invalid key
	 */
	public function validateAllForeignKeys(Model $model) {
		// Get the aliases of all associations as array('AliasName) => 'alias_id', ...)
		$returnForeignKey = create_function('$belongToAssociation', 'return $belongToAssociation["foreignKey"];');
		$aliases = array_map($returnForeignKey, $model->belongsTo);

		// Check the foreign keys of all associations
		foreach ($aliases as $alias => $foreignKeyField) {
			// Skip excluded fields
			if (in_array($foreignKeyField, $this->settings[$model->alias]['exclude'])) {
				continue;
			}

			// But only if we have data to validate
			if (isset($model->data[$model->alias]) &&
				array_key_exists($foreignKeyField, $model->data[$model->alias])) {
				$assocModel = $model->{$alias};
				$foreignKeyValue = $model->data[$model->alias][$foreignKeyField];

				// Since we don't know better, allow null
				$allowNull = true;

				$model->validator()->add($foreignKeyField, 'validateAllForeignKeys', array(
					'rule' => array(
						'validForeignKey',
						$allowNull,
						$assocModel->name,
						$assocModel->primaryKey,
						$foreignKeyValue
					),
					'message' => sprintf(
						$this->settings[$model->alias]['errMsg'],
						Inflector::humanize(Inflector::underscore($alias))
					),
				));
			}
		}
	}

	/**
	 * Validate if a key exists in the associated table.
	 *
	 * @param Model $model The model to validate.
	 * @param array $data The key/value pair to validate.
	 * @param boolean $allowNull If null is allowed (optional).
	 * @param null|string $assocModelName The name of the associated model (optional).
	 * @param string $assocFieldName The name of the associated field (optional).
	 * @param null|string $assocFieldValue The value to validate instead (optional).
	 * @return bool True if valid, else false.
	 * @throws InvalidArgumentException If an invalid amount of arguments was supplied.
	 * @todo Explain it's intentional that we don't introspect the model under test, as we do in validateAllForeignKeys.
	 */
	public function validForeignKey(Model $model, $data, $allowNull = false,
									$assocModelName = null, $assocFieldName = 'id',
									$assocFieldValue = null) {
		$fieldValueGiven = false;

		// Depending on how many parameters are configured/passed
		switch (func_num_args()) {
			case 3:
				// No additional parameter given
				$allowNull = false;
				$assocModelName = null;
				$assocFieldName = null;
				break;
			case 4:
				// $allowNull given
				$assocModelName = null;
				$assocFieldName = null;
				break;
			case 5:
				// $allowNull, $assocModelName given
				$assocFieldName = null;
				break;
			case 6:
				// $allowNull, $assocModelName, $assocFieldName given
				break;
			case 7:
				// $allowNull, $assocModelName, $assocFieldName, $assocFieldValue given
				$fieldValueGiven = true;
				break;
			default:
				throw new InvalidArgumentException('Invalid amount of arguments to validForeignKey().');
		}

		// If no field value was given, get it from $data array
		if (!$fieldValueGiven) {
			reset($data);
			$assocFieldValue = current($data);
		}
		// Treat null values depending on $allowNull
		if ($assocFieldValue === null) {
			if ($allowNull === true) {
				return true;
			}

			return false;
		}
		// Workaround datasources which allow false as key like ArraySource,
		// would be converted to "" otherwise
		if ($assocFieldValue === false) {
			$assocFieldValue = 0;
		}

		if ($assocModelName === null) {
			$assocModelName = key($data);
			$assocModelName = substr($assocModelName, 0, -3);
			$assocModelName = Inflector::camelize($assocModelName);
		}
		if ($assocFieldName === null) {
			$assocFieldName = 'id';
		}
		$foreignModel = ClassRegistry::init($assocModelName);

		$found = $foreignModel->find('count',
			array(
				'conditions' => array($assocFieldName => $assocFieldValue),
				'recursive' => -1
			)
		);
		return ($found > 0) ? true : false;
	}
} 