<?php

namespace BPGraphQL;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\Types as WPTypes;
use BPGraphQL\Types;
use BPGraphQL\Data\DataSource;

/**
 * Class Filters.
 *
 * @since   0.0.1-alpha
 * @package BPGraphQL
 */
class Filters {

	/**
	 * Filters the WPGraphQL root query fields and introduce entry points for BuddyPress
	 *
	 * @param array $fields An array with fields.
	 *
	 * @access public
	 * @since 0.0.1-alpha
	 * @return array
	 */
	public static function add_fields( $fields ) {

		$fields['group'] = [
			'type'        => Types::group_type(),
			'description' => __( 'Group defined for BuddyPress', 'bp-graphql' ),
			'args'        => [
				'id' => WPTypes::non_null( WPTypes::id() ),
			],
			'resolve' => function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
				return DataSource::resolve_group( $args['id'] );
			},
		];

		return $fields;

	}

}
