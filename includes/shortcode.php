<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * SHORTCODE
 */

add_shortcode('solex_jobs', 'solex_jobs_shortcode');

function solex_jobs_shortcode() {

    /**
     * GET LOCAL JOBS
     */

    $jobs = solex_get_jobs();



    /**
     * EMPTY STATE
     */

    if (empty($jobs)) {

        return '

            <div class="solex-empty-state">

                <h3>
                    No Open Positions Currently
                </h3>

                <p>
                    Please check back later for future opportunities.
                </p>

            </div>

        ';
    }



    /**
     * RESET ARRAY INDEXES
     */

    $jobs = array_values($jobs);



    /**
     * PAGINATION
     */

    $per_page = 5;

    $page = 1;

    $offset = 0;

    $paged_jobs = array_slice($jobs, $offset, $per_page);

    $total_jobs = count($jobs);

    $total_pages = ceil($total_jobs / $per_page);



    ob_start();

?>

    <div class="solex-careers-wrapper">

        <!-- LEFT SIDE -->

        <div class="solex-job-list">

            <div id="solex-jobs-container">

                <?php foreach ($paged_jobs as $job) : ?>

                    <?php

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

                <?php endforeach; ?>

            </div>



            <!-- PAGINATION -->

            <div id="solex-pagination-container">

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

            </div>

        </div>



        <!-- RIGHT SIDE -->

        <div

            class="solex-job-detail"

            id="solex-job-detail"

        >

            <div class="solex-job-detail-inner">

                <h2>
                    Select a Job
                </h2>

                <p>
                    Click on any job to view details.
                </p>

            </div>

        </div>

    </div>

<?php

    return ob_get_clean();
}