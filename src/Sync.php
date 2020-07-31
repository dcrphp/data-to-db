<?php

declare(strict_types=1);

namespace DcrPHP\DataToDb;

use DcrPHP\Config\Config;

class Sync
{
    private $configDir; //配置目录
    private $configName; //配置文件名
    private $config;
    private $clsHandler;
    private $isDebug;
    private $clsLog; //日志处理类

    public function __construct($configDir, $configName)
    {
        $this->configDir = $configDir;
        $this->configName = $configName;
        $this->init();
    }

    public function init()
    {
        $this->initConfig();
        $this->initHandler();
    }

    public function getConfigPath()
    {
        return $this->configDir . DIRECTORY_SEPARATOR . $this->configName;
    }

    /**
     * @return mixed
     */
    public function getIsDebug()
    {
        return $this->isDebug;
    }

    /**
     * @param mixed $isDebug
     */
    public function setIsDebug($isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function setLog($clsLog)
    {
        $this->clsLog = $clsLog;
    }

    public function initConfig()
    {
        //判断文件存在不存
        $configFile = $this->getConfigPath();
        if (!file_exists($configFile)) {
            throw new \Exception('faild to find this config:' . $configFile);
        }

        $clsConfig = new Config($configFile);
        $config = $clsConfig->get();
        $this->config = current($config);
        //判断配置是不是合格
        $this->checkConfig();
        //存入配置
    }

    private function checkConfig()
    {
        //判断配置文件
        $config = $this->config;
        if (!$config) {
            throw new \Exception('获取[' . $this->getConfigPath() . ']配置失败');
        }

        $enable = $config['enable'];
        if (!$enable) {
            throw new \Exception('请在[' . $this->getConfigPath() . ']中启用enable');
        }

        $handler = $config['handler'];
        if (empty($handler)) {
            throw new \Exception('请在[' . $this->getConfigPath() . ']中配置handler');
        }
        //先不检测字段
        $result = 1;
        //通用字段
        if (isset($config['source_config']) && isset($config['target']) && isset($config['target_table']))
        {
            $sourceConfig = $config['source_config'];
            if ('api' == $handler) {
                $result = isset($sourceConfig['url']) && isset($sourceConfig['data_field']) && isset($sourceConfig['request_type']);
            } elseif ('db' == $handler) {
                $result = isset($sourceConfig['driver']);
            }
        }else{
        $result = 0;
    }
        if (!$result) {
            throw new \Exception('配置字段有缺失');
        }
        return true;
    }

    public function initHandler()
    {
        //得到driver名
        $handlerName = $this->config['handler'];
        $handlerName = "DcrPHP\\DataToDb\\Handler\\" . ucfirst($handlerName);
        //初始化
        $clsHandler = new $handlerName();
        $clsHandler->setClsSync($this);
        $this->clsHandler = $clsHandler;
    }

    public function sync()
    {
        $this->clsHandler->sync();
    }
}
