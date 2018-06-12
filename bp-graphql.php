<?php
/**
 * BP GraphQL
 *
 * @package      BPGraphQL
 * @author       The BuddyPress Community
 * @copyright    2018 BuddyPress
 * @license      GPLv3
 *
 * @wordpress-plugin
 * Plugin Name:       BP GraphQL
 * Plugin URI:        https://github.com/buddypress/bp-graphql
 * Description:       GraphQL API for BuddyPress
 * Version:           0.0.1-alpha
 * Author:            The BuddyPress Community
 * Author URI:        https://buddypress.org/
 * Text Domain:       bp-graphql
 * Domain Path:       /languages/
 * Requires PHP:      5.5
 * Requires WP:       4.9
 * Tested up to:      5.0-alpha-43320
 * GitHub Plugin URI: https://github.com/buddypress/bp-graphql
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BPGraphQL' ) ) :

	/**
	 * This is the one true BPGraphQL class
	 */
	final class BPGraphQL {

		/**
		 * Stores the instance of the BPGraphQL class
		 *
		 * @access private
		 * @since  0.0.1-alpha
		 * @var BPGraphQL The one true BPGraphQL
		 */
		private static $instance;

		/**
		 * The instance of the BPGraphQL object
		 *
		 * @access public
		 * @since  0.0.1-alpha
		 * @return object|BPGraphQL - The one true BPGraphQL
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( is_a( self::$instance, 'BPGraphQL' ) ) ) {
				self::$instance = new BPGraphQL();
				self::$instance->setup_constants();
				self::$instance->dependencies();
				self::$instance->includes();
				self::$instance->actions();
				self::$instance->filters();
			}

			/**
			 * Return the BPGraphQL Instance
			 */
			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 * The whole idea of the singleton design pattern is that there is a single object
		 * therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since  0.0.1-alpha
		 * @return void
		 */
		public function __clone() {

			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'The BPGraphQL class should not be cloned.', 'bp-graphql' ), '0.0.1-alpha' );

		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access public
		 * @since  0.0.1-alpha
		 * @return void
		 */
		public function __wakeup() {

			// De-serializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'De-serializing instances of the BPGraphQL class is not allowed', 'bp-graphql' ), '0.0.1-alpha' );

		}

		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @since  0.0.1-alpha
		 * @return void
		 */
		private function setup_constants() {

			// Plugin Folder Path.
			if ( ! defined( 'BPGRAPHQL_PLUGIN_DIR' ) ) {
				define( 'BPGRAPHQL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'BPGRAPHQL_PLUGIN_URL' ) ) {
				define( 'BPGRAPHQL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'BPGRAPHQL_PLUGIN_FILE' ) ) {
				define( 'BPGRAPHQL_PLUGIN_FILE', __FILE__ );
			}

		}

		/**
		 * Uses composer's autoload to include required files.
		 *
		 * @access private
		 * @since 0.0.1-alpha
		 * @return void
		 */
		private function includes() {

			// Autoload required classes.
			require_once( BPGRAPHQL_PLUGIN_DIR . 'vendor/autoload.php' );

		}

		/**
		 * Class dependencies
		 *
		 * @access private
		 * @since  0.0.1-alpha
		 * @return void
		 */
		private function dependencies() {

			// Checks if BuddyPress is installed.
			if ( ! class_exists( 'BuddyPress' ) ) {
				add_action( 'admin_notices', array( $this, 'buddypress_missing_notice' ) );
				return;
			}

			// Checks if WPGraphQL is installed.
			if ( ! class_exists( 'WPGraphQL' ) ) {
				add_action( 'admin_notices', array( $this, 'wpgraphql_missing_notice' ) );
				return;
			}

		}

		/**
		 * Sets up actions to run at certain spots throughout WordPress and the BPGraphQL execution cycle
		 *
		 * @access private
		 * @since 0.0.1-alpha
		 * @return void
		 */
		private function actions() {

			/**
			 * Init BPGraphQL after themes have been setup,
			 * allowing for both plugins and themes to register
			 * things before graphql_init
			 */
			add_action( 'after_setup_theme', function() {

				/**
				 * Fire off init action
				 *
				 * @param BPGraphQL $instance The instance of the BPGraphQL class
				 */
				do_action( 'graphql_init', self::$instance );

			} );

		}

		/**
		 * Hook BuddyPress fields.
		 *
		 * @access private
		 * @since 0.0.1-alpha
		 * @return void
		 */
		private function filters() {
			add_filter( 'graphql_root_queries', [ '\BPGraphQL\Filters', 'add_fields' ], 10, 1 );
		}

		/**
		 * BuddyPress missing notice.
		 *
		 * @access public
		 * @since 0.0.1-alpha
		 * @return void
		 */
		public function buddypress_missing_notice() {
			?>
			<div class="error">
				<p><strong><?php esc_html_e( 'BP GraphQL', 'bp-graphql' ); ?></strong> <?php esc_html_e( 'depends on the lastest version of Buddypress to work!', 'bp-graphql' ); ?></p>
			</div>
			<?php
		}

		/**
		 * WPGraphQL missing notice.
		 *
		 * @access public
		 * @since 0.0.1-alpha
		 * @return void
		 */
		public function wpgraphql_missing_notice() {
			?>
			<div class="error">
				<p><strong><?php esc_html_e( 'BP GraphQL', 'bp-graphql' ); ?></strong> <?php esc_html_e( 'depends on the lastest version of WPGraphQL to work!', 'bp-graphql' ); ?></p>
			</div>
			<?php
		}
	}

endif;

/**
 * Function that instantiates the plugins main class
 *
 * @since 0.0.1-alpha
 */
function bpgraphql_init() {

	/**
	 * Return an instance of the action
	 */
	return \BPGraphQL::instance();
}
bpgraphql_init();
