<?php

declare(strict_types=1);

namespace DcrPHP\DataToDb\Handler;

use DcrPHP\DataToDb\Concerns\Sync;
use Doctrine\ORM\Tools\Setup;

class Db extends Sync
{

    public function getDataList()
    {
        $configSource = $this->config['source_config'];

        //得出结果
        $this->sourceDataList = $this->getDb($configSource['driver'],$configSource)->query($this->config['source_config']['sql'])->fetchAll();
    }
}