<?php

/**
* Plugin Name: CCTV Lens Calculator  
* Plugin URI: http://patrickgroot.com
* Description: De lenscalculator kan op twee manieren toegepast worden. U kunt de afstand tot het object bepalen door gebruik te maken van de totale breedte van het object, of door de totale hoogte van het object. Het formaat van het CCD of CMOS element staat meestal bij de specificaties van de camera weergegeven.
* Version: 2.0.6
* Author: Patrick Groot
* Author URI: http://patrickgroot.com
* Text Domain: lens-calculator
* Domain Path: /languages
* License: GPL v2
*/

/***********************************************************************************************/
/* Define Plugin Version */
/* How to use: echo('CCTV_LENS_CALCULATOR_VERSION'); */
/***********************************************************************************************/

define ('CCTV_LENS_CALCULATOR_VERSION', '2.0.6');

/***********************************************************************************************/
/* Define CCTV sensor sizes (width × height in mm) */
/***********************************************************************************************/

function wplc_get_sensor_sizes() {
    return [
        ['label' => __('4/3 inch (4:3) — 21.64 mm diagonaal', 'lens-calculator'),  'width' => 17.30, 'height' => 13.00],
        ['label' => __('1 inch (3:2) — 15.86 mm diagonaal', 'lens-calculator'),    'width' => 13.20, 'height' => 8.80],
        ['label' => __('1 inch (16:9) — 15.03 mm diagonaal', 'lens-calculator'),   'width' => 13.20, 'height' => 7.40],
        ['label' => __('1 inch (4:3) — 16.00 mm diagonaal', 'lens-calculator'),    'width' => 12.80, 'height' => 9.60],
        ['label' => __('1/1.12 inch — 15.62 mm diagonaal', 'lens-calculator'),     'width' => 12.50, 'height' => 9.40],
        ['label' => __('1/1.2 inch — 14.00 mm diagonaal', 'lens-calculator'),      'width' => 11.20, 'height' => 8.40],
        ['label' => __('1/1.33 inch — 12.00 mm diagonaal', 'lens-calculator'),     'width' => 9.60,  'height' => 7.20],
        ['label' => __('2/3 inch — 11.00 mm diagonaal', 'lens-calculator'),        'width' => 8.80,  'height' => 6.60],
        ['label' => __('1/1.55 inch — 10.32 mm diagonaal', 'lens-calculator'),     'width' => 8.30,  'height' => 6.20],
        ['label' => __('1/1.7 inch — 9.50 mm diagonaal', 'lens-calculator'),       'width' => 7.60,  'height' => 5.70],
        ['label' => __('1/1.8 inch — 9.00 mm diagonaal', 'lens-calculator'),       'width' => 7.20,  'height' => 5.40],
        ['label' => __('1/1.9 inch — 9.00 mm diagonaal', 'lens-calculator'),       'width' => 7.20,  'height' => 5.40],
        ['label' => __('1/2 inch — 8.00 mm diagonaal', 'lens-calculator'),         'width' => 6.40,  'height' => 4.80],
        ['label' => __('1/2.3 inch — 7.70 mm diagonaal', 'lens-calculator'),       'width' => 6.17,  'height' => 4.55],
        ['label' => __('1/2.8 inch — 7.40 mm diagonaal', 'lens-calculator'),       'width' => 6.46,  'height' => 3.64],
        ['label' => __('1/2.5 inch — 7.18 mm diagonaal', 'lens-calculator'),       'width' => 5.76,  'height' => 4.29],
        ['label' => __('1/2.7 inch (4:3) — 6.72 mm diagonaal', 'lens-calculator'), 'width' => 5.37,  'height' => 4.04],
        ['label' => __('1/2.7 inch (16:9) — 6.20 mm diagonaal', 'lens-calculator'),'width' => 5.37,  'height' => 3.02],
        ['label' => __('1/3 inch — 6.00 mm diagonaal', 'lens-calculator'),         'width' => 4.80,  'height' => 3.60],
        ['label' => __('1/3.2 inch — 5.68 mm diagonaal', 'lens-calculator'),       'width' => 4.54,  'height' => 3.42],
        ['label' => __('1/3.6 inch — 5.00 mm diagonaal', 'lens-calculator'),       'width' => 4.00,  'height' => 3.00],
        ['label' => __('1/4 inch — 4.00 mm diagonaal', 'lens-calculator'),         'width' => 3.20,  'height' => 2.40],
        ['label' => __('1/5 inch — 3.20 mm diagonaal', 'lens-calculator'),         'width' => 2.56,  'height' => 1.92],
        ['label' => __('1/6 inch — 2.67 mm diagonaal', 'lens-calculator'),         'width' => 2.13,  'height' => 1.60]
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
	if( is_rtl() === false && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'full-calculator' ) || has_shortcode( $post->post_content, 'width-calculator' ) || has_shortcode( $post->post_content, 'height-calculator' ) ) {
		wp_register_style( 'lens-calculator', plugins_url( 'lens-calculator/dist/lens-calculator.min.css' ) );
		wp_enqueue_style( 'lens-calculator' );
	} elseif( is_rtl() === true && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'full-calculator' ) || has_shortcode( $post->post_content, 'width-calculator' ) || has_shortcode( $post->post_content, 'height-calculator' ) ) {
		wp_register_style( 'lens-calculator', plugins_url( 'lens-calculator/dist/lens-calculator-rtl.min.css' ) );
		wp_enqueue_style( 'lens-calculator' );
	}
}
add_action( 'wp_enqueue_scripts', 'register_lens_calulator_styles' );

