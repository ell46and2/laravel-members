<?php

/*
    JCP variables and storage locations.
    ** Note:Storage may need to come from .env so we can have different buckets for each environment
 */ 

return [

	'buckets' => [
		'avatars' => 'https://s3.eu-west-2.amazonaws.com/images.jcp/avatars/',
        'documents' => 'https://s3.eu-west-2.amazonaws.com/documents.jcp/',
        'videos_out' => 'https://s3.eu-west-1.amazonaws.com/video-out.jcp/',
        'video_thumbnails' => 'https://s3.eu-west-1.amazonaws.com/video-thumbs.jcp/',
        'images' => 'https://s3.eu-west-2.amazonaws.com/images.jcp/images/',
        // attachment_videos
        // attachment_videos_encoded
        // attachment_images
        // documents
	],

    'site' => [
        'pagination' => 15,
    ],

    'racing_excellence' => [
        'set_fee' => 120,
        'additional_divisions' => 60,
    ],

    'activity' => [
        'rate' => 35
    ],

    'group_activity' => [
        '2' => 1.5,
        '3+' => 1.75
    ],

    'mileage' => [
        'threshold' => 10000,
        'rate_below_threshold' => 0.45,
        'rate_above_threshold' => 0.25,
    ],

    'invoice' => [
        'start_period' => 1,
        'end_period' => 10,
        'address' => [
            'name' => 'Junnie Durrans',
            'line1' => 'The British Racing School',
            'line2' => 'Snailwell Road',
            'line3' => 'Newmarket',
            'county' => 'Suffolk',
            'postcode' => 'CB8 7NU'
        ]
    ],

    'jockey_hours_training_allowance' => [
        'months_for_initial_period' => 3,
        'initial' => 6,
        'after' => 4
    ],

    'sports_office_api' => [
        // 'auth_params' => [
        //     'grant_type' => 'password',
        //     'client_id' => env('SPORTS_OFFICE_API_CLIENT_ID'),
        //     'client_secret' => env('SPORTS_OFFICE_API_CLIENT_SECRET'),
        //     'username' => env('SPORTS_OFFICE_API_USERNAME'),
        //     'password' => env('SPORTS_OFFICE_API_PASSWORD'),
        // ],
        'oauth_params' => [
            'grant_type' => 'password',
            'client_id' => env('BRITISH_HORSE_RACING_OAUTH_CLIENT_ID'),
            'client_secret' => env('BRITISH_HORSE_RACING_OAUTH_CLIENT_SECRET'),
            'username' => env('BRITISH_HORSE_RACING_OAUTH_USERNAME'),
            'password' => env('BRITISH_HORSE_RACING_OAUTH_PASSWORD'),
            'scope' => 'coaching',
        ]
    ],

    're_scoring' => [
        'default' => [
            1 => 5,
            2 => 3,
            3 => 2,
            4 => 1,
        ],
        'salisbury' => [
            1 => 10,
            2 => 6,
            3 => 4,
            4 => 3,
            5 => 2,
            6 => 1,
        ]
    ],

    'pdp_fields' => [
        ['field' => 'personal_details', 'label' => 'Personal Details'],
        ['field' => 'career', 'label' => 'Career'],
        ['field' => 'nutrition', 'label' => 'Nutrition'],
        ['field' => 'physical', 'label' => 'Physical'],
        ['field' => 'communication_media', 'label' => 'Communication & Media'],
        ['field' => 'personal_well_being', 'label' => 'Personal Well-being'],
        ['field' => 'managing_finance', 'label' => 'Managing Finances'],
        ['field' => 'sports_psychology', 'label' => 'Sports Psychology'],
        ['field' => 'mental_well_being', 'label' => 'Mental Well-being'],
        ['field' => 'interests_hobbies', 'label' => 'Interests & Hobbies'],
        ['field' => 'performance_goals', 'label' => 'Performance Goals'],
        ['field' => 'actions', 'label' => 'Actions'],
        ['field' => 'support_team', 'label' => 'Present Support Team']
    ],

    'skills_profile_fields' => [
        [ 'field' => 'riding_rating' ,'label' => 'Riding'],
        [ 'field' => 'simulator_rating' ,'label' => 'Simulator'],
        [ 'field' => 'race_riding_skills_rating' ,'label' => 'Race Riding Skills'],
        [ 'field' => 'whip_rating' ,'label' => 'Use of the Whip'],
        [ 'field' => 'fitness_rating' ,'label' => 'Fitness'],
        [ 'field' => 'weight_rating' ,'label' => 'Weight and Nutrition'],
        [ 'field' => 'communication_rating' ,'label' => 'Communication'],
        [ 'field' => 'professionalism_rating' ,'label' => 'Professionalism'],
    ]

    // VAT
    // Hours jockeys are chargable for - i.e. 4hrs and 6hrs if first 3 months of license
    // Mileage rates, and th mileage that the fee goes up/down i.e. after 10,000 miles

];

/*
{
    "Version": "2012-10-17",
    "Id": "http referer policy example",
    "Statement": [
        {
            "Sid": "Allow get requests originating from jcp.local.",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::images.jcp/*",
            "Condition": {
                "StringLike": {
                    "aws:Referer": [
                        "http://jcp.local/*"
                    ]
                }
            }
        }
    ]
}
 */