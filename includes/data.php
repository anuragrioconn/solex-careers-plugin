<?php

if (!defined('ABSPATH')) {
    exit;
}

function solex_get_jobs_data() {

    return [

        [
            'job_id' => 1,
            'title' => 'Solar Project Engineer',
            'department' => 'Engineering',
            'type' => 'Full Time',
            'location' => 'Ahmedabad',
            'openings' => 5,
            'description' => 'We are looking for a Solar Project Engineer to manage solar projects.',
            'responsibilities' => [
                'Design solar systems',
                'Prepare project reports',
                'Coordinate with teams'
            ],
            'qualifications' => [
                'Bachelor Degree',
                '2+ years experience',
                'AutoCAD knowledge'
            ]
        ],

        [
            'job_id' => 2,
            'title' => 'Sales Executive',
            'department' => 'Sales',
            'type' => 'Full Time',
            'location' => 'Surat',
            'openings' => 3,
            'description' => 'Looking for dynamic sales executive.',
            'responsibilities' => [
                'Generate leads',
                'Handle clients',
                'Meet sales targets'
            ],
            'qualifications' => [
                'MBA preferred',
                'Good communication',
                'Sales experience'
            ]
        ],

        [
            'job_id' => 3,
            'title' => 'Electrical Design Engineer',
            'department' => 'Engineering',
            'type' => 'Full Time',
            'location' => 'Vadodara',
            'openings' => 2,
            'description' => 'Electrical system design and planning.',
            'responsibilities' => [
                'Prepare layouts',
                'Design electrical systems',
                'Testing'
            ],
            'qualifications' => [
                'B.Tech Electrical',
                '2 Years Experience'
            ]
        ]

    ];
}