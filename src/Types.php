<?php

namespace BPGraphQL;

use BPGraphQL\Type\Group\GroupType;

/**
 * Class Types - Acts as a registry and factory for Types.
 *
 * @since   0.0.1-alpha
 * @package BPGraphQL
 */
class Types {

	/**
	 * Group type.
	 *
	 * @access private
	 * @since 0.0.1-alpha
	 * @var string
	 */
	private static $group_type;

	/**
	 * This returns the definition for the GroupType
	 *
	 * @access public
	 * @since  0.0.1-alpha
	 * @return GroupType object
	 */
	public static function group_type() {
		return self::$group_type ?: ( self::$group_type = new GroupType() );
	}

}
