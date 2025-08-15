<?php

use Yoast\WPTestUtils\WPIntegration;

define( 'TESTS_PLUGIN_DIR', dirname( __FILE__, 3 ) );

require_once dirname( TESTS_PLUGIN_DIR ) . '/vendor/yoast/wp-test-utils/src/WPIntegration/bootstrap-functions.php';

$_tests_dir = WPIntegration\get_path_to_wp_test_dir();

if ( ! $_tests_dir ) {
	exit( PHP_EOL . "\033[41mWP_TESTS_DIR environment variable is not defined.\033[0m" . PHP_EOL . PHP_EOL );
}

require_once $_tests_dir . 'includes/functions.php';

/**
 * Manually load the Example Plugin.
 */
function _manually_load_plugin() {
    echo 'Loading Lens Calculator plugin...' . dirname(TESTS_PLUGIN_DIR) . '/lenscalculator.php';
    require_once dirname(TESTS_PLUGIN_DIR) . '/lenscalculator.php';
}
tests_add_filter( 'plugins_loaded', '_manually_load_plugin' );

WPIntegration\bootstrap_it();