/***********************************************************************************************/
/* Register Javascript */
/***********************************************************************************************/

function register_lens_calulator_scripts() {  
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'full-calculator' ) ) {
		wp_register_script( 'lens-calculator', plugins_url( 'lens-calculator/dist/lens-calculator.bundle.min.js' ) );
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
	} elseif (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'width-calculator' ) ) {
        wp_register_script( 'lens-calculator', plugins_url( 'lens-calculator/dist/lens-calculator.bundle.min.js' ) );
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
    } elseif (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'height-calculator' ) ) {
		wp_register_script( 'lens-calculator', plugins_url( 'lens-calculator/dist/lens-calculator.bundle.min.js' ) );
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
    $html .= '<div class="custom-select"><div class="custom-select-trigger">Selecteer</div><div class="custom-options"></div></div>';
    return $html;
}

/***********************************************************************************************/
/* Shortcodes */
/***********************************************************************************************/

add_action('init', function() {
    add_shortcode('height-calculator', 'wplc_hoogte_calculator');
    add_shortcode('width-calculator', 'wplc_breedte_calculator');
    add_shortcode('full-calculator', 'wplc_volledig_calculator');
});

function wplc_hoogte_calculator() {
	return wplc_height_calculator();
}

function wplc_breedte_calculator() {
	return wplc_width_calculator();
}

function wplc_volledig_calculator() {
	return wplc_full_calculator();
}

function wplc_height_calculator() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/height-form.php';
    return ob_get_clean();
}

function wplc_width_calculator() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/width-form.php';
    return ob_get_clean();
}

function wplc_full_calculator() {
	ob_start();
	include plugin_dir_path(__FILE__) . 'templates/full-calculator.php';
	return ob_get_clean();
}

/***********************************************************************************************/
/* Parts */
/***********************************************************************************************/

function wplc_advice() {
	include plugin_dir_path(__FILE__) . 'templates/parts/advice.php';
}
add_filter( 'wplc_after_height_form', 'wplc_advice' , 10);
add_filter( 'wplc_after_width_form', 'wplc_advice' , 10);
add_filter( 'wplc_after_height_width_form', 'wplc_advice' , 10);

function wplc_disclaimer()
{
	include plugin_dir_path(__FILE__) . 'templates/parts/disclaimer.php';
}
add_action('wplc_after_height_form','wplc_disclaimer', 20);
add_action('wplc_after_width_form','wplc_disclaimer', 20);
add_action('wplc_after_height_width_form','wplc_disclaimer', 20);

function wplc_copyright() {
    $disable = get_option('disable_footer_copyright', false);
    if ( $disable ) {
        return;
    }
    include plugin_dir_path(__FILE__) . 'templates/parts/copyright.php';
}
add_filter( 'wplc_after_height_form', 'wplc_copyright' , 999);
add_filter( 'wplc_after_width_form', 'wplc_copyright' , 999);
add_filter( 'wplc_after_height_width_form', 'wplc_copyright' , 999);












// Add a custom menu item under "Settings"
add_action('admin_menu', 'mytheme_add_settings_menu');
function mytheme_add_settings_menu() {
    add_options_page(
        'CCTV Lens Calculator',                 // Page title
        'CCTV Lens Calculator',                 // Menu title
        'manage_options',                  // Capability
        'mytheme-footer-settings',         // Menu slug
        'mytheme_footer_settings_page'     // Callback function
    );
}

// Register the setting
add_action('admin_init', 'mytheme_register_settings');
function mytheme_register_settings() {
    register_setting('mytheme_footer_settings_group', 'disable_footer_copyright');
    
    add_settings_section(
        'mytheme_footer_section',
        'Settings',
        null,
        'mytheme-footer-settings'
    );

    add_settings_field(
        'disable_footer_copyright',
        'Disable Copyright Footer Text',
        'mytheme_footer_checkbox_render',
        'mytheme-footer-settings',
        'mytheme_footer_section'
    );
}

// Render the checkbox field
function mytheme_footer_checkbox_render() {
    $checked = get_option('disable_footer_copyright', false);
    ?>
    <input type="checkbox" name="disable_footer_copyright" value="1" <?php checked($checked, 1); ?> />
    <label for="disable_footer_copyright">Check to remove the copyright text in the footer.</label>
    <?php
}

// Create the settings page HTML
function mytheme_footer_settings_page() {
    ?>
    <div class="wrap">
        <h1>CCTV Lens Calculator</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('mytheme_footer_settings_group');
            do_settings_sections('mytheme-footer-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Example of how to conditionally hide the footer text
add_action('wp_footer', 'mytheme_maybe_hide_footer_copyright', 1);
function mytheme_maybe_hide_footer_copyright() {
    $disable = get_option('disable_footer_copyright', false);
    if ($disable) {
        // Prevent default footer copyright text
        remove_action('wp_footer', 'your_theme_footer_copyright_function');
    }
}



?>