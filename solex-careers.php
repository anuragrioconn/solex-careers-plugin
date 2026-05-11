<?php
/**
 * Plugin Name: Solex Careers
 * Plugin URI: https://solex.in
 * Description: Dynamic careers listing and job detail plugin for Solex.
 * Version: 1.1.0
 * Author: Anurag
 * Text Domain: solex-careers
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SOLEX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SOLEX_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once SOLEX_PLUGIN_PATH . 'includes/data.php';
require_once SOLEX_PLUGIN_PATH . 'includes/ajax.php';
require_once SOLEX_PLUGIN_PATH . 'includes/shortcode.php';

function solex_enqueue_assets() {

    wp_enqueue_style(
        'solex-style',
        SOLEX_PLUGIN_URL . 'assets/css/style.css',
        [],
        time()
    );

    wp_enqueue_script(
        'solex-script',
        SOLEX_PLUGIN_URL . 'assets/js/script.js',
        ['jquery'],
        time(),
        true
    );

    wp_localize_script('solex-script', 'solex_ajax', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}

add_action('wp_enqueue_scripts', 'solex_enqueue_assets');