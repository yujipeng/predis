# Predis 

## 关于 Redis 

* 官方网站：http://redis.io/
* 关于客户端的扩展推荐，php 推荐有两个，当前使用的是 Predis ： http://redis.io/clients


## 关于 Predis ：

* predis 官方wiki : https://github.com/nrk/predis
* 使用composer 安装 predis :  wiki :https://packagist.org/packages/predis/predis


## 通过predis 实现一些功能

* 类似examples中的示例，做了几个简单的功能实现
* 使用 list 数据类型，实现消息队列功能
        如果是多队列，可以使用brpop依次获取队列内容
        按照队列名称前后顺序，其特性等同于简单的优先级队列

* 使用 sort set 数据类型，实现优先级队列功能 
        虽然可以按照score获取相应的数据，如何删除相应的数据，是需要重视的问题


