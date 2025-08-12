<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function lens_calculator_remove_pages_and_menu() {
    // Page slugs you used when creating pages
    $page_slugs = [
        'lens-calculator-shortcode-1',
        'lens-calculator-shortcode-2',
        'lens-calculator-shortcode-3',
    ];

    // Remove pages
    foreach ( $page_slugs as $slug ) {
        $page = get_page_by_path( $slug );
        if ( $page ) {
            wp_delete_post( $page->ID, true ); // true = force delete, bypass trash
        }
    }

    // Remove pages from main menu
    $menu_name = 'Main Menu'; // Change this to your menu name if different
    $menu = wp_get_nav_menu_object( $menu_name );

    if ( $menu ) {
        $menu_items = wp_get_nav_menu_items( $menu->term_id );

        if ( ! empty( $menu_items ) ) {
            foreach ( $menu_items as $item ) {
                if ( in_array( $item->object_id, wp_list_pluck( get_posts( [
                    'post_type' => 'page',
                    'post_status' => 'any',
                    'name' => $page_slugs,
                    'fields' => 'ids',
                ] ), 'ID' ) ) ) {
                    wp_delete_post( $item->ID, true );
                }
            }
        }
    }
}
