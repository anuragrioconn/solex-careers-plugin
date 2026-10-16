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

  $(document).on("click", ".solex-view-details", function () {
    if (isLoadingDetail) {
      return;
    }

    isLoadingDetail = true;

    let job_id = $(this).data("job-id");

    if (!job_id) {
      isLoadingDetail = false;

      return;
    }

    $(".solex-job-card").removeClass("active");

    $(this).closest(".solex-job-card").addClass("active");

    $(".solex-job-detail-inner").html(solexDetailSkeleton());

    $.ajax({
      url: solex_ajax.ajax_url,

      type: "POST",

      dataType: "json",

      data: {
        action: "solex_get_job_detail",

        nonce: solex_ajax.nonce,

        job_id: job_id,
      },

      success: function (response) {
        if (!response.success || !response.data) {
          $(".solex-job-detail-inner").html(`

                        <div class="solex-error-state">

                            Failed to load job.

                        </div>

                    `);

          return;
        }

        let job = response.data;

        let applyUrl = `https://solexhcm.darwinbox.in/ms/candidatev2/main/careers/jobDetails/${job.job_id}`;

        let html = `

                    <h2>${job.title || "Untitled Job"}</h2>

                    <div class="solex-detail-meta">

                        <span>${job.department || ""}</span>

                        <span>${job.location || ""}</span>

                    </div>

                    <div class="solex-description">

                        ${job.description || ""}

                    </div>

                    <a

                        href="${applyUrl}"

                        class="solex-apply-btn"

                        target="_blank"

                    >

                        Apply Now

                    </a>

                `;

        $(".solex-job-detail-inner").html(html);
      },

      error: function () {
        $(".solex-job-detail-inner").html(`

                    <div class="solex-error-state">

                        Unable to load job.

                    </div>

                `);
      },

      complete: function () {
        isLoadingDetail = false;
      },
    });
  });

  /**
   * AUTO LOAD FIRST JOB
   */

  setTimeout(function () {
    $(".solex-view-details").first().trigger("click");
  }, 200);

  /**
   * LOAD JOBS (PAGINATION + DEPARTMENT FILTER)
   */
  function solexLoadJobs(page) {
    if (isLoadingJobs) {
      return;
    }

    isLoadingJobs = true;

    const department = $("#solex-department-select").val() || "";

    $(".solex-page-btn").removeClass("active");
    $("#solex-jobs-container").html(solexCardSkeleton());

    $.ajax({
      url: solex_ajax.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "solex_load_jobs",
        nonce: solex_ajax.nonce,
        page: page,
        department: department
      },
      success: function (response) {
        if (!response.success || !response.data) {
          $("#solex-jobs-container").html(
            '<div class="solex-error-state">Failed to load jobs.</div>'
          );
          $("#solex-pagination-container").empty();
          return;
        }

        $("#solex-jobs-container").html(response.data.jobs || "");
        $("#solex-pagination-container").html(response.data.pagination || "");

        if ($("#solex-jobs-container .solex-view-details").length) {
          setTimeout(function () {
            $("#solex-jobs-container .solex-view-details").first().trigger("click");
          }, 100);
        } else {
          $(".solex-job-detail-inner").html(
            '<div class="solex-empty-state"><h3>No Job Selected</h3><p>Select another department to view available positions.</p></div>'
          );
        }
      },
      error: function () {
        $("#solex-jobs-container").html(
          '<div class="solex-error-state">Unable to load jobs.</div>'
        );
        $("#solex-pagination-container").empty();
      },
      complete: function () {
        isLoadingJobs = false;
      }
    });
  }

  $(document).on("click", ".solex-page-btn", function (e) {
    e.preventDefault();
    const page = parseInt($(this).data("page"), 10) || 1;
    solexLoadJobs(page);
  });

  $(document).on("change", "#solex-department-select", function () {
    solexLoadJobs(1);
  });

  /**
   * FRONTEND SYNC
   */

  $(document).on("click", "#solex-sync-btn", function (e) {
    e.preventDefault();

    const button = $(this);

    /**
     * PREVENT MULTIPLE CLICKS
     */

    if (button.hasClass("syncing")) {
      return;
    }

    button.addClass("syncing");

    /**
     * LOADING STATE
     */

    button.html(`

        <i class="fa-solid fa-rotate fa-spin"></i>

        Syncing...
    `);

    button.prop("disabled", true);

    /**
     * CLEAR OLD STATUS
     */

    $(".solex-sync-status").html("");

    /**
     * AJAX REQUEST
     */

    $.ajax({
      url: solex_ajax.ajax_url,

      type: "POST",

      dataType: "json",

      data: {
        action: "solex_frontend_sync",
      },

      success: function (response) {
        /**
         * SUCCESS
         */

        if (response.success) {
          $(".solex-sync-status").html(`

                    <div class="solex-success">

                        ${response.data.message}

                    </div>

                `);

          /**
           * RELOAD DASHBOARD
           */

          setTimeout(function () {
            location.reload();
          }, 1200);
        } else {
          $(".solex-sync-status").html(`

                    <div class="solex-error">

                        ${response.data.message || "Sync failed"}

                    </div>

                `);
        }
      },

      /**
       * AJAX ERROR
       */

      error: function (xhr) {
        console.log(xhr.responseText);

        $(".solex-sync-status").html(`

                <div class="solex-error">

                    AJAX request failed

                </div>

            `);
      },

      /**
       * COMPLETE
       */

      complete: function () {
        button.removeClass("syncing");

        button.prop("disabled", false);

        button.html(`

                <i class="fa-solid fa-rotate"></i>

                Sync Jobs
            `);
      },
    });
  });
});
