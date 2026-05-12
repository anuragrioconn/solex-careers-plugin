<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * JOB DETAIL AJAX
 */

add_action('wp_ajax_solex_get_job_detail', 'solex_get_job_detail_callback');

add_action('wp_ajax_nopriv_solex_get_job_detail', 'solex_get_job_detail_callback');

function solex_get_job_detail_callback()
{

    /**
     * VALIDATE JOB ID
     */

    $job_id = isset($_POST['job_id'])

        ? sanitize_text_field($_POST['job_id'])

        : '';

    if (empty($job_id)) {

        wp_send_json_error([
            'message' => 'Invalid Job ID'
        ]);
    }



    /**
     * GET LOCAL JOBS
     */

    $jobs = solex_get_jobs();

    if (empty($jobs)) {

        wp_send_json_error([
            'message' => 'No jobs found'
        ]);
    }



    /**
     * FIND JOB
     */

    $selected_job = solex_get_job($job_id);





    /**
     * RETURN RESPONSE
     */

    if (!$selected_job) {

        wp_send_json_error([
            'message' => 'Job not found'
        ]);
    }

    wp_send_json_success($selected_job);
}





/**
 * AJAX PAGINATION
 */

add_action('wp_ajax_solex_load_jobs', 'solex_load_jobs_callback');

add_action('wp_ajax_nopriv_solex_load_jobs', 'solex_load_jobs_callback');

function solex_load_jobs_callback()
{

    /**
     * PAGINATION
     */

    $page = isset($_POST['page'])

        ? intval($_POST['page'])

        : 1;

    $per_page = 5;



    /**
     * GET LOCAL JOBS
     */

    $jobs = solex_get_jobs();

    if (empty($jobs)) {

        wp_send_json_error([
            'message' => 'No jobs available'
        ]);
    }



    /**
     * CALCULATE PAGINATION
     */

    $total_jobs = count($jobs);

    $total_pages = ceil($total_jobs / $per_page);

    $offset = ($page - 1) * $per_page;

    $paged_jobs = array_slice($jobs, $offset, $per_page);



    /**
     * JOB LIST HTML
     */

    ob_start();

    foreach ($paged_jobs as $job) :

?>

        <div

            class="solex-job-card solex-view-details"

            data-job-id="<?php echo esc_attr($job['job_id']); ?>">

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

                        📍

                        <?php echo esc_html($job['location']); ?>

                    </div>

                </div>

            </div>

            <div class="solex-job-footer">

                <div class="solex-openings">

                    <?php echo esc_html($job['openings']); ?>

                    Openings

                </div>

                <div class="solex-card-arrow">
                    →
                </div>

            </div>

        </div>

    <?php

    endforeach;

    $jobs_html = ob_get_clean();



    /**
     * PAGINATION HTML
     */

    ob_start();

    ?>

    <div class="solex-pagination">

        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>

            <button

                class="solex-page-btn <?php echo ($page == $i) ? 'active' : ''; ?>"

                data-page="<?php echo esc_attr($i); ?>">

                <?php echo esc_html($i); ?>

            </button>

        <?php endfor; ?>

    </div>

<?php

    $pagination_html = ob_get_clean();



    /**
     * RESPONSE
     */

    wp_send_json_success([

        'jobs' => $jobs_html,

        'pagination' => $pagination_html

    ]);
}
