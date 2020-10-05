<?php

declare(strict_types=1);

namespace DcrPHP\DataToDb\Handler;

use DcrPHP\DataToDb\Concerns\Sync;

class Api extends Sync
{
    public function getDataList()
    {
        $configSource = $this->config['source_config'];

        //请求数据

        $client = new \GuzzleHttp\Client(['timeout' => 300, 'verify' => false]);
        $res = $client->request(
            $configSource['request_type'],
            $configSource['url'],
            array('form_params' => $configSource['request_data'])
        );
        $dataList = json_decode($res->getBody()->getContents(), true);
        $keyList = explode(',', $configSource['data_field']);
        foreach ($keyList as $key) {
            $dataList = $dataList[$key];
        }
        $this->sourceDataList = $dataList;
    }
}
