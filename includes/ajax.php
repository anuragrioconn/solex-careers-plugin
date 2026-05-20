<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * JOB DETAIL AJAX
 */

add_action(

    'wp_ajax_solex_get_job_detail',

    'solex_get_job_detail_callback'
);

add_action(

    'wp_ajax_nopriv_solex_get_job_detail',

    'solex_get_job_detail_callback'
);

function solex_get_job_detail_callback()
{

    /**
     * VALIDATE NONCE
     */

    check_ajax_referer(

        'solex_nonce',

        'nonce'
    );



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

add_action(

    'wp_ajax_solex_load_jobs',

    'solex_load_jobs_callback'
);

add_action(

    'wp_ajax_nopriv_solex_load_jobs',

    'solex_load_jobs_callback'
);

function solex_load_jobs_callback()
{

    /**
     * VALIDATE NONCE
     */

    check_ajax_referer(

        'solex_nonce',

        'nonce'
    );



    /**
     * PAGINATION
     */

    $page = isset($_POST['page'])

        ? max(1, intval($_POST['page']))

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
     * RESET ARRAY INDEXES
     */

    $jobs = array_values($jobs);



    /**
     * CALCULATE PAGINATION
     */

    $total_jobs  = count($jobs);

    $total_pages = ceil($total_jobs / $per_page);

    $offset      = ($page - 1) * $per_page;

    $paged_jobs  = array_slice($jobs, $offset, $per_page);



    /**
     * JOB LIST HTML
     */

    ob_start();

    foreach ($paged_jobs as $job) :

        /**
         * SAFE VALUES
         */

        $job_id          = $job['job_id'] ?? '';

        $title           = $job['title'] ?? 'Untitled Job';

        $department      = $job['department'] ?? '';

        $location        = $job['location'] ?? '';

        $experience_from = $job['experience_from'] ?? '0';

        $experience_to   = $job['experience_to'] ?? '0';

        $employee_type   = $job['employee_type'] ?? 'Full Time';

        $company         = $job['company'] ?? 'Solex Energy';

        $openings        = $job['total_positions'] ?? 1;

?>

        <div

            class="solex-job-card solex-view-details"

            data-job-id="<?php echo esc_attr($job_id); ?>"

        >

            <div class="solex-job-top">

                <div class="solex-icon">

                    <i class="fa-solid fa-briefcase"></i>

                </div>

                <div class="solex-job-info">

                    <h3>

                        <?php echo esc_html($title); ?>

                    </h3>



                    <?php if (!empty($department)) : ?>

                        <div class="solex-meta">

                            <span>

                                <?php echo esc_html($department); ?>

                            </span>

                        </div>

                    <?php endif; ?>



                    <?php if (!empty($location)) : ?>

                        <div class="solex-location">

                            <i class="fa-solid fa-location-dot"></i>

                            <?php echo esc_html($location); ?>

                        </div>

                    <?php endif; ?>



                    <div class="solex-job-tags">

                        <span class="solex-tag">

                            <i class="fa-solid fa-hourglass-half"></i>

                            <?php echo esc_html($experience_from); ?>

                            -

                            <?php echo esc_html($experience_to); ?>

                            Years

                        </span>



                        <?php if (!empty($employee_type)) : ?>

                            <span class="solex-tag">

                                <i class="fa-solid fa-user-tie"></i>

                                <?php echo esc_html($employee_type); ?>

                            </span>

                        <?php endif; ?>

                    </div>



                    <?php if (!empty($company)) : ?>

                        <div class="solex-company">

                            <i class="fa-solid fa-building"></i>

                            <?php echo esc_html($company); ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>



            <div class="solex-job-footer">

                <div class="solex-openings">

                    <?php echo esc_html($openings); ?>

                    Opening<?php echo ($openings > 1) ? 's' : ''; ?>

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

                data-page="<?php echo esc_attr($i); ?>"

            >

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





/**
 * FRONTEND SYNC
 */

add_action(

    'wp_ajax_solex_frontend_sync',

    'solex_frontend_sync_callback'
);

add_action(

    'wp_ajax_nopriv_solex_frontend_sync',

    'solex_frontend_sync_callback'
);

function solex_frontend_sync_callback()
{

    /**
     * VALIDATE NONCE
     */

    check_ajax_referer(

        'solex_nonce',

        'nonce'
    );



    /**
     * RUN SYNC
     */

    $result = solex_sync_jobs();



    /**
     * FAILED
     */

    if (!$result) {

        wp_send_json_error([

            'message' => 'Sync failed'
        ]);
    }



    /**
     * UPDATE LAST SYNC
     */

    update_option(

        'solex_jobs_last_sync',

        current_time('mysql')
    );



    /**
     * SUCCESS
     */

    wp_send_json_success([

        'message' => 'Jobs synced successfully',

        'last_sync' => current_time('mysql')
    ]);
}