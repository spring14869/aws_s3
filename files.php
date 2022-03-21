<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'services/AwsS3Client.php';

class Files
{
    const BUCKET = 'marleychang.live-s3';

    private $act;
    private $s3client;

    private $key = 'AKIAWRPXFQY3PKRLHM26';
    private $secret = '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';
    private $region = 'ap-northeast-1';

    public function __construct($act)
    {
        session_start();
        $this->act = $act;
        $this->s3client = new AwsS3Client($this->key, $this->secret, $this->region);
    }

    public function handle()
    {
        try {
            switch ($this->act) {
                case 'upload':
                    $this->upload();
                    break;

                case 'delete':
                    $this->delete();
                    break;

                case 'show':
                    $this->show();
                    break;

                case 'index':
                default:
                    $this->index();
                    break;
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    public function index()
    {
        $bucketName = static::BUCKET;
        $pageTitle = 'Bucket所有Objects';

        $objectsAry = [];
        $objectsAry = $this->s3client->getBucketObjects(static::BUCKET);

        include 'views/files_index.php';
    }


    public function show()
    {
        $key = $_GET['key'];
        $objectUrl = $this->s3client->getFileUrl(static::BUCKET, $key);

        echo $objectUrl;
    }


    public function upload()
    {
        $fileField = 'file';
        $file = $_FILES[$fileField];
        $targetDir = 'images/';
        $targetFile = $targetDir . basename($file['name']);
        $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($file['name']));
        $keyName = $targetDir . $fileName . '_' . time() . '.' . $fileType;


        try {
            if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'svg'])) {
                throw new Exception($fileType . ' 不符合規定');
            }

            if (getimagesize($file['tmp_name']) === false) {
                throw new Exception($fileType . ' 錯誤');
            }

            if (!move_uploaded_file($file['tmp_name'], $keyName)) {
                throw new Exception('上傳失敗');
            }
        } catch (Exception $exception) {
            $_SESSION['error'] = 'Exception:' . $exception->getMessage();
        }


        $fileSource = __DIR__ . DIRECTORY_SEPARATOR . $keyName;

        $objects = $this->s3client->uploadFile(static::BUCKET, $keyName, $fileSource);

        $_SESSION['success'] = '上傳結果：' . $objects;

        header('Location: files.php');
    }


    public function delete()
    {
        $key = $_GET['key'];

        $objects = $this->s3client->deleteFile(static::BUCKET, $key);

        $_SESSION['success'] = '刪除結果：<pre>' . json_encode($objects);

        header('Location: files.php');
    }
}

$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']): 'index';

$files = new Files($act);

$files->handle();