<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * Our forms do NOT generate the Open and Close tags for the form !
 */


return array(
	'prep_value'            => true,
	'auto_id'               => true,
	'auto_id_prefix'        => '',
	'form_method'           => 'post',
	'form_template'  		=> "\n\t\t<ul>\n{fields}\n",
	'fieldset_template'     => "\n\t\t<tr><td colspan=\"2\">{open}<table>\n{fields}</table></td></tr>\n\t\t{close}\n",
	'field_template'        => "\t\t<li>\n\t\t\t{label}\n\t\t\t{field} <span class='error'>{required}</span> {description} {error_msg}\n\t\t</li>",
	'multi_field_template'  => "\t\t<tr>\n\t\t\t<td class=\"{error_class}\">{group_label}{required}</td>\n\t\t\t<td class=\"{error_class}\">{fields}\n\t\t\t\t{field} {label}<br />\n{fields}{description}\t\t\t{error_msg}\n\t\t\t</td>\n\t\t</tr>\n",
	'error_template'        => '<span>{error_msg}</span>',
	'required_mark'         => '*',
	'inline_errors'         => false,
	'error_class'           => 'validation_error',
	'help_text'             => '<span>{help_text}</span>',
);
