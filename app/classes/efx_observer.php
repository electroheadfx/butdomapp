<?php

class Efx_Observer extends \Orm\Model {

	public static function set_fields($obj, $fieldset = null)
	{
		static $_generated = array();
		static $_tabular_rows = array();

		$class = is_object($obj) ? get_class($obj) : $obj;
		if (is_null($fieldset))
		{
			$fieldset = \Fieldset::instance($class);
			if ( ! $fieldset)
			{
				$fieldset = \Fieldset::forge($class);
			}
		}

		// is our parent fieldset a tabular form set?
		$tabular_form = is_object($fieldset->parent()) ? $fieldset->parent()->get_tabular_form() : false;

		// don't cache tabular form fieldsets
		if ( ! $tabular_form)
		{
			! array_key_exists($class, $_generated) and $_generated[$class] = array();
			if (in_array($fieldset, $_generated[$class], true))
			{
				return $fieldset;
			}
			$_generated[$class][] = $fieldset;
		}

		$primary_keys = is_object($obj) ? $obj->primary_key() : $class::primary_key();
		$primary_key = count($primary_keys) === 1 ? reset($primary_keys) : false;
		$properties = is_object($obj) ? $obj->properties(true) : $class::properties(true);

		if ($tabular_form and $primary_key and ! is_object($obj))
		{
			isset($_tabular_rows[$class]) or $_tabular_rows[$class] = 0;
		}

		foreach ($properties as $p => $settings)
		{
			if (\Arr::get($settings, 'skip', in_array($p, $primary_keys)))
			{
				continue;
			}

			if (isset($settings['form']['options']))
			{
				foreach ($settings['form']['options'] as $key => $value)
				{
					is_array($value) or $settings['form']['options'][$key] = \Lang::get($value, array(), false) ?: $value;
				}
			}

			// field attributes can be passed in form key
			$attributes = isset($settings['form']) ? $settings['form'] : array();
			// label is either set in property setting, as part of form attributes or defaults to fieldname
			$label = isset($settings['label']) ? $settings['label'] : (isset($attributes['label']) ? $attributes['label'] : $p);
			$label = \Lang::get($label, array(), false) ?: $label;

			// change the fieldname and label for tabular form fieldset children
			if ($tabular_form and $primary_key)
			{
				if (is_object($obj))
				{
					$p = $tabular_form.'['.$obj->{$primary_key}.']['.$p.']';
				}
				else
				{
					$p = $tabular_form.'_new['.$_tabular_rows[$class].']['.$p.']';
				}
				$label = '';
			}

			// create the field and add validation rules
			$field = $fieldset->add($p, $label, $attributes);
			if ( ! empty($settings['validation']))
			{
				foreach ($settings['validation'] as $rule => $args)
				{
					if (is_int($rule) and is_string($args))
					{
						$args = array($args);
					}
					else
					{
						array_unshift($args, $rule);
					}

					call_fuel_func_array(array($field, 'add_rule'), $args);
				}
			}
		}

		// increase the row counter for tabular row fieldsets
		if ($tabular_form and $primary_key and ! is_object($obj))
		{
			$_tabular_rows[$class]++;
		}

		return $fieldset;
	}

	/**
	 * Validate the model
	 *
	 * @param   Model	the model object to validate
	 *
	 * @throws  ValidationFailed
	 */
	public function validate(Model $obj)
	{
		$fieldset = static::set_fields($obj);
		$val = $fieldset->validation();

		// only allow partial validation on updates, specify the fields for updates to allow null
		$allow_partial = $obj->is_new() ? false : array();

		$input = array();
		foreach (array_keys($obj->properties(true)) as $p)
		{
			if ( ! in_array($p, $obj->primary_key()) and $obj->is_changed($p))
			{
				$input[$p] = $obj->{$p};
				is_array($allow_partial) and $allow_partial[] = $p;
			}
		}

		if ( ! empty($input) and $val->run($input, $allow_partial, array($obj)) === false)
		{
			throw new ValidationFailed($val->show_errors(), 0, null, $fieldset);
		}
		else
		{
			foreach ($input as $k => $v)
			{
				if( ! in_array($k, array_keys($obj->properties())))
				{
					continue;
				}
				$obj->{$k} = $val->validated($k);
			}
		}
	}

}