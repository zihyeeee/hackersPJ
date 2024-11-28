<?php

use \src\services\S3Service;
use \src\core\DB;

$test = $_GET['test'] ?? '';

if(empty($test)) {
    exit;
}

switch($test) {
    case 'phpinfo':
        phpinfo();
        break;

    case 'db':
        if(IS_DEV) {
            echo 'hacademia<br>';
            $db = new DB('hacademia');
            $db_slave = new DB('hacademia');
        } else {
            echo 'master<br>';
            $db = new DB('master');
            $db_slave = new DB('slave');
        }

        var_dump($db->getDb());
        echo '<br><br>';
        var_dump($db_slave->getDb());
        break;

    case 's3':
        if(!empty($_FILES['file'])) {
            
            if(IS_DEV) {
                echo 'hacademia<br>';
                $s3Service = new S3Service('hackersac-cdn');
            } else {
                echo 'adieu2024<br>';
                $s3Service = new S3Service('adieu2024');
            }

            $result = $s3Service->upload($_FILES['file']);

            var_dump($result);
            exit;
        } ?>
        <form action="/?page=test&test=s3" method="post" enctype="multipart/form-data">
            <input type="file" name="file">
            <button type="submit">업로드</button>
        </form>
        <?php break;
}
?>
