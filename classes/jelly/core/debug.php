<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Jelly debug class
 *
 * @package    Jelly
 * @category   Base
 * @author     Miodrag Tokić
 * @copyright  (c) 2010-2011 Jonathan Geiger
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Jelly_Core_Debug {

	/**
	 * Returns debug informations about a field
	 *
	 * @param   Jelly_Field
	 * @return  string
	 */
	public static function field(Jelly_Field $field)
	{
		$info = array(
			'<strong>name</strong>: '.Jelly_Debug::value($field->name),
			'<strong>model</strong>: '.Jelly_Debug::value($field->model),
			'<strong>column</strong>: '.Jelly_Debug::value($field->column),
			'<strong>label</strong>: '.Jelly_Debug::value($field->label),
			'<strong>unique</strong>: '.Jelly_Debug::value($field->unique),
			'<strong>primary</strong>: '.Jelly_Debug::value($field->primary),
			'<strong>in_db</strong>: '.Jelly_Debug::value($field->in_db),
			'<strong>default</strong>: '.Jelly_Debug::value($field->default),
			'<strong>convert_empty</strong>: '.Jelly_Debug::value($field->convert_empty),
			'<strong>empty_value</strong>: '.Jelly_Debug::value($field->empty_value),
			'<strong>allow_null</strong>: '.Jelly_Debug::value($field->allow_null),
		);

		return '<pre>'.implode("\n", $info).'</pre>';
	}

	/**
	 * Returns dump of model's values
	 *
	 * @param   Jelly_Model
	 * @return  string
	 */
	public static function model(Jelly_Model $model)
	{
		$fields = $model->meta()->fields();

		$output = array();

		foreach ($fields as $field)
		{
			// Get field type from the class name
			$type = substr(get_class($field), strlen(Jelly::field_prefix()));

			// Get field name
			$name = $field->name;

			// Was field value changed?
			$changed = ($model->changed($name)) ? '*' : '';

			// Get field value
			$value = Jelly_Debug::value($model->$name);

			$output[] = '<strong>'.$name.'</strong> <small>('.$type.')</small>'.$changed.': '.$value;
		}

		return '<pre>'.implode("\n", $output).'</pre>';
	}

	/**
	 * Returns human readable representation of a value
	 *
	 * @param   mixed
	 * @return  string
	 */
	public static function value($value)
	{
		// Default color
		$color = '#000';

		if ($value === NULL)
		{
			$value = 'NULL';
			$color = '#3465A4';
		}
		elseif (is_bool($value))
		{
			$value = $value ? 'TRUE' : 'FALSE';
			$color = '#75507B';
		}
		elseif (is_string($value))
		{
			$color = '#CC0000';
		}
		elseif (is_int($value) OR is_float($value))
		{
			$color = '#4E9A06';
		}
		elseif ($value instanceof Jelly_Model)
		{
			$value = '[relation: model]';
			$color = '#A33B33';
		}
		elseif ($value instanceof Jelly_Collection)
		{
			$value = '[relation: collection]';
			$color = '#A33B33';
		}

		// Escape the value
		$value = htmlspecialchars($value, ENT_NOQUOTES, Kohana::$charset);

		return '<span style="color: '.$color.'">'.$value.'</span>';
	}
}
