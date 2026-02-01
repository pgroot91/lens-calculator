<?php
class SensorsTest extends WP_UnitTestCase {
    public function test_sensor_sizes_count() {
        $sensors = wplc_get_sensor_sizes();
        $this->assertCount(24, $sensors, 'Sensor list should have 24 CCTV formats');
    }

    public function test_sensor_sizes_values() {
        $sensors = wplc_get_sensor_sizes();
        $this->assertEquals(17.30, $sensors[0]['width']);
        $this->assertEquals(13.00, $sensors[0]['height']);
        $this->assertEquals('4/3 inch (4:3) — 21.64 mm diagonaal', $sensors[0]['label']);
    }

    public function test_sensor_select_html() {
        $html = wplc_sensor_select('answer1', 'answer1');
        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('4/3 inch (4:3) — 21.64 mm diagonaal', $html);
        $this->assertStringContainsString('Kies formaat', $html);
    }
}
