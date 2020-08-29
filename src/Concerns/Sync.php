<?php

declare(strict_types=1);

namespace DcrPHP\DataToDb\Concerns;

use Doctrine\ORM\Tools\Setup;

abstract class Sync
{

    protected $clsSync;
    protected $sourceDataList;
    protected $finalDataList;
    protected $config;

    /**
     * 把父类引进来，使用其配置或日志
     * @param \DcrPHP\DataToDb\Sync $clsSync
     */
    public function setClsSync(\DcrPHP\DataToDb\Sync $clsSync)
    {
        $this->clsSync = $clsSync;
        $this->config = $clsSync->getConfig();
    }

    /**
     * 更新到目标库
     * @return bool
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateData()
    {
        $db = $this->getDb($this->config['target']['driver'], $this->config['target']);
        $db->beginTransaction();
        if ($this->config['target_table_empty']) {
            $db->exec("delete from {$this->config['target_table']}");
        }
        //开始添加数据
        foreach ($this->finalDataList as $data) {
            array_walk(
                $data,
                function (&$value, $key) {
                    $value = addslashes($value);
                }
            );
            $this->insert($db, $this->config['target_table'], $data);
        }
        $db->commit();
        return true;
    }

    /**
     * 获取数据列表 请把获取的数据按如下格式输出 这里的字段名不用对应数据库字段名，后面的formatData会对应好
     * array(
     * array('a'=>1,'b'=>1),
     * array('a'=>2,'b'=>2),
     * )
     * @return mixed
     */
    abstract public function getDataList();

    /**
     * 格式好数据，比如把字段对应好
     * @return mixed
     */
    public function formatData()
    {
        $finalList = array();
        //表字段对应
        $columns = $this->config['columns'];
        if(! $columns){
            $this->finalDataList = $this->sourceDataList;
            //没有配置 不用替换
            return;
        }
        foreach ($this->sourceDataList as $data) {
            $finalData = array();
            foreach ($data as $key => $value) {
                $finalData[$columns[$key]] = $value;
            }
            array_push($finalList, $finalData);
        }
        $this->finalDataList = $finalList;
    }

    public function sync()
    {
        $this->getDataList();
        $this->formatData();
        $this->updateData();
    }


    /**
     * 连接数据库
     * @param $driver //驱动 pdo_mysql pdo_sqlite
     * @param $config //数据库配置
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\ORM\ORMException
     */
    protected function getDb($driver, $config)
    {
        $ormConfig = Setup::createAnnotationMetadataConfiguration(
            array(__DIR__),
            true,
            null,
            null,
            false
        );
        $dbConn = $config;
        $dbConn['driver'] = $driver;

        $entityManager = \Doctrine\ORM\EntityManager::create($dbConn, $ormConfig);
        return $entityManager->getConnection();
    }

    /**
     * @param $db
     * @param $table
     * @param $info
     * @return mixed
     */
    public function insert($db, $table, $info)
    {
        $insert = $db->createQueryBuilder();
        $insert = $insert->insert($table);
        foreach ($info as $key => $value) {
            $insert = $insert->setValue("`{$key}`", "'{$value}'");
        }
        $sql = $insert->getSql();
        $db->exec($sql);
        /*echo $sql;
        exit;*/
        return $db->lastInsertId();
    }
}
