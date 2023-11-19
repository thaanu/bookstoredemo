<?php
namespace Heliumframework;
class Validate
{

	private $_passed 	= false,
			$_errors 	= array(),
			$_db 		= null;


	public function __construct()
	{
		// Create an instance of the database engine
		// $this->_db = MysqliDb::getInstance();
		// $this->_db = new \mysqli(_env('DB_HOST'), _env('DB_USER'), _env('DB_PASS'), _env('DB_NAME'));
	}

	// Check if the form is valid
	public function check( $source, $items = array() )
	{

		foreach( $items as $item => $rules ) {

			$formatedItem = str_replace('_', ' ', $item);
			$formatedItem = ucwords($formatedItem);

			// Set the label
			if( isset( $rules['label'] ) ) { $formatedItem = $rules['label']; }

			// Validate Item
			if( $item == 'file' && !empty($source[$item]) ) {
				
				// Check if the file is valid
				if( $this->_checkFileType($rules['allowed_types'], $source[$item]['tmp_name']) == false ) {
					$this->addError($item, "{$formatedItem} is invalid");
				}

			}

			foreach( $rules as $rule => $rule_value ) {

				@$value = trim($source[$item]);

				// Check for required flag and if the value is empty
				if( $rule === 'required' && empty($value) ) {
				// if( $rule === 'required' && empty($value) || (is_array($source[$item]) && empty($source[$item])) ) {

					// Check for an array
					if( is_array($source[$item]) )  {
						if(empty($source[$item]['name'])) {
							$this->addError( $item, "{$formatedItem} is required");
						}
					} 
					// If not an array
					else {
						$this->addError($item, "{$formatedItem} is required");
					}

				} else if( ! empty($value) ) {

					switch( $rule ) {

						case 'min' :
							if( strlen($value) < $rule_value ) {
								$this->addError($item, "{$formatedItem} must be minimum {$rule_value} characters");
							}
						break;

						case 'max' :
							if( strlen($value) > $rule_value ) {
								$this->addError($item, "{$formatedItem} must be maximum {$rule_value} characters");
							}
						break;

						case 'matches' :
							if( $value != $source[$rule_value] ) {
								$this->addError($item, "{$rule_value} must match {$item}");
							}
						break;

						case 'email_valid' :
							if (!filter_var($value, FILTER_VALIDATE_EMAIL) === true) {
								$this->addError($item, "Invalid {$formatedItem}");
							}
						break;

						// Pass the database table name
						// case 'unique' :
						// 	$this->_db->where($item, $value);
						// 	$check = $this->_db->get($rule_value);
						// 	if( !empty($check) ) {
						// 		$this->addError($item, "{$formatedItem} already exists");
						// 	}
						// break;

						/* Check for unique value and also check if ID is not same
						example:  array(
								'primary_key' => 'ID',
								'primary_key_value' => $id,
								'tablename' => 'users'
							)
						*/
						// case 'uniquebut' :
						// 	$this->_db->where($rule_value['primary_key'], $rule_value['primary_key_value'], '<>');
						// 	$this->_db->where($item, $value);
						// 	$check = $this->_db->get($rule_value['tablename']);
						// 	if( !empty($check) ) {
						// 		$this->addError($item, "{$formatedItem} already exists");
						// 	}
						// break;

						// Check for numeric value only
						case 'is_numeric' :
							if( !is_numeric($value) ) {
								$this->addError($item, "{$formatedItem} must be a number");
							}
						break;

						// Check if value is equal to required string length
						case 'length_equal' :						
							if( strlen($value) != $rule_value ) {
								$this->addError($item, "{$formatedItem} must be {$rule_value} characters");
							}
						break;

						// Check if value is greater than required string length
						case 'length_max' :						
							if( strlen($value) < $rule_value ) {
								$this->addError($item, "{$formatedItem} must be more than {$rule_value} characters");
							}
						break;

						// Check if value is greater than required string length
						case 'length_min' :						
							if( strlen($value) > $rule_value ) {
								$this->addError($item, "{$formatedItem} must be less than {$rule_value} characters");
							}
						break;

						// Match pattern
						case 'match' :
							if ( preg_match( $rule_value, $value ) == false ) {
								$this->addError($item, "{$formatedItem} is invalid");
							}
						break;

					}

				}

			}

		}

		if( empty($this->_errors) ) {
			$this->_passed = true;
		}

		return $this;

	}

	
	/**
	 * File Type Validation
	 *
	 * @param array $allowedTypes
	 * @param string $filename
	 * @return boolean
	 */
	private function _checkFileType( $allowedTypes = array(), $filename )
	{
		if( @in_array(mime_content_type($filename), $allowedTypes) ) {
			return true;
		}
		return false;
	}

	// Add error to error's array
	private function addError( $field, $error )
	{
		// $this->_errors[]['field'] = $field;
		// $this->_errors[]['message'] = $error;
		$this->_errors[] = array( 'field' => $field, 'message' => $error );
	}

	// Get the error's array set
	public function errors()
	{
		return $this->_errors;
	}

	// Check if the form validtion passed
	public function passed() 
	{
		return $this->_passed;
	}

}