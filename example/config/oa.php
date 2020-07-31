<?php

return array(
    'enable'=> 1,
    'handler' => 'api', //类型  支持db和api获取数据
    'source_config' => array( //配置方式可看target.php
        'url' => '',  //api地址
        'data_field' => '',  //数据列表字段 如data.list或data
        'request_type' => 'get', //post or get
        'request_data' => array(), //请求的数据
    ),
    'target' => include('target.php'),
    'target_table' => '',
    'target_table_empty' => 1, //是不是清空目标库表再添加
    //字段对应关系 如果没有配置就一一对应
    'columns' => array(
        //'source sql结果集的字段名' => 'target表字段名',
    ),
);
