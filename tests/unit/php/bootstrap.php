<?php
/**
 * PHPUnit bootstrap for plugin using wp-env's built-in WordPress test suite.
 */

$_tests_dir = '/var/www/html/wp-content/plugins/wordpress-develop/tests/phpunit';

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
    echo "Could not find WordPress test suite in $_tests_dir\n";
    exit(1);
}

// Load WordPress test functions.
require_once $_tests_dir . '/includes/functions.php';

// Load the plugin.
tests_add_filter( 'muplugins_loaded', function () {
    require dirname( __DIR__, 3 ) . '/your-plugin-main-file.php';
} );

// Start WordPress testing environment.
require $_tests_dir . '/includes/bootstrap.php';
