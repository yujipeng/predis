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

// 使用 sort set 实现优先级队列功能

$priority_queue_list = 'priority_queue_list';

$client->del($priority_queue_list);

for($i = 0 ; $i < 20 ; $i ++) {

    $weight = rand(1, 10);
    $value  = rand(100, 999);
    $unique_id = ceil(microtime(true)*1000).'_'.$i.'_'.$value;
    var_dump($unique_id);
    $action = $client->zadd($priority_queue_list, $weight, $unique_id);
}

$all_values = $client->zrange($priority_queue_list, 0, -1, ['withscores' => true]);

echo $priority_queue_list.PHP_EOL;
var_dump($all_values);


for($i = 0 ; $i < 10 ; $i ++) {
    $limit = $client->zRevRangeByScore($priority_queue_list, '+inf', '-inf', array('withscores'=>false, 'limit'=>array(0,3)));
    echo 'limit = '.$i.PHP_EOL;
    var_dump($limit);
    foreach($limit as $value){
        $pid = pcntl_fork();
        if ($pid == -1) {
            die('could not fork');
        } else if ($pid) {
            // we are the parent
            pcntl_wait($status); //Protect against Zombie children
            echo 'parent pid='.$pid. " child status $status ".PHP_EOL;
        } else {
            // we are the child
            var_dump($value);
            $del = $client->zrem($priority_queue_list, $value);
            exit;
        }
    }
}



// Say goodbye :-)
$version = redis_version($client->info());
echo "Goodbye from Redis $version!", PHP_EOL;
