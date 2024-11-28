<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2023-08-04
 * Time: 오후 2:20
 */
namespace src\services;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class S3Service
{
    private $fileConfig;
    private $s3;
    private $s3Client;
    
    public function __construct($bucket)
    {
        $this->fileConfig = include CONFIG_PATH . '/file.php';
        $this->s3 = $this->fileConfig['s3'][$bucket];
        $this->s3Client = S3Client::factory($this->s3['factory']);
    }

    public function upload($file, $dir = null) {
        if(empty($dir)) {
            $dir = $this->fileConfig['upload_path'];
        }

        $returnData = [];
        $returnData['result'] = false;

        $fileExt = strtolower($this->getExt($file['name'])); // 확장자
        $saveFileName = md5($this->uniqueTimeStamp()) . "." . $fileExt; // 저장할 파일명
        $directoryPath = $this->makeDirectoryPath($dir . '/' . $saveFileName); // 저장경로
        $allowSize = 1048576; // 1MB
        $allowExt = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'zip', 'ppt', 'docx', 'doc', 'hwp', 'mp3', 'mp4', 'wmv', 'm4a', 'ogg', 'pptx', 'pdf', 'mov', 'xlsx', 'xls', 'avi', 'smi', 'csv']; // 허용된 확장자

        if(!$file || !$directoryPath) {
            $returnData['msg'] = '필수 값 없음';
            return $returnData;
        }

        if(!in_array(strtolower($fileExt), $allowExt)) {
            $returnData['msg'] = '허용되지 않은 확장자';
            return $returnData;
        }

        if($file['size'] > $allowSize) {
            $mb = ($allowSize / 1024) / 1024;
            $returnData['msg'] = $mb . 'MB 이하의 파일만 업로드 가능합니다.';
            return $returnData;
        }

        // 이미지 메타 데이터
        $imageMetaData = getimagesize($file['tmp_name']);

        // 파일을 S3에 저장
        $putResult = $this->putObject($file, $directoryPath);
        if(!$putResult) {
            $returnData['msg'] = '업로드 실패';
            return $returnData;
        }

        // 값 리턴
        $returnData['result'] = true;
        $returnData['msg'] = '업로드 완료';
        $returnData['name'] = $saveFileName;
        $returnData['url'] = $this->s3['url'] . '/' . $directoryPath;
        $returnData['directoryPath'] = $directoryPath;
        $returnData['meta'] = $imageMetaData;
        return $returnData;
    }

    private function putObject($file, $directoryPath) {
        if(!$file || !$this->s3Client || !$this->s3['bucket'] || !$directoryPath) {
            return false;
        }

        // 저장될 파일명
        try {
            $result = $this->s3Client->putObject(array(
                'Bucket' => $this->s3['bucket'],
                'Key'    => $directoryPath,
                'Body'   => fopen($file['tmp_name'], 'r'),
                'ContentType'  =>  $file['type']
            ));

            // S3 파일 업로드 후 파일 삭제
            if (is_file($file['tmp_name'])) {
                @unlink($file['tmp_name']);
            }
        } catch (S3Exception $e) {
            echo $e;
            return false;
        }
        return $result;
    }

    private function getExt($name) {
        $nx=explode('.',$name);
        return $nx[count($nx)-1];
    }

    private function uniqueTimeStamp() {
        list($msec, $sec) = explode(" ", microtime());
        $msec = explode(".", $msec);
        return $sec.substr(array_pop($msec), 0, 4);
    }

    private function makeDirectoryPath($directoryPath) {
        if($this->s3['bucket'] == 'adieu2024') {
            return $directoryPath;
        }

        $directoryPathArr = explode('/', $directoryPath);
        if(in_array($directoryPathArr[0], ['upload-test', 'upload'])) {
            return $directoryPath;
        }

        // 저장경로 설정
        if(IS_DEV) {
            $directoryPath = 'upload-test/' . $directoryPath;
        } else {
            $directoryPath = 'upload/' . $directoryPath;
        }
        return $directoryPath;
    }
}