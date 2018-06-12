<?php
/**
 * Test Class
 *
 * @package BPGraphQL
 */

class Test_BPGraphQL extends WP_UnitTestCase {

	/**
	 * Test constants.
	 */
	public function test_constants() {
		$this->assertTrue( defined( 'BPGRAPHQL_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'BPGRAPHQL_PLUGIN_URL' ) );
		$this->assertTrue( defined( 'BPGRAPHQL_PLUGIN_FILE' ) );
	}

}
