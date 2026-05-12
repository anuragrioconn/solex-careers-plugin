<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * SYNC JOBS
 */

function solex_sync_jobs() {

    /**
     * FETCH JOB LIST
     */

    $job_list = solex_fetch_job_list();



    /**
     * API FAILURE
     */

    if (

        empty($job_list['success'])

        ||

        empty($job_list['data'])

    ) {

        update_option('solex_jobs_sync_status', 'failed');

        update_option(

            'solex_jobs_sync_message',

            $job_list['message'] ?? 'Unknown API Error'
        );

        return false;
    }



    /**
     * GET JOB ARRAY
     */

    $job_items = $job_list['data']['data'] ?? [];

    if (empty($job_items)) {

        update_option('solex_jobs_sync_status', 'failed');

        update_option(

            'solex_jobs_sync_message',

            'No jobs returned from API'
        );

        return false;
    }



    /**
     * NORMALIZED JOBS
     */

    $jobs = [];



    /**
     * LOOP JOBS
     */

    foreach ($job_items as $job) {

        /**
         * VALIDATE JOB ID
         */

        $job_id = $job['job_id'] ?? '';

        if (empty($job_id)) {
            continue;
        }



        /**
         * FETCH JOB DETAIL
         */

        $detail = solex_fetch_job_detail($job_id);

        if (

            empty($detail['success'])

            ||

            empty($detail['data'])

        ) {

            continue;
        }



        /**
         * DETAIL DATA
         */

        $detail_data = $detail['data']['data'] ?? [];



        /**
         * NORMALIZE JOB
         */

        $normalized_job = [

            'job_id' => sanitize_text_field($job_id),

            'title' => sanitize_text_field(

                $detail_data['job_title'] ?? 'Untitled Job'
            ),

            'department' => sanitize_text_field(

                $detail_data['department'] ?? ''
            ),

            'location' => sanitize_text_field(

                $detail_data['location'] ?? ''
            ),

            'type' => sanitize_text_field(

                $detail_data['employment_type'] ?? ''
            ),

            'description' => wp_kses_post(

                $detail_data['job_description'] ?? ''
            ),

            'responsibilities' =>

                $detail_data['roles_responsibilities'] ?? [],

            'qualifications' =>

                $detail_data['skills'] ?? [],

            'apply_url' => esc_url_raw(

                $detail_data['apply_url'] ?? ''
            ),

            'openings' => intval(

                $detail_data['no_of_openings'] ?? 1
            ),

            'updated_at' => current_time('mysql'),

            'raw' => $detail_data
        ];



        /**
         * ADD JOB
         */

        $jobs[$job_id] = $normalized_job;
    }



    /**
     * FINAL VALIDATION
     */

    if (empty($jobs)) {

        update_option('solex_jobs_sync_status', 'failed');

        update_option(

            'solex_jobs_sync_message',

            'No valid jobs synced'
        );

        return false;
    }



    /**
     * SORT JOBS
     */

    $jobs = array_values($jobs);



    /**
     * STORE JOBS
     */

    update_option('solex_jobs_data', $jobs);

    update_option(

        'solex_jobs_last_sync',

        current_time('mysql')
    );

    update_option(

        'solex_jobs_sync_status',

        'success'
    );

    update_option(

        'solex_jobs_sync_message',

        'Jobs synced successfully'
    );

    update_option(

        'solex_jobs_total',

        count($jobs)
    );



    return true;
}