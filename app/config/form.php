<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */


return array(
	// regular form definitions
	'prep_value'                 => true,
	'auto_id'                    => true,
	'auto_id_prefix'             => 'form_',
	'form_method'                => 'post',

	// 'form_template'              => "\n\t\t{open}\n\t\t<table>\n{fields}\n\t\t</table>\n\t\t{close}\n",
	// 'field_template'             => "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{field} <span>{description}</span> {error_msg}</td>\n\t\t</tr>\n",
	// 'multi_field_template'       => "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{group_label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{fields}\n\t\t\t\t{field} {label}<br />\n{fields}<span>{description}</span>\t\t\t{error_msg}\n\t\t\t</td>\n\t\t</tr>\n",
	// 'error_template'             => '<span>{error_msg}</span>',

	'form_template' 				=> "\n\t\t{open}\n\t\t\n{fields}\n\t\t\n\t\t{close}\n",
	'fieldset_template' 			=> "\n\t\t{open}\n{fields}\n\t\t{close}\n",
	'field_template' 				=> "\t\t<div class=\"control-group\">\n\t\t\t<div class=\"control-label\">{label}{required}</div>\n\t\t\t<div class=\"controls\">{field} {description} {error_msg}</div></div>\n\t\t\n",
	'multi_field_template' 			=> "\t\t<div class=\"control-group\">\n\t\t\t<div class=\"control-label\">{group_label}{required}</div>\n\t\t\t<div class=\"{error_class}\">{fields}\n\t\t\t\t{field} {label}<br />\n<div class=\"controls\">{fields}</div>{description}\t\t\t{error_msg}\n\t\t\t\n\t\t\n",
	'error_template' 				=> '<div style="margin-top:10px;" ><span class="label label-danger">{error_msg}</span></div>',

	'group_label'	             => '<span>{label}</span>',
	'required_mark'              => '*',
	'inline_errors'              => true,
	'error_class'                => null,
	'label_class'                => null,

	// tabular form definitions
	'tabular_form_template'      => "<table>{fields}</table>\n",
	'tabular_field_template'     => "{field}",
	'tabular_row_template'       => "<tr>{fields}</tr>\n",
	'tabular_row_field_template' => "\t\t\t<td>{label}{required}&nbsp;{field} {error_msg}</td>\n",
	'tabular_delete_label'       => "Delete?",
);
