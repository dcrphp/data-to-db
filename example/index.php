<?php
require_once("../vendor/autoload.php");

ini_set('display_errors', 'on');

use DcrPHP\DataToDb\Sync;

//$debug = 1; //记录错误及一般信息 不支持本参数
$clsSync = new Sync(__DIR__ . DIRECTORY_SEPARATOR . 'config', 'oa.php');
/*if ($debug) {
    $clsSync->setIsDebug($debug); //是不是输出日志信息，如果设置为1 则在log目录下可以看日志
    $clsLog = new \DcrPHP\Log\SystemLogger();
    $clsLog->setConfig(
        array(
            //频道名 一般定义为系统名
            'channel' => 'log',
            //要用什么存系统日志  用户日志用的是mongodb
            'handler' => 'directory',
            //directory为日志生成在path目录下， general为day则按天 hour按时 month按月 minute按分，prefix为日志文件后缀默认为log
            'directory' => array(
                'path' => __DIR__ . DIRECTORY_SEPARATOR . 'log',
                'prefix' => 'php',
                'general' => 'hour'
            ),
        )
    );
    $clsSync->setLog($clsLog);
}*/
$clsSync->init();
$clsSync->sync();
echo 'sync over';
