<?php

/**
* Plugin Name: CCTV Lens Calculator  
* Plugin URI: http://patrickgroot.com
* Description: De lenscalculator kan op twee manieren toegepast worden. U kunt de afstand tot het object bepalen door gebruik te maken van de totale breedte van het object, of door de totale hoogte van het object. Het formaat van het CCD of CMOS element staat meestal bij de specificaties van de camera weergegeven.
* Version: 1.0.3
* Author: Patrick Groot
* Author URI: http://patrickgroot.com
* Text Domain: lens-calculator
* Domain Path: /languages
* License: GPL v2
*/

/***********************************************************************************************/
/* Load Text Domain  */
/***********************************************************************************************/

add_action( 'plugins_loaded', 'lens_calculator_textdomain' );

function lens_calculator_textdomain() {
    load_plugin_textdomain( 'lens-calculator', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

/***********************************************************************************************/
/* Register Stylesheet, Javascript   */
/***********************************************************************************************/

add_action( 'wp_enqueue_scripts', 'register_lens_calulator_styles' );

function register_lens_calulator_styles() {
	wp_register_style( 'lens-calculator', plugins_url( 'lens-calculator/css/plugin.css' ) );
	wp_enqueue_style( 'lens-calculator' );
}

/***********************************************************************************************/
/* Register Javascript  */
/***********************************************************************************************/

add_action( 'wp_enqueue_scripts', 'register_lens_calulator_scripts' );

function register_lens_calulator_scripts() {  
	// Register the script
	wp_register_script( 'lens-calculator', plugins_url( 'lens-calculator/js/lens-calculator.js' ) );
	// Localize the script with new data
	$translation_array = array(
		'message1' => __( 'Formaat CCD element graag invullen.', 'lens-calculator' ),
		'message2' => __( 'Afstand tot object graag invullen.', 'lens-calculator' ),
		'message31' => __( 'Hoogte van het object graag invullen.', 'lens-calculator' ),
		'message32' => __( 'Breedte van het object graag invullen.', 'lens-calculator' ),
		'nnb' => __( 'NNB', 'lens-calculator' )
	);
	wp_localize_script( 'lens-calculator', 'object_name', $translation_array );

	// Enqueued script with localized data.
    wp_enqueue_script( 'lens-calculator' );  
}

/***********************************************************************************************/
/* Shortcodes  */
/***********************************************************************************************/

function wplc_hoogte_calculator() {
	wplc_height_calculator();
}

add_shortcode('height-calculator', 'wplc_hoogte_calculator');

function wplc_breedte_calculator() {
	wplc_width_calculator();
}

add_shortcode('width-calculator', 'wplc_breedte_calculator');

function wplc_volledig_calculator() {
	wplc_full_calculator();
}

add_shortcode('full-calculator', 'wplc_volledig_calculator');

/***********************************************************************************************/
/* Forms  */
/***********************************************************************************************/

function wplc_full_calculator() {
	echo '<h1>' . __( 'Lens Calculator', 'lens-calculator' ) . '</h1>';
	echo '<p>' . __( 'De onderstaande lenscalculator kan op twee manieren toegepast worden. U kunt de afstand tot het object bepalen door gebruik te maken van de totale breedte van het object, of door de totale hoogte van het object. Het formaat van het CCD of CMOS element staat meestal bij de specificaties van de camera weergegeven.', 'lens-calculator' ) . '</p>';
	echo '<h3>' . __( 'Berekening naar breedte object', 'lens-calculator' ) . '</h3>';
	echo '<form name="breedte" autocomplete="off">';
	echo '<p class="stap"><strong>' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</strong></p>';
	echo '<select name="answer1" size="1" class="wplc_select"> <option value="0" selected>' . __( 'Kies formaat CCD of CMOS', 'lens-calculator' ) . '</option><option value="1">' . __( '1 inch', 'lens-calculator' ) . '</option> <option value="2">' . __( '2/3 inch', 'lens-calculator' ) . '</option> <option value="3">' . __( '1/2 inch', 'lens-calculator' ) . '</option> <option value="4">' . __( '1/3 inch', 'lens-calculator' ) . '</option> <option value="5">' . __( '1/4 inch', 'lens-calculator' ) . '</option></select>';
	echo '<p class="stap"><strong>' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objectafstand" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<p class="stap"><strong>' . __( 'Stap 3: Wat is de breedte van het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objectbreedte" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<input class="button" onclick=compute_it_breedte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '" /><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" />';
	echo '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input name="output" class="output" size="5" disabled/>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	echo '</form>';
	echo '<h3>' . __( 'Berekening naar hoogte object', 'lens-calculator' ) . '</h3>';
	echo '<form name="hoogte" autocomplete="off">';
	echo '<p class="stap"><strong>' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</strong></p>';
	echo '<select name="answer1" size="1" class="wplc_select"> <option value="0" selected>' . __( 'Kies formaat CCD of CMOS', 'lens-calculator' ) . '</option><option value="1">' . __( '1 inch', 'lens-calculator' ) . '</option> <option value="2">' . __( '2/3 inch', 'lens-calculator' ) . '</option> <option value="3">' . __( '1/2 inch', 'lens-calculator' ) . '</option> <option value="4">' . __( '1/3 inch', 'lens-calculator' ) . '</option> <option value="5">' . __( '1/4 inch', 'lens-calculator' ) . '</option></select>';
	echo '<p class="stap"><strong>' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objectafstand" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<p class="stap"><strong>' . __( 'Stap 3: Wat is de hoogte van het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objecthoogte" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<input class="button" onclick=compute_it_hoogte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '" /><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" />';
	echo '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input name="output" class="output" size="5" disabled/>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	echo '</form>';
}

function wplc_width_calculator() {
	echo '<h3>' . __( 'Berekening naar breedte object', 'lens-calculator' ) . '</h3>';
	echo '<form name="breedte" autocomplete="off">';
	echo '<p class="stap"><strong>' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</strong></p>';
	echo '<select name="answer1" size="1" class="wplc_select"> <option value="0" selected>' . __( 'Kies formaat CCD of CMOS', 'lens-calculator' ) . '</option><option value="1">' . __( '1 inch', 'lens-calculator' ) . '</option> <option value="2">' . __( '2/3 inch', 'lens-calculator' ) . '</option> <option value="3">' . __( '1/2 inch', 'lens-calculator' ) . '</option> <option value="4">' . __( '1/3 inch', 'lens-calculator' ) . '</option> <option value="5">' . __( '1/4 inch', 'lens-calculator' ) . '</option></select>';
	echo '<p class="stap"><strong>' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objectafstand" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<p class="stap"><strong>' . __( 'Stap 3: Wat is de breedte van het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objectbreedte" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<input class="button" onclick=compute_it_breedte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '" /><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" />';
	echo '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input name="output" class="output" size="5" disabled/>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	echo '</form>';
}

function wplc_height_calculator() {
	echo '<h3>' . __( 'Berekening naar hoogte object', 'lens-calculator' ) . '</h3>';
	echo '<form name="hoogte" autocomplete="off">';
	echo '<p class="stap"><strong>' . __( 'Stap 1: Kies het formaat', 'lens-calculator' ) . '</strong></p>';
	echo '<select name="answer1" size="1" class="wplc_select"> <option value="0" selected>' . __( 'Kies formaat CCD of CMOS', 'lens-calculator' ) . '</option><option value="1">' . __( '1 inch', 'lens-calculator' ) . '</option> <option value="2">' . __( '2/3 inch', 'lens-calculator' ) . '</option> <option value="3">' . __( '1/2 inch', 'lens-calculator' ) . '</option> <option value="4">' . __( '1/3 inch', 'lens-calculator' ) . '</option> <option value="5">' . __( '1/4 inch', 'lens-calculator' ) . '</option></select>';
	echo '<p class="stap"><strong>' . __( 'Stap 2: Wat is de afstand tot het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objectafstand" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<p class="stap"><strong>' . __( 'Stap 3: Wat is de hoogte van het object?', 'lens-calculator' ) . '</strong></p>';
	echo '<input type="number" name="objecthoogte" class="wplc_field" size="5" />';
	echo '<p class="small">' . __( 'Alleen hele meters gebruiken', 'lens-calculator' ) . '<p>';
	echo '<input class="button" onclick=compute_it_hoogte() type="button" value="' . __( 'Berekenen', 'lens-calculator' ) . '" name="' . __( 'Berekenen', 'lens-calculator' ) . '" /><input class="button" type="reset" value="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" name="' . __( 'Nieuwe berekening', 'lens-calculator' ) . '" />';
	echo '<p>' . __( 'Gebruik een', 'lens-calculator' ) . '<input name="output" class="output" size="5" disabled/>' . __( 'mm objectief', 'lens-calculator' ) . '</p>';
	echo '</form>';
}

?>