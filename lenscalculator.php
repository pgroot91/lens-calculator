<?php

/**
* Plugin Name: CCTV Lens Calculator  
* Plugin URI: http://patrickgroot.com
* Description: De lenscalculator kan op twee manieren toegepast worden. U kunt de afstand tot het object bepalen door gebruik te maken van de totale breedte van het object, of door de totale hoogte van het object. Het formaat van het CCD of CMOS element staat meestal bij de specificaties van de camera weergegeven.
* Version: 2.0.0
* Author: Patrick Groot
* Author URI: http://patrickgroot.com
* Text Domain: lens-calculator
* Domain Path: /languages
* License: GPL v2
*/

/***********************************************************************************************/
/* Define CCTV sensor sizes (width × height in mm) */
/***********************************************************************************************/

function wplc_get_sensor_sizes() {
    return [
        ['label' => '1 inch',     'width' => 12.80, 'height' => 9.60],
        ['label' => '2/3 inch',   'width' => 8.80,  'height' => 6.60],
        ['label' => '1/1.2 inch', 'width' => 11.20, 'height' => 8.40],
        ['label' => '1/1.7 inch', 'width' => 7.60,  'height' => 5.70],
        ['label' => '1/1.8 inch', 'width' => 7.20,  'height' => 5.40],
        ['label' => '1/2 inch',   'width' => 6.40,  'height' => 4.80],
        ['label' => '1/2.3 inch', 'width' => 6.17,  'height' => 4.55],
        ['label' => '1/2.5 inch', 'width' => 5.76,  'height' => 4.29],
        ['label' => '1/2.7 inch', 'width' => 5.37,  'height' => 4.04],
        ['label' => '1/3 inch',   'width' => 4.80,  'height' => 3.60],
        ['label' => '1/3.2 inch', 'width' => 4.54,  'height' => 3.42],
        ['label' => '1/3.6 inch', 'width' => 4.00,  'height' => 3.00],
        ['label' => '1/4 inch',   'width' => 3.20,  'height' => 2.40],
        ['label' => '1/5 inch',   'width' => 2.56,  'height' => 1.92],
        ['label' => '1/6 inch',   'width' => 2.13,  'height' => 1.60]
    ];
}

/***********************************************************************************************/
/* Load Text Domain */
/***********************************************************************************************/

function lens_calculator_textdomain() {
	load_plugin_textdomain( 'lens-calculator', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'lens_calculator_textdomain' );

/***********************************************************************************************/
/* Register Stylesheet */
/***********************************************************************************************/

function register_lens_calulator_styles() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'full-calculator' ) || has_shortcode( $post->post_content, 'width-calculator' ) || has_shortcode( $post->post_content, 'height-calculator' ) ) {
		wp_register_style( 'lens-calculator', plugins_url( 'lens-calculator/css/plugin.css' ) );
		wp_enqueue_style( 'lens-calculator' );
	}
}
add_action( 'wp_enqueue_scripts', 'register_lens_calulator_styles' );

/***********************************************************************************************/
/* Register Javascript */
/***********************************************************************************************/

function register_lens_calulator_scripts() {  
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'full-calculator' ) || has_shortcode( $post->post_content, 'width-calculator' ) || has_shortcode( $post->post_content, 'height-calculator' ) ) {
		wp_register_script( 'lens-calculator', plugins_url( 'lens-calculator/dist/lens-calculator.bundle.js' ) );
		$sensor_sizes = wplc_get_sensor_sizes();
		$translation_array = array(
			'message1' => __( 'Formaat CCD element graag invullen.', 'lens-calculator' ),
			'message2' => __( 'Afstand tot object graag invullen.', 'lens-calculator' ),
			'message31' => __( 'Hoogte van het object graag invullen.', 'lens-calculator' ),
			'message32' => __( 'Breedte van het object graag invullen.', 'lens-calculator' ),
			'nnb' => __( 'NNB', 'lens-calculator' ),
			'sensors'  => $sensor_sizes
		);
		wp_localize_script( 'lens-calculator', 'lens_calculator', $translation_array );
		wp_enqueue_script( 'lens-calculator' );
	}
}
add_action( 'wp_enqueue_scripts', 'register_lens_calulator_scripts' );

/***********************************************************************************************/
/* Generate dropdown dynamically */
/***********************************************************************************************/

function wplc_sensor_select($name, $id) {
    $sensors = wplc_get_sensor_sizes();
    $html = '<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" class="wplc_select">';
    $html .= '<option value="0" selected>' . __('Kies formaat CCD of CMOS', 'lens-calculator') . '</option>';
    foreach ($sensors as $index => $sensor) {
        $html .= '<option value="' . ($index + 1) . '">' . esc_html($sensor['label']) . '</option>';
    }
    $html .= '</select>';
    return $html;
}

