# AWS S3 + PHP

安裝AWS PHP SDK
```
composer require aws/aws-sdk-php
```

或者依照composer.json
```
composer install
```
 
建立設定檔
```
mv env.example.php env.php
```

### AwsS3Client.php 使用方式

取得連線
```
$key = '<aws s3 bucket key>';
$secret = '<aws s3 bucket secret>';
$region = '<aws s3 region>';

$client = new AwsS3Client($key, $secret, $region);
```

取得Region中的Bucket
```$xslt
$buckets = $client->getBucketList();
```

取得Bucket中所有object
```$xslt
$bucket = '<your bucket name>';

$objects = $client->getBucketObjects($bucket);
```

上傳Object
```$xslt
$bucket = '<your bucket name>';
$objectKey = '<file key in s3>'; //可視為在S3 bucket中的路徑
$filePath = '<file local path>';

$response = $client->uploadFile($bucket, $objectKey, $filePath)
```

刪除Object
```$xslt
$bucket = '<your bucket name>';
$objectKey = '<file key in s3>';
$response = $client->deleteFile($bucket, $objectKey)
```

## S3設定
S3 Access必須設定為Public才可以讓外部連線當作圖庫

* Static website hosting 開啟靜態設定
* Block public access (bucket settings) 關閉所有阻擋
* Bucket policy 開通權限
```$xslt
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "Statement1",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::<bucket名稱>/*"
        }
    ]
}
```