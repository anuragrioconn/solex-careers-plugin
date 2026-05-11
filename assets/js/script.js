jQuery(document).ready(function ($) {

    // JOB DETAIL CLICK

    $(document).on('click', '.solex-view-details', function () {

        let job_id = $(this).data('job-id');

        $('.solex-job-card').removeClass('active');

        $(this).closest('.solex-job-card').addClass('active');

        $('.solex-job-detail-inner').html(`

            <div class="skeleton skeleton-title"></div>

            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>

            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>

            <div class="skeleton skeleton-button"></div>

        `);

        $.ajax({

            url: solex_ajax.ajax_url,

            type: 'POST',

            data: {
                action: 'solex_get_job_detail',
                job_id: job_id
            },

            success: function (response) {

                let responsibilities = '';
                let qualifications = '';

                response.responsibilities.forEach(function (item) {
                    responsibilities += `<li>${item}</li>`;
                });

                response.qualifications.forEach(function (item) {
                    qualifications += `<li>${item}</li>`;
                });

                let html = `

                    <h2>${response.title}</h2>

                    <div class="solex-detail-meta">

                        <span>${response.department}</span>
                        <span>${response.type}</span>
                        <span>${response.location}</span>

                    </div>

                    <div class="solex-openings-box">
                        ${response.openings} Openings
                    </div>

                    <h4>Job Description</h4>

                    <p>${response.description}</p>

                    <h4>Responsibilities</h4>

                    <ul>${responsibilities}</ul>

                    <h4>Qualifications</h4>

                    <ul>${qualifications}</ul>

                    <button class="solex-apply-btn">
                        Apply Now
                    </button>

                `;

                $('.solex-job-detail-inner').html(html);
                // MOBILE AUTO SCROLL

                if ($(window).width() < 768) {

                    $('html, body').animate({

                        scrollTop: $('#solex-job-detail').offset().top - 20

                    }, 500);

                }
            }
        });

    });



    // AUTO LOAD FIRST JOB

    $('.solex-view-details').first().trigger('click');



    // AJAX PAGINATION

    $(document).on('click', '.solex-page-btn', function () {

        let page = $(this).data('page');

        $('#solex-jobs-container').html(`

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

        `);

        $.ajax({

            url: solex_ajax.ajax_url,

            type: 'POST',

            data: {
                action: 'solex_load_jobs',
                page: page
            },

            success: function (response) {

                $('#solex-jobs-container').html(response.jobs);

                $('#solex-pagination-container').html(response.pagination);

                $('.solex-view-details').first().trigger('click');

            }
        });

    });

});