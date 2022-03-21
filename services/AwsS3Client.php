<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class AwsS3Client
{
    private $awsKey;
    private $awsSecret;
    private $awsRegion;
    protected $client;

    public function __construct($key, $secret, $region)
    {
        $this->awsKey = $key;
        $this->awsSecret = $secret;
        $this->awsRegion = $region;

        $this->getInstance();
    }

    /**
     * 取得連線
     */
    private function getInstance()
    {
        $credentials = new Credentials($this->awsKey, $this->awsSecret);

        $this->client = new S3Client([
            'credentials' => $credentials,
            'region' => $this->awsRegion,
            'version' => 'latest',
        ]);
    }

    /**
     * 取得帳號下所有Bucket
     * @return mixed
     */
    public function getBucketList()
    {
        return $this->client->listBuckets();
    }


    /**
     * 取得Bucket底下所有物件（包含目錄）
     * @param $bucket
     * @return mixed
     */
    public function getBucketObjects($bucket)
    {
        return $this->client->listObjects([
            'Bucket' => $bucket
        ])->get('Contents');
    }

    /**
     * 取得物件
     * @param $bucket
     * @param $objectKey
     * @return string
     */
    public function getFileUrl($bucket, $objectKey)
    {
        return $this->client->getObjectUrl($bucket, $objectKey);
    }


    public function getFile($bucket, $objectKey)
    {
        $objects = $this->client->getObject([
            'Bucket' => $bucket,
            'Key' => $objectKey
        ]);

        return $objects;
    }

    /**
     * 上傳物件
     * @param $bucket
     * @param $objectKey
     * @param $sourceFile 檔案實體路徑
     * @return mixed
     */
    public function uploadFile($bucket, $objectKey, $sourceFile)
    {
        $result = $this->client->putObject([
            'Bucket' => $bucket,
            'Key' => $objectKey,
            'SourceFile' => $sourceFile
        ]);

        return $result->get('ObjectURL');
    }

    /**
     * 刪除物件
     * @param $bucket
     * @param $objectKey
     * @return mixed
     */
    public function deleteFile($bucket, $objectKey)
    {
        return $this->client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $objectKey,
        ]);
    }

}