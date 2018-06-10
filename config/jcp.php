<?php

/*
    JCP variables and storage locations.
    ** Note:Storage may need to come from .env so we can have different buckets for each environment
 */ 

return [

	'buckets' => [
		'avatars' => 'https://s3.eu-west-2.amazonaws.com/images.jcp/avatars/',
        'documents' => 'https://s3.eu-west-2.amazonaws.com/documents.jcp/',
        // attachment_videos
        // attachment_videos_encoded
        // attachment_images
        // documents
	],

    'racing_excellence' => [
        'set_fee' => 120,
        'additional_divisions' => 60,
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