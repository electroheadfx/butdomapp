<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.7
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

	// the active pagination template
	'active'                      => 'bootstrap3',

	// Twitter bootstrap 3.x template
	'bootstrap3'                   => array(
		'wrapper'                 => "<div class=\"pagination\">\n\t<ul class='pagination' >{pagination}\n\t</ul>\n</div>\n",

		'first'                   => "\n\t\t<li>{link}</li>",
		'first-marker'            => "<span class='glyphicon glyphicon-step-backward'></span>",
		'first-link'              => "<a href=\"{uri}\">{page}</a>",

		'first-inactive'          => "",
		'first-inactive-link'     => "",

		'previous'                => "\n\t\t<li>{link}</li>",
		'previous-marker'         => "<span class='glyphicon glyphicon-chevron-left'></span>",
		'previous-link'           => "<a href=\"{uri}\" rel=\"prev\">{page}</a>",

		'previous-inactive'       => "\n\t\t<li class=\"disabled\">{link}</li>",
		'previous-inactive-link'  => "<a href=\"#\" rel=\"prev\">{page}</a>",

		'regular'                 => "\n\t\t<li>{link}</li>",
		'regular-link'            => "<a href=\"{uri}\">{page}</a>",

		'active'                  => "\n\t\t<li class=\"active\">{link}</li>",
		'active-link'             => "<a href=\"#\">{page}</a>",

		'next'                    => "\n\t\t<li>{link}</li>",
		'next-marker'             => "<span class='glyphicon glyphicon-chevron-right'></span>",
		'next-link'               => "<a href=\"{uri}\" rel=\"next\">{page}</a>",

		'next-inactive'           => "\n\t\t<li class=\"disabled\">{link}</li>",
		'next-inactive-link'      => "<a href=\"#\" rel=\"next\">{page}</a>",

		'last'                    => "\n\t\t<li>{link}</li>",
		'last-marker'             => "<span class='glyphicon glyphicon-step-forward'></span>",
		'last-link'               => "<a href=\"{uri}\">{page}</a>",

		'last-inactive'           => "",
		'last-inactive-link'      => "",
	),

);
