DcrPHP/DataToDb 
多源数据同步到数据库(mysql,sqlite,mssql,oracle等)  
1、支持api返回的数据同步到数据库  
2、支持数据库同步到数据库  

## 1、安装
　　composer install dcrphp/data-to-db

## 3、配置
　　请看example/config/下的案例

## 4、使用
　　请看example/index.php    
    
## 5、说明
　　如果要实现自己的同步逻辑，请在src/Handler中实现

## 6、已知问题
　　1、大量数据的同步要优化