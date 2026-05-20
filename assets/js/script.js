jQuery(document).ready(function ($) {

    /**
     * PREVENT MULTIPLE AJAX CALLS
     */

    let isLoadingJobs = false;
    let isLoadingDetail = false;



    /**
     * DETAIL SKELETON
     */

    function solexDetailSkeleton() {

        return `

            <div class="skeleton skeleton-title"></div>

            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>

            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>

            <div class="skeleton skeleton-button"></div>

        `;
    }



    /**
     * JOB CARD SKELETON
     */

    function solexCardSkeleton() {

        return `

            <div class="solex-job-card">

                <div class="skeleton skeleton-title"></div>

                <div class="skeleton skeleton-text"></div>
                <div class="skeleton skeleton-text"></div>

            </div>

            <div class="solex-job-card">

                <div class="skeleton skeleton-title"></div>

                <div class="skeleton skeleton-text"></div>
                <div class="skeleton skeleton-text"></div>

            </div>

        `;
    }



    /**
     * LOAD JOB DETAILS
     */

    $(document).on('click', '.solex-view-details', function () {

        /**
         * PREVENT MULTIPLE REQUESTS
         */

        if (isLoadingDetail) {
            return;
        }

        isLoadingDetail = true;



        let job_id = $(this).data('job-id');



        /**
         * VALIDATE JOB ID
         */

        if (!job_id) {

            isLoadingDetail = false;

            return;
        }



        /**
         * ACTIVE CARD
         */

        $('.solex-job-card').removeClass('active');

        $(this).closest('.solex-job-card').addClass('active');



        /**
         * SCROLL TO MAIN JOB SECTION
         * ONLY FOR DESKTOP / TABLET
         */

        if ($(window).width() > 767) {

            const targetSection = document.getElementById('job-listing-main-container');

            if (targetSection) {

                targetSection.scrollIntoView({

                    behavior: 'smooth',
                    block: 'start'

                });
            }
        }



        /**
         * MOBILE SCROLL
         */

        if ($(window).width() < 768) {

            window.scrollTo({

                top: $('#solex-job-detail').offset().top - 20,

                behavior: 'smooth'
            });
        }



        /**
         * SKELETON
         */

        $('.solex-job-detail-inner').html(

            solexDetailSkeleton()
        );



        /**
         * AJAX
         */

        $.ajax({

            url: solex_ajax.ajax_url,

            type: 'POST',

            dataType: 'json',

            data: {

                action: 'solex_get_job_detail',

                nonce: solex_ajax.nonce,

                job_id: job_id
            },

            success: function (response) {

                /**
                 * API ERROR
                 */

                if (!response.success || !response.data) {

                    $('.solex-job-detail-inner').html(`

                        <div class="solex-error-state">

                            <h3>
                                Failed to Load Job
                            </h3>

                            <p>
                                Please try again later.
                            </p>

                        </div>

                    `);

                    return;
                }



                /**
                 * RESPONSE DATA
                 */

                let job = response.data;



                /**
                 * DYNAMIC APPLY URL
                 */

                let applyUrl = `https://solexhcm.darwinbox.in/ms/candidatev2/main/careers/jobDetails/${job.job_id}`;



                /**
                 * APPLY BUTTON
                 */

                let applyButton = `

                    <a

                        href="${applyUrl}"

                        class="solex-apply-btn"

                        target="_blank"

                        rel="noopener noreferrer"

                    >

                        Apply Now

                    </a>

                `;



                /**
                 * FINAL HTML
                 */

                let html = `

                    <h2>

                        ${job.title || 'Untitled Job'}

                    </h2>



                    <div class="solex-detail-meta">

                        <span>
                            ${job.department || ''}
                        </span>

                        <span>
                            ${job.employee_type || ''}
                        </span>

                        <span>
                            ${job.location || ''}
                        </span>

                        <span>
                            ${job.experience_from || '0'}-${job.experience_to || '0'} Years
                        </span>

                    </div>



                    <div class="solex-detail-grid">

                        <div>

                            <strong>
                                Company:
                            </strong>

                            <span>
                                ${job.company || '-'}
                            </span>

                        </div>



                        <div>

                            <strong>
                                Grade:
                            </strong>

                            <span>
                                ${job.grade || '-'}
                            </span>

                        </div>



                        <div>

                            <strong>
                                Parent Department:
                            </strong>

                            <span>
                                ${job.parent_department || '-'}
                            </span>

                        </div>



                        <div>

                            <strong>
                                Status:
                            </strong>

                            <span>
                                ${job.job_status || '-'}
                            </span>

                        </div>

                    </div>



                    <div class="solex-openings-box">

                        ${job.total_positions || 1}

                        Open Position

                    </div>



                    <h4>
                        Full Location
                    </h4>

                    <p>
                        ${job.full_location || '-'}
                    </p>



                    <h4>
                        Job Description
                    </h4>



                    <div class="solex-description">

                        ${job.description || 'No description available.'}

                    </div>



                    ${applyButton}

                `;



                /**
                 * UPDATE HTML
                 */

                $('.solex-job-detail-inner').html(html);

            },



            /**
             * AJAX FAILURE
             */

            error: function () {

                $('.solex-job-detail-inner').html(`

                    <div class="solex-error-state">

                        <h3>
                            Something Went Wrong
                        </h3>

                        <p>
                            Unable to load job details.
                        </p>

                    </div>

                `);
            },



            complete: function () {

                isLoadingDetail = false;
            }
        });
    });



    /**
     * AUTO LOAD FIRST JOB
     */

    setTimeout(function () {

        $('.solex-view-details').first().trigger('click');

    }, 200);



    /**
     * AJAX PAGINATION
     */

    $(document).on('click', '.solex-page-btn', function (e) {

        e.preventDefault();



        /**
         * PREVENT MULTIPLE REQUESTS
         */

        if (isLoadingJobs) {
            return;
        }

        isLoadingJobs = true;



        let page = parseInt($(this).data('page')) || 1;



        /**
         * ACTIVE BUTTON
         */

        $('.solex-page-btn').removeClass('active');

        $(this).addClass('active');



        /**
         * SKELETON
         */

        $('#solex-jobs-container').html(

            solexCardSkeleton()
        );



        /**
         * AJAX
         */

        $.ajax({

            url: solex_ajax.ajax_url,

            type: 'POST',

            dataType: 'json',

            cache: false,

            data: {

                action: 'solex_load_jobs',

                nonce: solex_ajax.nonce,

                page: page
            },

            success: function (response) {

                /**
                 * API ERROR
                 */

                if (!response.success || !response.data) {

                    $('#solex-jobs-container').html(`

                        <div class="solex-error-state">

                            Failed to load jobs.

                        </div>

                    `);

                    return;
                }



                /**
                 * UPDATE JOB HTML
                 */

                $('#solex-jobs-container').html(

                    response.data.jobs || ''
                );



                /**
                 * UPDATE PAGINATION HTML
                 */

                $('#solex-pagination-container').html(

                    response.data.pagination || ''
                );



                /**
                 * AUTO LOAD FIRST JOB
                 */

                setTimeout(function () {

                    $('.solex-view-details')

                        .first()

                        .trigger('click');

                }, 100);
            },



            /**
             * AJAX FAILURE
             */

            error: function () {

                $('#solex-jobs-container').html(`

                    <div class="solex-error-state">

                        Unable to load jobs.

                    </div>

                `);
            },



            complete: function () {

                isLoadingJobs = false;
            }
        });
    });

});


/**
 * FRONTEND SYNC
 */

jQuery(document).on('click', '#solex-sync-btn', function () {

    const button = jQuery(this);

    button.text('Syncing...');

    jQuery.ajax({

        url: solex_ajax.ajax_url,

        type: 'POST',

        data: {
            action: 'solex_frontend_sync'
        },

        success: function (response) {

            if (response.success) {

                jQuery('.solex-sync-status').html(
                    '<div class="solex-success">' +
                    response.data.message +
                    '</div>'
                );

                location.reload();

            } else {

                jQuery('.solex-sync-status').html(
                    '<div class="solex-error">' +
                    response.data.message +
                    '</div>'
                );
            }

            button.text('Sync Jobs');
        },

        error: function () {

            button.text('Sync Jobs');

            jQuery('.solex-sync-status').html(
                '<div class="solex-error">AJAX Failed</div>'
            );
        }
    });
});