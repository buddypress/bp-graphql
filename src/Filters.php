<?php

namespace BPGraphQL;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\Types as WPTypes;
use BPGraphQL\Types;

/**
 * Class Filters.
 *
 * @since   0.0.1-alpha
 * @package BPGraphQL
 */
class Filters {

	/**
	 * Filters the GraphQL root query fields, to add entry points for BuddyPress
	 *
	 * @param array $fields An array with fields.
	 *
	 * @access public
	 * @since 0.0.1-alpha
	 * @return mixed
	 */
	public static function add_fields( $fields ) {

		// Group component active?
		if ( bp_is_active( 'groups' ) ) {
			$fields['group'] = [
				'type'        => Types::group_type(),
				'description' => __( 'Group defined for BuddyPress', 'bp-graphql' ),
				'args'        => [
					'id' => WPTypes::non_null( WPTypes::id() ),
				],
				'resolve' => function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$group = groups_get_group( $args['id'] );

					if ( empty( $group->id ) ) {
						throw new UserError( sprintf( __( 'No group was found with the ID: %1$s', 'bp-graphql' ), $id ) );
					}

					return $group;
				},
			];
		}

		return $fields;

	}

}
