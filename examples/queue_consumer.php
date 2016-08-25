<?php

/*
 * This file is part of the Predis package.
 *
 * (c) Jeep Yujipeng <jeepyu@shijiebang.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/shared.php';


// Create a client and disable r/w timeout on the socket
$client = new Predis\Client($single_server + array('read_write_timeout' => 0));

// 使用 list 实现队列功能
$queue_list = 'queue_list';
for($i = 0 ; $i < 10 ; $i ++){
    $value = rand(100, 999);
    $lpush = $client->lpush($queue_list, $value);
}

echo $queue_list.PHP_EOL;
$all_queue_list = $client->lrange($queue_list, 0 , -1);
var_dump($all_queue_list);

$queue_list_high = 'queue_list_high';
for($i = 0 ; $i < 5 ; $i ++){
    $value = rand(1200, 1999);
    $lpush = $client->lpush($queue_list_high, $value);
}

echo $queue_list_high.PHP_EOL;
$all_queue_list = $client->lrange($queue_list_high, 0 , -1);
var_dump($all_queue_list);


echo "BRPOP 依次获取队列数据".PHP_EOL;
for($j = 0 ; $j < 25 ; $j ++){
    $brpop = $client->brpop([$queue_list_high, $queue_list], 1);
    var_dump($brpop);
}




// Say goodbye :-)
$version = redis_version($client->info());
echo "Goodbye from Redis $version!", PHP_EOL;
