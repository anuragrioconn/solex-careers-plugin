<?php

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('solex_jobs', 'solex_jobs_shortcode');

function solex_jobs_shortcode()
{

    $jobs = solex_get_jobs_data();

    ob_start();

?>

    <div class="solex-careers-wrapper">

        <!-- LEFT SIDE -->

        <div class="solex-job-list">

            <div id="solex-jobs-container">

                <?php

                $per_page = 5;

                $page = 1;

                $offset = 0;

                $paged_jobs = array_slice($jobs, $offset, $per_page);

                ?>

                <?php foreach ($paged_jobs as $job) : ?>

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

                <?php endforeach; ?>

            </div>

            <?php

            $total_jobs = count($jobs);

            $total_pages = ceil($total_jobs / $per_page);

            ?>

            <div id="solex-pagination-container">

                <div class="solex-pagination">

                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>

                        <button
                            class="solex-page-btn <?php echo ($page == $i) ? 'active' : ''; ?>"
                            data-page="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </button>

                    <?php endfor; ?>

                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->

        <div class="solex-job-detail">

            <div class="solex-job-detail-inner">

                <h2>Select a Job</h2>

                <p>
                    Click on any job to view details.
                </p>

            </div>

        </div>

    </div>

<?php

    return ob_get_clean();
}
