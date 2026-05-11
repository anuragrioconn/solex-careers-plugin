<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_solex_get_job_detail', 'solex_get_job_detail_callback');
add_action('wp_ajax_nopriv_solex_get_job_detail', 'solex_get_job_detail_callback');

function solex_get_job_detail_callback()
{

    $job_id = intval($_POST['job_id']);

    $jobs = solex_get_jobs_data();

    $selected_job = null;

    foreach ($jobs as $job) {

        if ($job['job_id'] == $job_id) {
            $selected_job = $job;
        }
    }

    wp_send_json($selected_job);
}

// For pagination (if needed in future)
add_action('wp_ajax_solex_load_jobs', 'solex_load_jobs_callback');
add_action('wp_ajax_nopriv_solex_load_jobs', 'solex_load_jobs_callback');

function solex_load_jobs_callback()
{

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $jobs = solex_get_jobs_data();

    $per_page = 5;

    $total_jobs = count($jobs);

    $total_pages = ceil($total_jobs / $per_page);

    $offset = ($page - 1) * $per_page;

    $paged_jobs = array_slice($jobs, $offset, $per_page);

    ob_start();

    foreach ($paged_jobs as $job) :

?>

        <div class="solex-job-card solex-view-details" data-job-id="<?php echo esc_attr($job['job_id']); ?>">

            <div class="solex-job-top">

                <div class="solex-icon">
                    💼
                </div>

                <div class="solex-job-info">

                    <h3>
                        <?php echo esc_html($job['title']); ?>
                    </h3>

                    <div class="solex-meta">

                        <span>
                            <?php echo esc_html($job['department']); ?>
                        </span>

                        <span>•</span>

                        <span>
                            <?php echo esc_html($job['type']); ?>
                        </span>

                    </div>

                    <div class="solex-location">
                        📍 <?php echo esc_html($job['location']); ?>
                    </div>

                </div>

            </div>

            <div class="solex-job-footer">

                <div class="solex-openings">
                    <?php echo esc_html($job['openings']); ?> Openings
                </div>

                <div class="solex-card-arrow">
                    →
                </div>

            </div>

        </div>

    <?php

    endforeach;

    $jobs_html = ob_get_clean();

    ob_start();

    ?>

    <div class="solex-pagination">

        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>

            <button
                class="solex-page-btn <?php echo ($page == $i) ? 'active' : ''; ?>"
                data-page="<?php echo $i; ?>">
                <?php echo $i; ?>
            </button>

        <?php endfor; ?>

    </div>

<?php

    $pagination_html = ob_get_clean();

    wp_send_json([
        'jobs' => $jobs_html,
        'pagination' => $pagination_html
    ]);
}
