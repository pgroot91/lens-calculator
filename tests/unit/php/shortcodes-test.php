<?php
class ShortcodesTest extends WP_UnitTestCase
{
    public function test_height_calculator_shortcode()
    {
        $this->assertTrue(shortcode_exists('height-calculator'), 'Height calculator shortcode should exist');
    }
    public function test_width_calculator_shortcode()
    {
        $this->assertTrue(shortcode_exists('width-calculator'), 'Width calculator shortcode should exist');
    }
    public function test_full_calculator_shortcode()
    {
        $this->assertTrue(shortcode_exists('full-calculator'), 'Full calculator shortcode should exist');
    }
}