<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * API CONFIG
 */

define('SOLEX_API_BASE_URL', 'https://solexhcm.darwinbox.in/JobsApiv3');

define('SOLEX_API_USERNAME', '2159');

define('SOLEX_API_PASSWORD', 'Pass@123');

define(

    'SOLEX_JOB_LIST_API_KEY',

    '357c7236fc5550ecae993a23f7b098f28a2a0f89c0dc7e44f292648c04c5d3d4cd5851e5e27a20794ec97c7809ed707bde13c13ca53e4257ed4199e0feb71d6a'
);

define(

    'SOLEX_JOB_DETAIL_API_KEY',

    'e3168fbd133605989d28533d174b70ae5b86ba9d40a312864b1cc63faba5e1dece924abb6b0cba1f1ee6ec26f90e9ebe529f6e196fbbaa352174ff94db4f306a'
);




/**
 * GENERIC API REQUEST
 */

function solex_api_request($endpoint, $body = []) {

    $response = wp_remote_post(

        SOLEX_API_BASE_URL . '/' . $endpoint,

        [

            'headers' => [

                'Authorization' => 'Basic ' . base64_encode(

                    SOLEX_API_USERNAME . ':' . SOLEX_API_PASSWORD
                ),

                'Content-Type' => 'application/json'
            ],

            'body' => wp_json_encode($body),

            'timeout' => 30
        ]
    );



    /**
     * WP ERROR
     */

    if (is_wp_error($response)) {

        return [

            'success' => false,

            'message' => $response->get_error_message()
        ];
    }



    /**
     * HTTP STATUS
     */

    $status_code = wp_remote_retrieve_response_code($response);

    if ($status_code !== 200) {

        return [

            'success' => false,

            'message' => 'API returned status code: ' . $status_code
        ];
    }



    /**
     * RESPONSE BODY
     */

    $body = wp_remote_retrieve_body($response);

    if (empty($body)) {

        return [

            'success' => false,

            'message' => 'Empty API response'
        ];
    }



    /**
     * DECODE JSON
     */

    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {

        return [

            'success' => false,

            'message' => 'Invalid JSON response'
        ];
    }



    /**
     * SUCCESS
     */

    return [

        'success' => true,

        'data' => $data
    ];
}





/**
 * FETCH JOB LIST
 */

function solex_fetch_job_list() {

    return solex_api_request(

        'joblist',

        [

            'api_key' => SOLEX_JOB_LIST_API_KEY
        ]
    );
}





/**
 * FETCH JOB DETAIL
 */

function solex_fetch_job_detail($job_id) {

    return solex_api_request(

        'Jobdetail',

        [

            'api_key' => SOLEX_JOB_DETAIL_API_KEY,

            'job_id' => $job_id
        ]
    );
}