/***********************************************************************************************/
/* Shortcodes */
/***********************************************************************************************/

function wplc_hoogte_calculator() {
	return wplc_height_calculator();
}
add_shortcode('height-calculator', 'wplc_hoogte_calculator');

function wplc_breedte_calculator() {
	return wplc_width_calculator();
}
add_shortcode('width-calculator', 'wplc_breedte_calculator');

function wplc_volledig_calculator() {
	return wplc_full_calculator();
}
add_shortcode('full-calculator', 'wplc_volledig_calculator');

/***********************************************************************************************/
/* Forms */
/***********************************************************************************************/

function wplc_full_calculator() {
	$form = '<h1>' . __( 'Lens Calculator', 'lens-calculator' ) . '</h1>';
	$form .= '<p>' . __( 'De onderstaande lenscalculator kan op twee manieren toegepast worden. U kunt de afstand tot het object bepalen door gebruik te maken van de totale breedte van het object, of door de totale hoogte van het object. Het formaat van het CCD of CMOS element staat meestal bij de specificaties van de camera weergegeven.', 'lens-calculator' ) . '</p>';

	$form .= '<h3>' . __( 'Berekening naar breedte object', 'lens-calculator' ) . '</h3>';
	$form .= '<form name="breedte" autocomplete="off">';
	$form .= '<label for="breedte-answer">' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</label>';
    $form .= wplc_sensor_select('answer1', 'breedte-answer');
	$form .= '<label for="breedte-objectafstand">' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objectafstand" id="breedte-objectafstand" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<label for="breedte-objectbreedte">' . __( 'Stap 3: Wat is de breedte van het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objectbreedte" id="breedte-objectbreedte" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<div class="btn-group">';
	$form .= '<input class="button button-primary" onclick=compute_it_breedte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '"><input class="button button-secodary" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '">';
	$form .= '</div>';
	$form .= '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input type="text" name="output" class="wplc_field output" size="5" readonly>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	$form .= '</form>';

	$form .= '<h3>' . __( 'Berekening naar hoogte object', 'lens-calculator' ) . '</h3>';
	$form .= '<form name="hoogte" autocomplete="off">';
	$form .= '<label for="hoogte-answer">' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</label>';
    $form .= wplc_sensor_select('answer1', 'hoogte-answer');
	$form .= '<label for="hoogte-objectafstand">' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objectafstand" id="hoogte-objectafstand" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<label for="objecthoogte">' . __( 'Stap 3: Wat is de hoogte van het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objecthoogte" id="objecthoogte" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<div class="btn-group">';
	$form .= '<input class="button" onclick=compute_it_hoogte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '"><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '">';
	$form .= '</div>';
	$form .= '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input type="text" name="output" class="wplc_field output" size="5" readonly>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	$form .= '</form>';

	return $form;
}

function wplc_width_calculator() {
	$form = '<h3>' . __( 'Berekening naar breedte object', 'lens-calculator' ) . '</h3>';
	$form .= '<form name="breedte" autocomplete="off">';
	$form .= '<label for="breedte-answer">' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</label>';
    $form .= wplc_sensor_select('answer1', 'breedte-answer');
	$form .= '<label for="breedte-objectafstand">' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objectafstand" id="breedte-objectafstand" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<label for="breedte-objectbreedte">' . __( 'Stap 3: Wat is de breedte van het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objectbreedte" id="breedte-objectbreedte" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<div class="btn-group">';
	$form .= '<input class="button" onclick=compute_it_breedte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '"><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '">';
	$form .= '</div>';
	$form .= '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input type="text" name="output" class="wplc_field output" size="5" readonly>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	$form .= '</form>';

	return $form;
}

function wplc_height_calculator() {
	$form = '<h3>' . __( 'Berekening naar hoogte object', 'lens-calculator' ) . '</h3>';
	$form .= '<form name="hoogte" autocomplete="off">';
	$form .= '<label for="hoogte-answer">' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</label>';
    $form .= wplc_sensor_select('answer1', 'hoogte-answer');
	$form .= '<label for="hoogte-objectafstand">' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objectafstand" id="hoogte-objectafstand" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<label for="objecthoogte">' . __( 'Stap 3: Wat is de hoogte van het object?', 'lens-calculator' ) . '</label>';
	$form .= '<input type="number" name="objecthoogte" id="objecthoogte" class="wplc_field" min="0" max="999" placeholder="0" />';
	$form .= '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '</p>';
	$form .= '<div class="btn-group">';
	$form .= '<input class="button" onclick=compute_it_hoogte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '"><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" />';
	$form .= '</div>';
	$form .= '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input type="text" name="output" class="wplc_field output" size="5" readonly>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	$form .= '</form>';

	return $form;
}

?>