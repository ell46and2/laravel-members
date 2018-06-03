<?php 

return [

	'buckets' => [
		'avatars' => 'https://s3.eu-west-2.amazonaws.com/images.jcp/avatars/',
	]

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