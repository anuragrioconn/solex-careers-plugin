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
        ],

        [
            'job_id' => 4,
            'title' => 'HR Executive',
            'department' => 'Human Resources',
            'type' => 'Full Time',
            'location' => 'Ahmedabad',
            'openings' => 1,
            'description' => 'Manage recruitment and employee engagement.',
            'responsibilities' => [
                'Screen candidates',
                'Coordinate interviews',
                'Employee onboarding'
            ],
            'qualifications' => [
                'MBA HR',
                'Good communication skills'
            ]
        ],

        [
            'job_id' => 5,
            'title' => 'Site Supervisor',
            'department' => 'Operations',
            'type' => 'Full Time',
            'location' => 'Rajkot',
            'openings' => 4,
            'description' => 'Monitor solar installation sites.',
            'responsibilities' => [
                'Manage site workers',
                'Track progress',
                'Ensure safety compliance'
            ],
            'qualifications' => [
                'Diploma/B.Tech',
                'Site handling experience'
            ]
        ],

        [
            'job_id' => 6,
            'title' => 'Procurement Manager',
            'department' => 'Procurement',
            'type' => 'Full Time',
            'location' => 'Ahmedabad',
            'openings' => 2,
            'description' => 'Handle procurement and vendor management.',
            'responsibilities' => [
                'Vendor negotiations',
                'Purchase orders',
                'Inventory coordination'
            ],
            'qualifications' => [
                'Supply chain experience',
                'ERP knowledge'
            ]
        ],

        [
            'job_id' => 7,
            'title' => 'Marketing Specialist',
            'department' => 'Marketing',
            'type' => 'Full Time',
            'location' => 'Mumbai',
            'openings' => 2,
            'description' => 'Drive brand awareness and campaigns.',
            'responsibilities' => [
                'Social media campaigns',
                'Performance marketing',
                'Lead generation'
            ],
            'qualifications' => [
                'Digital marketing skills',
                'Google Ads experience'
            ]
        ],

        [
            'job_id' => 8,
            'title' => 'Accounts Executive',
            'department' => 'Finance',
            'type' => 'Full Time',
            'location' => 'Ahmedabad',
            'openings' => 2,
            'description' => 'Handle accounting and GST filings.',
            'responsibilities' => [
                'Bookkeeping',
                'GST filing',
                'Invoice management'
            ],
            'qualifications' => [
                'B.Com/M.Com',
                'Tally knowledge'
            ]
        ],

        [
            'job_id' => 9,
            'title' => 'Frontend Developer',
            'department' => 'IT',
            'type' => 'Full Time',
            'location' => 'Remote',
            'openings' => 3,
            'description' => 'Build frontend applications and dashboards.',
            'responsibilities' => [
                'Develop UI',
                'API integration',
                'Responsive design'
            ],
            'qualifications' => [
                'React.js knowledge',
                'JavaScript expertise'
            ]
        ],

        [
            'job_id' => 10,
            'title' => 'Business Development Manager',
            'department' => 'Business Development',
            'type' => 'Full Time',
            'location' => 'Pune',
            'openings' => 1,
            'description' => 'Expand business opportunities and partnerships.',
            'responsibilities' => [
                'Client acquisition',
                'Strategic partnerships',
                'Sales pipeline management'
            ],
            'qualifications' => [
                'MBA preferred',
                'Strong communication'
            ]
        ]

    ];
}