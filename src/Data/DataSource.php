<?php

namespace BPGraphQL\Data;

use GraphQL\Error\UserError;

/**
 * Class DataSource
 *
 * @since   0.0.1-alpha
 * @package BPGraphQL\Data
 */
class DataSource {

	/**
	 * Retrieves a BP_Groups_Group object for the id that gets passed.
	 *
	 * @throws UserError Sends an error to the user.
	 *
	 * @param int $id ID of the group we want to get the object for.
	 *
	 * @access public
	 * @since  0.0.1-alpha
	 * @return \BP_Groups_Group object
	 */
	public static function resolve_group( $id ) {

		if ( ! bp_is_active( 'groups' ) ) {
			throw new UserError( __( 'The Groups component is not active.', 'bp-graphql' ) );
		}

		$group = groups_get_group( $id );

		if ( empty( $group->id ) ) {
			throw new UserError( sprintf( __( 'No group was found with the ID: %d', 'bp-graphql' ), absint( $id ) ) );
		}

		return $group;

	}

}
