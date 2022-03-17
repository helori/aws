<?php

namespace Helori\Aws;

use Aws\S3\S3Client;
use Aws\Result;
use Symfony\Component\Dotenv\Dotenv;

class Aws
{
    use WritesToConsole;

    public function cancelUploads()
    {
        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__).'/.env');
        
        $endpoint = $_ENV['AWS_ENDPOINT'];
        $region = $_ENV['AWS_REGION'];
        $key = $_ENV['AWS_KEY'];
        $secret = $_ENV['AWS_SECRET'];
        $bucket = $_ENV['AWS_BUCKET'];

        $s3 = new S3Client([
            'endpoint' => $endpoint,
            'version' => 'latest',
            'region'  => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
        ]);

        $response = $s3->listMultipartUploads([
            'Bucket' => $bucket,
        ]);

        $uploads = $response->get('Uploads');
        
        if(is_null($uploads))
        {
            $this->info("No unfinished multipart uploads to cancel");
        }
        else
        {
            foreach($uploads as $i => $upload)
            {
                $this->info(($i+1).'/'.count($uploads).' | Aborting upload | Initiated : '.$upload['Initiated']->format('d/m/Y H:i:s').' | Key : '.$upload['Key']);
                $s3->abortMultipartUpload([
                    'Bucket' => $bucket,
                    'UploadId' => $upload['UploadId'],
                    'Key' => $upload['Key'],
                ]);
            }
        }
    }

    public function configCancelUploads()
    {
        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__).'/.env');
        
        $endpoint = $_ENV['AWS_ENDPOINT'];
        $region = $_ENV['AWS_REGION'];
        $key = $_ENV['AWS_KEY'];
        $secret = $_ENV['AWS_SECRET'];
        $bucket = $_ENV['AWS_BUCKET'];

        $s3 = new S3Client([
            'endpoint' => $endpoint,
            'version' => 'latest',
            'region'  => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
        ]);

        // Set config to prevent incomplete multipart uploads
        // https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putbucketlifecycleconfiguration
        $response = $s3->putBucketLifecycleConfiguration([
            'Bucket' => $bucket,
            'LifecycleConfiguration' => [
                'Rules' => [
                    [
                        'ID' => 'Abort incomplete multipart uploads',
                        'Status' => 'Enabled',
                        'Filter' => [],
                        'AbortIncompleteMultipartUpload' => [
                            'DaysAfterInitiation' => 1,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
