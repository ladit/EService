<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
use \Workerman\Worker;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

// 自动加载类
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../web/functions/connect_database.php';

// bussinessWorker 进程
$worker = new BusinessWorker();
// worker名称
$worker->name = 'ChatBusinessWorker';
// bussinessWorker进程数量
$worker->count = 4;
// 服务注册地址
$worker->registerAddress = '127.0.0.1:1236';

/*
全局服务队列数组
$serve_queue = array
(
    'product_id' => array('client_id','client_id','client_id'),
    'product_id' => array('client_id','client_id','client_id'),
    'product_id' => array('client_id','client_id','client_id'),
);

全局客服列表数组
$cs_list = array
(
'product_id' => array(
                    array('client_id','busy'),
                    array('client_id','busy'),
                    array('client_id','busy'),
                ),
'product_id' => array(
                    array('client_id','busy'),
                    array('client_id','busy'),
                    array('client_id','busy'),
                ),
);
*/

$serve_queue = array();
$cs_list = array();

$worker->onWorkerStart = function($connection)
{
    ini_set('memory_limit', '1024M');
    date_default_timezone_set('Asia/Shanghai');
    Jieba::init();
    Finalseg::init();
    global $serve_queue;
    global $cs_list;
    global $link;
};

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}
