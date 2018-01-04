<?php
require_once dirname(__FILE__) . "/vendor/autoload.php";
require_once dirname(__FILE__) . "/vendor/qinlodestar/aliyun-php-sdk-green/aliyun-php-sdk-core/Config.php";
use Green\Request\V20170112 as Green;
$iClientProfile = DefaultProfile::getProfile("cn-shenzhen", "accessKeyId", "accessKeySecret"); // TODO
DefaultProfile::addEndpoint("cn-shenzhen", "cn-shenzhen", "Green", "green.cn-beijing.aliyuncs.com");
$client = new DefaultAcsClient($iClientProfile);

$request = new Green\TextScanRequest();
$request->setMethod("POST");
$request->setAcceptFormat("JSON");
$task1 = array('dataId' =>  uniqid(),
    'content' => '案件移交'
);
$request->setContent(json_encode(array("tasks" => array($task1),
    "scenes" => array("antispam"))));

try {
    $response = $client->getAcsResponse($request);
    print_r($response);
    if(200 == $response->code){
        $taskResults = $response->data;
        foreach ($taskResults as $taskResult) {
            if(200 == $taskResult->code){
                $sceneResults = $taskResult->results;
                foreach ($sceneResults as $sceneResult) {
                    $scene = $sceneResult->scene;
                    $suggestion = $sceneResult->suggestion;
                    //根据scene和suggetion做相关的处理
                    //do something
                    print_r($scene);
                    print_r($suggestion);
                }
            }else{
                print_r("task process fail:" + $response->code);
            }
        }
    }else{
        print_r("detect not success. code:" + $response->code);
    }
} catch (Exception $e) {
    print_r($e);
}
