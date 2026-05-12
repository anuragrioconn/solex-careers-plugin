jQuery(document).ready(function ($) {

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

        let job_id = $(this).data('job-id');



        /**
         * ACTIVE CARD
         */

        $('.solex-job-card').removeClass('active');

        $(this).closest('.solex-job-card').addClass('active');



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

                if (!response.success) {

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
                 * APPLY BUTTON
                 */

                let applyButton = `

                    <button class="solex-apply-btn">

                        Apply Now

                    </button>

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
                                Company
                            </strong>

                            <span>
                                ${job.company || '-'}
                            </span>

                        </div>



                        <div>

                            <strong>
                                Grade
                            </strong>

                            <span>
                                ${job.grade || '-'}
                            </span>

                        </div>



                        <div>

                            <strong>
                                Parent Department
                            </strong>

                            <span>
                                ${job.parent_department || '-'}
                            </span>

                        </div>



                        <div>

                            <strong>
                                Status
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
            }
        });
    });



    /**
     * AUTO LOAD FIRST JOB
     */

    $('.solex-view-details').first().trigger('click');



    /**
     * AJAX PAGINATION
     */

    $(document).on('click', '.solex-page-btn', function () {

        let page = $(this).data('page');



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

            data: {

                action: 'solex_load_jobs',

                nonce: solex_ajax.nonce,

                page: page
            },

            success: function (response) {

                /**
                 * API ERROR
                 */

                if (!response.success) {

                    $('#solex-jobs-container').html(`

                        <div class="solex-error-state">

                            Failed to load jobs.

                        </div>

                    `);

                    return;
                }



                /**
                 * UPDATE HTML
                 */

                $('#solex-jobs-container').html(

                    response.data.jobs
                );

                $('#solex-pagination-container').html(

                    response.data.pagination
                );



                /**
                 * AUTO LOAD FIRST JOB
                 */

                $('.solex-view-details')

                    .first()

                    .trigger('click');
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
            }
        });
    });

});