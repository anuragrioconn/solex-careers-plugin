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

        update_option(

            'solex_jobs_sync_status',

            'failed'
        );

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

        update_option(

            'solex_jobs_sync_status',

            'failed'
        );

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
         * FIX DESCRIPTION
         */

        $raw_description =

            $detail_data['job_decription']

            ??

            $detail_data['job_description']

            ??

            '';



        $decoded_description = html_entity_decode($raw_description);

        $clean_description = trim(

            wp_strip_all_tags($decoded_description)
        );



        /**
         * EMPTY DESCRIPTION FALLBACK
         */

        if (empty($clean_description)) {

            $decoded_description = '

                <p>
                    Detailed job description will be shared during the hiring process.
                </p>

            ';
        }



        /**
         * NORMALIZE JOB
         */

        $normalized_job = [

            'job_id' => sanitize_text_field($job_id),

            'title' => sanitize_text_field(
                $detail_data['job_title'] ?? 'Untitled Job'
            ),

            'company' => sanitize_text_field(
                $detail_data['group_company'] ?? ''
            ),

            'department' => sanitize_text_field(
                $detail_data['department'] ?? ''
            ),

            'parent_department' => sanitize_text_field(
                $detail_data['parent_department'] ?? ''
            ),

            'designation' => sanitize_text_field(
                $detail_data['designation'] ?? ''
            ),

            'employee_type' => sanitize_text_field(
                $detail_data['employee_type'] ?? ''
            ),

            'grade' => sanitize_text_field(
                $detail_data['grade'] ?? ''
            ),

            'experience_from' => sanitize_text_field(
                $detail_data['experience_from'] ?? ''
            ),

            'experience_to' => sanitize_text_field(
                $detail_data['experience_to'] ?? ''
            ),

            'experience_unit' => sanitize_text_field(
                $detail_data['unit_experience'] ?? 'Years'
            ),

            'location' => !empty($detail_data['location_city'][0])

                ? sanitize_text_field(
                    $detail_data['location_city'][0]
                )

                : '',

            'full_location' => !empty($detail_data['location'][0])

                ? sanitize_text_field(
                    $detail_data['location'][0]
                )

                : '',

            'country' => sanitize_text_field(
                $detail_data['location_country'] ?? ''
            ),

            'description' => wp_kses_post(
                $decoded_description
            ),

            'total_positions' => intval(
                $detail_data['total_positions'] ?? 0
            ),

            'open_positions' => intval(
                $detail_data['open_positions'] ?? 0
            ),

            'job_status' => sanitize_text_field(
                $detail_data['job_status'] ?? ''
            ),

            'created_at' => sanitize_text_field(
                $detail_data['job_created_timestamp'] ?? ''
            ),

            'updated_at' => sanitize_text_field(
                $detail_data['job_updated_timestamp'] ?? ''
            )

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

        update_option(

            'solex_jobs_sync_status',

            'failed'
        );

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

    update_option(

        'solex_jobs_data',

        $jobs
    );



    /**
     * UPDATE LAST SYNC
     */

    update_option(

        'solex_jobs_last_sync',

        wp_date(

            'd M Y, h:i A',

            current_time('timestamp')
        )
    );



    /**
     * UPDATE STATUS
     */

    update_option(

        'solex_jobs_sync_status',

        'success'
    );



    /**
     * UPDATE MESSAGE
     */

    update_option(

        'solex_jobs_sync_message',

        'Jobs synced successfully'
    );



    /**
     * UPDATE TOTAL
     */

    update_option(

        'solex_jobs_total',

        count($jobs)
    );



    return true;
}