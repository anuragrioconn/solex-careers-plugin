<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */

add_action('admin_menu', 'solex_register_admin_menu');

function solex_register_admin_menu() {

    add_menu_page(

        'Solex Careers',

        'Solex Careers',

        'manage_options',

        'solex-careers',

        'solex_careers_admin_page',

        'dashicons-businessperson',

        30
    );
}



/**
 * Admin Page
 */

function solex_careers_admin_page() {

    $sync_message = '';

    /**
     * MANUAL SYNC
     */

    if (

        isset($_POST['solex_sync_jobs'])

        &&

        check_admin_referer('solex_sync_jobs_action', 'solex_sync_jobs_nonce')

    ) {

        $sync = solex_sync_jobs();

        if ($sync) {

            $sync_message = '

                <div class="notice notice-success is-dismissible">

                    <p>
                        Jobs synced successfully.
                    </p>

                </div>

            ';
        }

        else {

            $sync_message = '

                <div class="notice notice-error is-dismissible">

                    <p>
                        Job sync failed. Please check API response.
                    </p>

                </div>

            ';
        }
    }



    /**
     * DATA
     */

    $last_sync = get_option('solex_jobs_last_sync');

    $sync_status = get_option('solex_jobs_sync_status');

    $jobs = get_option('solex_jobs_data', []);

    $total_jobs = is_array($jobs) ? count($jobs) : 0;

    ?>

    <div class="wrap">

        <h1 style="margin-bottom:20px;">

            Solex Careers Dashboard

        </h1>

        <?php echo $sync_message; ?>



        <div style="
            background:#fff;
            padding:24px;
            border-radius:16px;
            max-width:700px;
            box-shadow:0 2px 10px rgba(0,0,0,0.04);
        ">

            <table class="widefat striped" style="margin-bottom:24px;">

                <tbody>

                    <tr>

                        <td width="220">
                            <strong>Total Jobs</strong>
                        </td>

                        <td>
                            <?php echo esc_html($total_jobs); ?>
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Last Sync</strong>
                        </td>

                        <td>

                            <?php

                            echo $last_sync

                                ? esc_html($last_sync)

                                : 'Never Synced';

                            ?>

                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Sync Status</strong>
                        </td>

                        <td>

                            <?php

                            if ($sync_status === 'success') {

                                echo '<span style="color:green;font-weight:600;">Success</span>';
                            }

                            elseif ($sync_status === 'failed') {

                                echo '<span style="color:red;font-weight:600;">Failed</span>';
                            }

                            else {

                                echo '<span>Not Synced Yet</span>';
                            }

                            ?>

                        </td>

                    </tr>

                </tbody>

            </table>



            <form method="post">

                <?php

                wp_nonce_field(

                    'solex_sync_jobs_action',

                    'solex_sync_jobs_nonce'
                );

                ?>

                <button

                    class="button button-primary button-large"

                    name="solex_sync_jobs"

                >

                    Sync Jobs Now

                </button>

            </form>

        </div>

    </div>

    <?php
}