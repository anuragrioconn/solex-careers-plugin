<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * FRONTEND SYNC SHORTCODE
 */

add_shortcode('solex_sync_dashboard', 'solex_sync_dashboard_shortcode');

function solex_sync_dashboard_shortcode()
{
    ob_start();

    $last_sync = get_option('solex_last_sync');
    $jobs      = get_option('solex_jobs_data', []);

    ?>

    <div class="solex-sync-dashboard">

        <div class="solex-sync-card">

            <h2>Career Sync Dashboard</h2>

            <button id="solex-sync-btn">
                Sync Jobs
            </button>

            <div class="solex-sync-status"></div>

            <div class="solex-sync-meta">

                <p>
                    <strong>Last Sync:</strong>
                    <?php echo $last_sync ? $last_sync : 'Never'; ?>
                </p>

                <p>
                    <strong>Total Jobs:</strong>
                    <?php echo count($jobs); ?>
                </p>

            </div>

        </div>

    </div>

    <?php

    return ob_get_clean();
}