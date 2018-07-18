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
        // 'invoicing_address' => 
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
        'auth_params' => [
            'grant_type' => 'password',
            'client_id' => env('SPORTS_OFFICE_API_CLIENT_ID'),
            'client_secret' => env('SPORTS_OFFICE_API_CLIENT_SECRET'),
            'username' => env('SPORTS_OFFICE_API_USERNAME'),
            'password' => env('SPORTS_OFFICE_API_PASSWORD'),
        ]
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