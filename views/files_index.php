<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <? include 'templates/meta.php'; ?>
</head>
<body>
<? include 'templates/header.php'; ?>

<main class="container">
    <div class="row">
        <div class="col">
            <h1><?= $pageTitle ?></h1>
        </div>
        <div class="col">
            <form action="files.php?act=upload" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="formFile" class="form-label">選擇要上傳的圖片</label>
                    <input class="form-control" type="file" name="file" id="formFile">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-light float-right">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <? include 'templates/message.php'; ?>

    <div class="row">
        <div class="col">
            <h3>Bucket name：<?= $bucketName?></h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Key</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                </tr>
                </thead>
                <? foreach ($objectsAry as $idx => $object):
                    $isFile = empty($object['Size']) ? false : true; ?>
                    <tr>
                        <td><?= $idx ?></td>
                        <td class="text-center"><?= $isFile ? 'File' : 'Folder' ?></td>
                        <td><?= $object['Key'] ?></td>
                        <td class="text-right"><?= $object['Size'] ?></td>
                        <td><?= $object['LastModified']->date ?></td>
                        <td>
                            <? if ($isFile):?>
                                <a class="btn btn-primary btn-outline" target="_blank" href="files.php?act=show&key=<?= $object['Key'] ?>">Open</a>
                            <? endif; ?>
                            <a class="btn btn-danger" href="files.php?act=delete&key=<?= $object['Key'] ?>">Delete</a>
                        </td>
                    </tr>
                <? endforeach;?>
            </table>
        </div>
    </div>
    <? include 'templates/footer.php'; ?>
</main>

</body>
</html>

