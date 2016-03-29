<?php

//composer auto load
include("aws/aws-autoloader.php");

use Aws\Common\Aws;
use Aws\Common\Enum\Region;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\S3Exception;
use Aws\CloudFront\Enum\OriginProtocolPolicy;
use Aws\CloudFront\Enum\ViewerProtocolPolicy;
use Aws\CloudFront\Exception\CloudFrontException;
use Guzzle\Http\EntityBody;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Enum\Type;

class AmazonServices {

    private $aws;
    private $s3Client;
    private $config;
    private $sesClient;
    private $sqsClient;
    private $snsClient;
    private $dynamoClient;
    private $cfClient;
    private $cfIdentity;
    public $debug = true;

    function __construct() {
        
        $this->aws = Aws::factory(array(
                    'key' => 'AKIAIO4B2SELTX6674SA',
                    'secret' => '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',
                    'region' => Region::US_EAST_1
                ));
        $this->cfIdentity = null;
        //$this->s3Client = $this->aws->get('s3');
        //$this->sesClient = $this->aws->get('ses');
        //$this->cfClient = $this->aws->get('cloudfront');
        //$this->sqsClient = $this->aws->get('sqs');
        //$this->snsClient = $this->aws->get('sns');
        $this->dynamoClient = $this->aws->get('dynamodb');
        //echo "[DEBUG] -- DynamoDB client supposed to be created!<BR>\n";
        
    }

    function getS3Client() {
        return $this->s3Client;
    }

    function isValidBucketName($bucket_name) {
        try {
            if ($this->s3Client->isValidBucketName($bucket_name)) {
                return true;
            }
            return false;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function doesBucketExist($bucket_name) {
        try {
            if ($this->s3Client->doesBucketExist($bucket_name)) {
                return true;
            }
            return false;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function createBucket($bucket_name) {
        $this->s3Client->createBucket(
                array(
                    'Bucket' => $bucket_name,
                    'ACL' => CannedAcl::PUBLIC_READ
                //add more items if required here
        ));
    }

    //File should be sent as fopen('/path/to/file', 'r'),
    function put_object($bucket_name, $name, $file) {
        try {
            $response = $this->s3Client->putObject(array(
                'Bucket' => $bucket_name,
                'Key' => $name,
                'Body' => fopen($file, 'r'),
                'ACL' => CannedAcl::PUBLIC_READ
                    ));

            return $response;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            }
        }
    }

    function copy_object($bucket_name, $name, $copySource) {
        try {
            $response = $this->s3Client->copyObject(array(
                'Bucket' => $bucket_name,
                'Key' => $name,
                'CopySource' => $copySource,
                'ACL' => CannedAcl::PUBLIC_READ
                    ));

            return $response;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            }
        }
    }

    function get_object($bucket_name, $name, $path) {
        try {
            $response = $this->s3Client->getObject(
                    array(
                        'Bucket' => $bucket_name,
                        'Key' => $name,
                        'SaveAs' => $path
                    )
            );
            return $response;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            }
        }
    }

    function _return_bucket_policy($bucket_name = '') {
        return '{
            "Version": "2008-10-17",
            "Id": "PolicyForCloudFrontPrivateContent",
            "Statement": [
                {
                    "Sid": "1",
                    "Effect": "Allow",
                    "Principal": {
                        "AWS": "arn:aws:iam::cloudfront:user/CloudFront Origin Access Identity ' . $this->cfIdentity . '"
                    },
                    "Action": "s3:GetObject",
                    "Resource": "arn:aws:s3:::' . $bucket_name . '/*"
                }
            ]
        }';
    }

