<?php
class SampleTest extends WP_UnitTestCase {
    public function test_plugin_loaded() {
        $this->assertTrue( function_exists( 'compute_it_hoogte' ) || function_exists( 'compute_it_breedte' ) );
    }
}
