<?php
/**
 * Plugin Name: Solex Careers
 * Plugin URI: https://solex.in
 * Description: Dynamic careers listing and job detail plugin for Solex.
 * Version: 1.8.4
 * Author: Anurag
 * Text Domain: solex-careers
 */

if (!defined('ABSPATH')) {
    exit;
}



/**
 * PLUGIN CONSTANTS
 */

define('SOLEX_PLUGIN_VERSION', '1.8.4');

define('SOLEX_PLUGIN_URL', plugin_dir_url(__FILE__));

define('SOLEX_PLUGIN_PATH', plugin_dir_path(__FILE__));



/**
 * INCLUDE FILES
 */

require_once SOLEX_PLUGIN_PATH . 'includes/api.php';

require_once SOLEX_PLUGIN_PATH . 'includes/sync.php';

require_once SOLEX_PLUGIN_PATH . 'includes/admin.php';

require_once SOLEX_PLUGIN_PATH . 'includes/ajax.php';

require_once SOLEX_PLUGIN_PATH . 'includes/shortcode.php';

require_once SOLEX_PLUGIN_PATH . 'includes/helpers.php';



/**
 * PLUGIN ACTIVATION
 */

register_activation_hook(

    __FILE__,

    'solex_plugin_activate'
);

function solex_plugin_activate() {

    /**
     * DEFAULT OPTIONS
     */

    if (!get_option('solex_jobs_data')) {

        update_option('solex_jobs_data', []);
    }

    if (!get_option('solex_jobs_sync_status')) {

        update_option('solex_jobs_sync_status', 'not_synced');
    }
}



/**
 * ENQUEUE FRONTEND ASSETS
 */

function solex_enqueue_assets() {

    /**
     * GOOGLE FONTS
     */

    wp_enqueue_style(

        'solex-google-fonts',

        'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Roboto:wght@300;400;500;700&display=swap',

        [],

        null
    );

    wp_enqueue_style(

        'solex-fontawesome-6',

        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',

        [],

        '6.5.1'
    );


    /**
     * MAIN CSS
     */

    wp_enqueue_style(

        'solex-style',

        SOLEX_PLUGIN_URL . 'assets/css/style.css',

        [],

        SOLEX_PLUGIN_VERSION
    );



    /**
     * MAIN JS
     */

    wp_enqueue_script(

        'solex-script',

        SOLEX_PLUGIN_URL . 'assets/js/script.js',

        ['jquery'],

        SOLEX_PLUGIN_VERSION,

        true
    );



    /**
     * LOCALIZE
     */

    wp_localize_script(

        'solex-script',

        'solex_ajax',

        [

            'ajax_url' => admin_url('admin-ajax.php'),

            'nonce' => wp_create_nonce('solex_nonce')
        ]
    );
}

add_action(

    'wp_enqueue_scripts',

    'solex_enqueue_assets'
);