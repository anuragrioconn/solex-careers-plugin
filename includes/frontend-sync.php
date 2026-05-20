<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * FRONTEND SYNC SHORTCODE
 */

add_shortcode(

    'solex_sync_dashboard',

    'solex_sync_dashboard_shortcode'
);

function solex_sync_dashboard_shortcode()
{

    ob_start();



    /**
     * CENTRALIZED DATA
     */

    $last_sync = solex_get_last_sync();

    $total_jobs = solex_get_total_jobs();

    $sync_status = solex_get_sync_status();

    $sync_message = solex_get_sync_message();



    /**
     * STATUS LABEL
     */

    $status_class = 'solex-status-pending';

    $status_text = 'Not Synced';



    if ($sync_status === 'success') {

        $status_class = 'solex-status-success';

        $status_text = 'Synced';
    }

    elseif ($sync_status === 'failed') {

        $status_class = 'solex-status-failed';

        $status_text = 'Failed';
    }

?>



    <div class="solex-sync-dashboard">

        <div class="solex-sync-header">

            <div>

                <h2>

                    Career Sync Dashboard

                </h2>

                <p>

                    Sync and manage career listings in real-time.

                </p>

            </div>



            <button id="solex-sync-btn">

                <i class="fa-solid fa-rotate"></i>

                Sync Jobs

            </button>

        </div>



        <div class="solex-sync-grid">

            <div class="solex-sync-box">

                <div class="solex-sync-icon">

                    <i class="fa-solid fa-briefcase"></i>

                </div>

                <div>

                    <span>Total Jobs</span>

                    <h3>

                        <?php echo esc_html($total_jobs); ?>

                    </h3>

                </div>

            </div>



            <div class="solex-sync-box">

                <div class="solex-sync-icon">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div>

                    <span>Last Sync</span>

                    <h3>

                        <?php echo esc_html($last_sync); ?>

                    </h3>

                </div>

            </div>



            <div class="solex-sync-box">

                <div class="solex-sync-icon">

                    <i class="fa-solid fa-signal"></i>

                </div>

                <div>

                    <span>Status</span>

                    <h3 class="<?php echo esc_attr($status_class); ?>">

                        <?php echo esc_html($status_text); ?>

                    </h3>

                </div>

            </div>

        </div>



        <?php if (!empty($sync_message)) : ?>

            <div class="solex-sync-message">

                <?php echo esc_html($sync_message); ?>

            </div>

        <?php endif; ?>



        <div class="solex-sync-status"></div>

    </div>

<?php

    return ob_get_clean();
}