    function putBucketPolicy($bucket_name) {
        if ($this->doesBucketExist($bucket_name)) {
            try {
                $this->s3Client->putBucketPolicy(
                        array(
                            'Bucket' => $bucket_name,
                            'Policy' => $this->_return_bucket_policy($bucket_name)
                ));
                return true;
            } catch (S3Exception $e) {
                if ($this->debug) {
                    return $e->getMessage();
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    function deleteBucket($bucket_name) {
        try {
            $this->s3Client->clearBucket($bucket_name);
            $this->s3Client->deleteBucket(array(
                'Bucket' => $bucket_name
            ));
            return true;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function doesObjectExist($bucket_name, $key) {
        try {
            if ($this->s3Client->doesObjectExist($bucket_name, $key)) {
                return true;
            }
            return false;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function putObject($bucket_name, $key, $body) {
        try {
            $this->s3Client->putObject(array(
                'Bucket' => $bucket_name,
                'Key' => $key,
                'Body' => $body,
                'ACL' => CannedAcl::PUBLIC_READ
            ));
            return true;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function deleteObject($bucket_name, $objects) {
        try {
            $this->s3Client->deleteObject(array(
                'Bucket' => $bucket_name,
                'Objects' => $objects
            ));
            return true;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    // $objects should be array(array('Key'=>$key), array('Key'=>$key2)); testing needed here before implementation
    function deleteObjects($bucket_name, $keys) {
        try {
            $response = $this->s3Client->deleteObjects(array(
                'Bucket' => $bucket_name,
                'Objects' => $keys
                    ));
            return $response;
        } catch (S3Exception $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function createDistribution($bucket_name, $domain_name) {
        try {
            $return = $this->cfClient->createDistribution($this->_return_distribution_config_array($bucket_name, $domain_name, true));
            return $return->toArray();
        } catch (CloudFrontException $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function _return_distribution_config_array($bucket_name = '', $domain_name = '', $enabled = true) {
        $origin_id = 'S3-' . $bucket_name;
        return array(
            'CallerReference' => md5(time()),
            'Aliases' => array(
                'Quantity' => 1,
                'Items' => array($domain_name)
            ),
            'DefaultRootObject' => 'index.html',
            'Origins' => array(
                'Quantity' => 1,
                'Items' => array(
                    array(
                        'Id' => $origin_id,
                        'DomainName' => strtolower($bucket_name . '.s3.amazonaws.com'),
                        'S3OriginConfig' => array(
                            'OriginAccessIdentity' => 'origin-access-identity/cloudfront/' . $this->cfIdentity
                        )
                    )
                )
            ),
            'DefaultCacheBehavior' => array(
                'TargetOriginId' => $origin_id,
                'ForwardedValues' => array(
                    'QueryString' => false
                ),
                'TrustedSigners' => array(
                    'Enabled' => false,
                    'Quantity' => 0,
                    'Items' => array()
                ),
                'ViewerProtocolPolicy' => ViewerProtocolPolicy::ALLOW_ALL,
                'MinTTL' => 0
            ),
            'CacheBehaviors' => array('Quantity' => 0, 'Items' => array()),
            'Comment' => 'Distribution for ' . $bucket_name,
            'Logging' => array(
                'Enabled' => false,
                'Bucket' => '',
                'Prefix' => ''
            ),
            'Enabled' => $enabled
        );
    }

    function disableDistribution($cfID) {
        try {
            $getConfig = $this->cfClient->getDistributionConfig(array('Id' => $cfID));
            $got_config_array = $getConfig->toArray();
            try {
                $config_array = $got_config_array;
                $config_array['Enabled'] = false;
                $config_array['Id'] = $cfID;
                $config_array['IfMatch'] = $got_config_array['ETag'];
                $config_array['Logging'] = array(
                    'Enabled' => false,
                    'Bucket' => '',
                    'Prefix' => ''
                );
                unset($config_array['ETag']);
                unset($config_array['RequestId']);
                $this->cfClient->updateDistribution($config_array);
            } catch (CloudFrontException $e) {
                if ($this->debug) {
                    return $e->getMessage();
                } else {
                    return false;
                }
            }
        } catch (CloudFrontException $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function deleteDistribution($cfID) {
        try {
            $getDistribution = $this->cfClient->getDistribution(array('Id' => $cfID));
            $got_distribution_array = $getDistribution->toArray();
            if ($got_distribution_array['Status'] == 'Deployed' and $got_distribution_array['DistributionConfig']['Enabled'] == false) {
                try {
                    $this->cfClient->deleteDistribution(array('Id' => $cfID, 'IfMatch' => $got_distribution_array['ETag']));
                    return true;
                } catch (CloudFrontException $e) {
                    if ($this->debug) {
                        return $e->getMessage();
                    } else {
                        return false;
                    }
                }
            } else {
                if ($this->debug) {
                    return $e->getMessage();
                } else {
                    return false;
                }
            }
        } catch (CloudFrontException $e) {
            if ($this->debug) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function amazonSesEmail($to, $subject, $message) {
        $obj['Subject']['Data'] = $subject;
        $obj['Body']['Html']['Data'] = $message;
          $this->sesClient->sendEmail(array('Source' => $this->config['amazon_send_email'],
            'Destination' => array('ToAddresses' => array($to)),
            'Message' => $obj
        ));
    }

    function sendAmazonSqsMessage($message, $queueUrl) {
        $this->sqsClient->sendMessage(array(
            'QueueUrl' => $queueUrl,
            'MessageBody' => $message,
                //  'DelaySeconds' => 30,
        ));
    }

    function getAmazonSqsMessages($queueUrl) {
        $result = $this->sqsClient->receiveMessage(array(
            'QueueUrl' => $queueUrl,
            //'WaitTimeSeconds' => 2,
                ));

        $response = array();
        $messages=$result->getPath('Messages/*/Body');
        
        if (!empty($messages)) {
            foreach ($messages as $messageBody) {
                array_push($response, json_decode($messageBody, true));
            }
        }
        
        $receipthandle = $result->getPath('Messages/*/ReceiptHandle');
        
        $message['notifications']=$response;
        $message['handler']=$receipthandle;
        return $message;
    }
    
     function deleteAmazonSqsMessages($queueUrl,$handler) {
        $result = $this->sqsClient->deleteMessage(array(
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $handler,
                ));
     }
     
     function subscribeTopic($topicArn,$token){
         $response = $this->snsClient->confirmSubscription(array(
            'TopicArn' => $topicArn,
            'Token' => $token,
                ));
     }
     
     function publishTopic($topicArn,$message){
         $response = $this->snsClient->publish(array(
            'TopicArn' => $topicArn,
            'Message' => $message,
                ));
     }
     
     function get_dynamo_db(){
       return $this->dynamoClient;
    }



}