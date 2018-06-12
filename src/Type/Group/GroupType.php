<?php

namespace BPGraphQL\Type\Group;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;
use WPGraphQL\Type\WPObjectType;
use WPGraphQL\Types;

/**
 * BuddyPress Group Type
 *
 * @since   0.0.1-alpha
 * @package BPGraphQL
 */
class GroupType extends WPObjectType {

	/**
	 * Holds the GroupType name
	 *
	 * @access private
	 * @since 0.0.1-alpha
	 * @var string $type_name
	 */
	private static $type_name;

	/**
	 * Holds the GroupType fields
	 *
	 * @access private
	 * @since 0.0.1-alpha
	 * @var array $fields
	 */
	private static $fields;

	/**
	 * GroupType constructor.
	 *
	 * @access public
	 * @since 0.0.1-alpha
	 * @return void
	 */
	public function __construct() {

		self::$type_name = 'Group';

		$config = [
			'name' => self::$type_name,
			'description' => __( 'Info about a BuddyPress group', 'bp-graphql' ),
			'fields' => function() {
				return self::fields();
			},
		];

		parent::__construct( $config );

	}

	/**
	 * Configure the fields.
	 *
	 * @access protected
	 * @since 0.0.1-alpha
	 * @return mixed|null
	 */
	protected static function fields() {

		if ( null === self::$fields ) {

			$fields = [
				'id' => [
					'type'        => Types::non_null( Types::id() ),
					'description' => __( 'The globally unique ID for the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return ( ! empty( $group->id ) ) ? $group->id : null;
					},
				],
				'groupId' => [
					'type'        => Types::non_null( Types::int() ),
					'description' => __( 'The id field that matches the BP_Groups_Group->id field.', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return ( ! empty( $group->id ) ) ? $group->id : null;
					},
				],
				'creatorId' => [
					'type'        => Types::user(),
					'description' => __( 'The creator of the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						$user_id = ( ! empty( $group->creator_id ) ) ? $group->creator_id : null;

						return ( ! empty( $user_id ) ) ? DataSource::resolve_user( $user_id ) : null;
					},
				],
				// 'admins' => [
				// 	'type'        => Types::list_of( Types::user() ),
				// 	'description' => __( 'The admins of the group', 'bp-graphql' ),
				// 	'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
				// 		$admins = [];

				// 		$admin_mods = groups_get_group_members( array(
				// 			'group_id'   => $group->id,
				// 			'group_role' => [ 'admin' ],
				// 		) );

				// 		foreach ( (array) $admin_mods['members'] as $user ) {
				// 			if ( ! empty( $user->is_admin ) ) {
				// 				return DataSource::resolve_user( $user->ID );
				// 			}
				// 		}

				// 		// return ( ! empty( $admins ) ) ? $admins : null;
				// 	},
				// ],
				// 'mods' => [
				// 	'type'        => Types::list_of( Types::user() ),
				// 	'description' => __( 'The mods of the group', 'bp-graphql' ),
				// 	'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
				// 		$admins = [];

				// 		$admin_mods = groups_get_group_members( array(
				// 			'group_id'   => $group->id,
				// 			'group_role' => [ 'mods' ],
				// 		) );

				// 		foreach ( (array) $admin_mods['members'] as $user ) {
				// 			if ( ! empty( $user->mods ) ) {
				// 				return DataSource::resolve_user( $user->ID );
				// 			}
				// 		}

				// 		// return ( ! empty( $admins ) ) ? $admins : null;
				// 	},
				// ],
				'name' => [
					'type'        => Types::string(),
					'description' => __( 'The name of the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return ( ! empty( $group->name ) ) ? $group->name : null;
					},
				],
				'slug' => [
					'type'        => Types::string(),
					'description' => __( 'The slug of the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return ( ! empty( $group->slug ) ) ? $group->slug : null;
					},
				],
				'description' => [
					'type'        => Types::string(),
					'description' => __( 'The description of the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return ( ! empty( $group->description ) ) ? $group->description : null;
					},
				],
				'link' => [
					'type'        => Types::string(),
					'description' => __( 'The link of the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						$link = bp_get_group_permalink( $group );

						return ( ! empty( $link ) ) ? $link : null;
					},
				],
				'enableForum' => [
					'type'        => Types::boolean(),
					'description' => __( 'Whether the group has a forum or not', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return $group->enable_forum;
					},
				],
				'totalMemberCount' => [
					'type'        => Types::int(),
					'description' => __( 'Count of all group members', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						$count = groups_get_groupmeta( $group->id, 'total_member_count' );

						return ( ! empty( $count ) ) ? $count : null;
					},
				],
				'lastActivity' => [
					'type'        => Types::string(),
					'description' => __( 'The date the group was last active', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						$date = Types::prepare_date_response( groups_get_groupmeta( $group->id, 'last_activity' ) );

						return ( ! empty( $date ) ) ? $date : null;
					},
				],
				'dateCreated' => [
					'type'        => Types::string(),
					'description' => __( 'The status of the group', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						$date = Types::prepare_date_response( $group->date_created );

						return ( ! empty( $date ) ) ? $date : null;
					},
				],
				'status' => [
					'type'        => Types::string(),
					'description' => __( 'The date the group was created', 'bp-graphql' ),
					'resolve'     => function( \BP_Groups_Group $group, $args, AppContext $context, ResolveInfo $info ) {
						return ( ! empty( $group->status ) ) ? $group->status : null;
					},
				],
			];

			self::$fields = self::prepare_fields( $fields, self::$type_name );

		}

		return ( ! empty( self::$fields ) ) ? self::$fields : null;

	}

}
