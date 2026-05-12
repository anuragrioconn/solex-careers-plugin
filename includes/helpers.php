<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * GET ALL JOBS
 */

function solex_get_jobs() {

    $jobs = get_option('solex_jobs_data', []);

    return is_array($jobs) ? $jobs : [];
}





/**
 * GET SINGLE JOB
 */

function solex_get_job($job_id) {

    $jobs = solex_get_jobs();

    foreach ($jobs as $job) {

        if (

            isset($job['job_id'])

            &&

            $job['job_id'] == $job_id

        ) {

            return $job;
        }
    }

    return false;
}





/**
 * GET SYNC STATUS
 */

function solex_get_sync_status() {

    return get_option(

        'solex_jobs_sync_status',

        'not_synced'
    );
}





/**
 * GET LAST SYNC
 */

function solex_get_last_sync() {

    return get_option(

        'solex_jobs_last_sync',

        'Never'
    );
}





/**
 * GET TOTAL JOBS
 */

function solex_get_total_jobs() {

    return count(solex_get_jobs());
}





/**
 * SAFE ARRAY VALUE
 */

function solex_array_get(

    $array,

    $key,

    $default = ''

) {

    return isset($array[$key])

        ? $array[$key]

        : $default;
}





/**
 * FORMAT OPENINGS
 */

function solex_format_openings($count) {

    $count = intval($count);

    return $count . ' Opening' . ($count > 1 ? 's' : '');
}





/**
 * LIMIT TEXT
 */

function solex_limit_text(

    $text,

    $limit = 20

) {

    $words = explode(' ', wp_strip_all_tags($text));

    if (count($words) <= $limit) {

        return $text;
    }

    return implode(

        ' ',

        array_slice($words, 0, $limit)

    ) . '...';
}