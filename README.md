DcrPHP/DataToDb 
多源数据同步到数据库(mysql,sqlite,mssql,oracle等)  
1、支持api返回的数据同步到数据库  
2、支持数据库同步到数据库  

## 1、安装
　　composer require dcrphp/data-to-db  
　　如果非正式版本无法安装请：  
　　composer config minimum-stability dev  
　　composer config prefer-stable true  

## 3、配置
　　请看example/config/下的案例

## 4、使用
　　请看example/index.php    
    
## 5、说明
　　如果要实现自己的同步逻辑，请在src/Handler中实现。比如你要添加一个对config中handler=my类型的处理，请在src/Handler添加My的类，实现getDataList就可以

## 6、已知问题
　　1、大量数据的同步要优化