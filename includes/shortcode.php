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

                    <div

                        class="solex-job-card solex-view-details"

                        data-job-id="<?php echo esc_attr($job['job_id']); ?>"

                    >

                        <div class="solex-job-top">

                            <div class="solex-icon">
                                💼
                            </div>

                            <div class="solex-job-info">

                                <h3>

                                    <?php

                                    echo esc_html(

                                        $job['title'] ?? 'Untitled Job'
                                    );

                                    ?>

                                </h3>



                                <div class="solex-meta">

                                    <span>

                                        <?php

                                        echo esc_html(

                                            $job['department'] ?? 'Department'
                                        );

                                        ?>

                                    </span>

                                </div>



                                <div class="solex-location">

                                    📍

                                    <?php

                                    echo esc_html(

                                        $job['location'] ?? 'Location'
                                    );

                                    ?>

                                </div>



                                <div class="solex-job-tags">

                                    <span class="solex-tag">

                                        ⭐

                                        <?php

                                        echo esc_html(

                                            $job['experience_from'] ?? '0'
                                        );

                                        ?>

                                        -

                                        <?php

                                        echo esc_html(

                                            $job['experience_to'] ?? '0'
                                        );

                                        ?>

                                        Years

                                    </span>



                                    <span class="solex-tag">

                                        💼

                                        <?php

                                        echo esc_html(

                                            $job['employee_type'] ?? 'Full Time'
                                        );

                                        ?>

                                    </span>

                                </div>



                                <div class="solex-company">

                                    🏢

                                    <?php

                                    echo esc_html(

                                        $job['company'] ?? 'Solex Energy'
                                    );

                                    ?>

                                </div>

                            </div>

                        </div>



                        <div class="solex-job-footer">

                            <div class="solex-openings">

                                <?php

                                echo esc_html(

                                    $job['total_positions'] ?? 1
                                );

                                ?>

                                Opening

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