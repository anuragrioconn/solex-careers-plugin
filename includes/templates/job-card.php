<?php

$job_id        = $job['job_id'] ?? '';
$title         = $job['title'] ?? 'Untitled Job';
$department    = $job['department'] ?? '';
$employee_type = $job['employee_type'] ?? '';
$location      = $job['location'] ?? '';
$company       = $job['company'] ?? '';
$experience_from = $job['experience_from'] ?? '';
$experience_to   = $job['experience_to'] ?? '';
$openings      = $job['open_positions'] ?? 0;

?>

<div class="solex-job-card solex-view-details"
     data-job-id="<?php echo esc_attr($job_id); ?>">

    <div class="solex-job-top">

        <div class="solex-icon">
            💼
        </div>

        <div class="solex-job-info">

            <h3>
                <?php echo esc_html($title); ?>
            </h3>

            <div class="solex-meta">

                <?php if ($department) : ?>
                    <span><?php echo esc_html($department); ?></span>
                <?php endif; ?>

                <?php if ($department && $employee_type) : ?>
                    <span>•</span>
                <?php endif; ?>

                <?php if ($employee_type) : ?>
                    <span><?php echo esc_html($employee_type); ?></span>
                <?php endif; ?>

            </div>

            <?php if ($location) : ?>

                <div class="solex-location">
                    📍 <?php echo esc_html($location); ?>
                </div>

            <?php endif; ?>

            <?php if ($experience_from || $experience_to) : ?>

                <div class="solex-experience">
                    ⏳ <?php echo esc_html($experience_from); ?>
                    -
                    <?php echo esc_html($experience_to); ?> Years
                </div>

            <?php endif; ?>

            <?php if ($company) : ?>

                <div class="solex-company">
                    🏢 <?php echo esc_html($company); ?>
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