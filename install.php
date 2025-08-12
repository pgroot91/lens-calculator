<?php
// install.php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function lens_calculator_create_pages_and_menu() {
    $pages = [
        [
            'title'   => 'Lens Calculator - Full',
            'content' => '[full-calculator]',
            'slug'    => 'lens-calculator-full',
        ],
        [
            'title'   => 'Lens Calculator - Width',
            'content' => '[width-calculator]',
            'slug'    => 'lens-calculator-width',
        ],
        [
            'title'   => 'Lens Calculator - Height',
            'content' => '[height-calculator]',
            'slug'    => 'lens-calculator-height',
        ],
    ];

    $page_ids = [];

    foreach ($pages as $page) {
        $existing_page = get_page_by_path($page['slug']);
        if (!$existing_page) {
            $page_id = wp_insert_post([
                'post_title'   => wp_strip_all_tags($page['title']),
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => $page['slug'],
            ]);
        } else {
            $page_id = $existing_page->ID;
        }
        $page_ids[] = $page_id;
    }

    // Add pages to the 'Primary Menu' (or create it if it doesn't exist)
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
    } else {
        $menu_id = $menu_exists->term_id;
    }

    // Get all menu items already in menu to avoid duplicates
    $menu_items = wp_get_nav_menu_items($menu_id) ?: [];
    $existing_page_ids_in_menu = [];
    foreach ($menu_items as $item) {
        if ($item->object_id) {
            $existing_page_ids_in_menu[] = (int) $item->object_id;
        }
    }

    // Add pages to menu if not already added
    foreach ($page_ids as $page_id) {
        if (!in_array($page_id, $existing_page_ids_in_menu, true)) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-object-id' => $page_id,
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
            ]);
        }
    }

    // Assign this menu to the theme location 'primary' (if it exists)
    $locations = get_theme_mod('nav_menu_locations');
    if (isset($locations['primary']) && $locations['primary'] !== $menu_id) {
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
