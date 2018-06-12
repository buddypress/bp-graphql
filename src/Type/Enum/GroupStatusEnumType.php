<?php

namespace BPGraphQL\Type\Enum;

use WPGraphQL\Type\WPEnumType;

/**
 * Class GroupStatusEnum
 *
 * This defines an EnumType with allowed post stati that are registered to WordPress.
 *
 * @since   0.0.1-alpha
 * @package BPGraphQL
 */
class GroupStatusEnum extends WPEnumType {

	/**
	 * This holds the enum values array
	 *
	 * @access private
	 * @since 0.0.1-alpha
	 * @var array $values
	 */
	private static $values;

	/**
	 * GroupStatusEnum constructor.
	 *
	 * @access public
	 * @since 0.0.1-alpha
	 * @return void
	 */
	public function __construct() {
		$config = array(
			'name'        => 'GroupStatusEnum',
			'description' => __( 'The status of the group.', 'bp-graphql' ),
			'values'      => self::values(),
		);

		parent::__construct( $config );
	}

	/**
	 * Values.
	 *
	 * @access private
	 * @since 0.0.1-alpha
	 * @return array
	 */
	private static function values() {

		/**
		 * Set the default, if no values are built dynamically
		 */
		self::$values = [
			'value' => 'public',
		];

		/**
		 * Get the dynamic list of group status
		 */
		$group_status = [ 'public', 'private', 'hidden' ];

		/**
		 * If there are $post_stati, create the $values based on them
		 */
		if ( ! empty( $group_status ) && is_array( $group_status ) ) {

			/**
			 * Reset the array
			 */
			self::$values = [];

			/**
			 * Loop through the group status
			 */
			foreach ( $group_status as $status ) {

				self::$values[ $status ] = array(
					'description' => sprintf( __( 'Group with the %1$s status', 'bp-graphql' ), $status ),
					'value'       => $status,
				);
			}
		}

		/**
		 * Return the $values
		 */
		return self::$values;

	}

}
