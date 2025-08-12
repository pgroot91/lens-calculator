<?php
class Test_Sensors extends WP_UnitTestCase {
    public function test_sensor_sizes_count() {
        $sensors = wplc_get_sensor_sizes();
        $this->assertCount(15, $sensors, 'Sensor list should have 15 CCTV formats');
    }

    public function test_sensor_sizes_values() {
        $sensors = wplc_get_sensor_sizes();
        $this->assertEquals(12.80, $sensors[0]['width']);
        $this->assertEquals(9.60, $sensors[0]['height']);
        $this->assertEquals('1 inch', $sensors[0]['label']);
    }

    public function test_sensor_select_html() {
        $html = wplc_sensor_select('answer1', 'answer1');
        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('1 inch', $html);
        $this->assertStringContainsString('Kies formaat', $html);
    }
}
