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

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose 
 */
use \GatewayWorker\Lib\Gateway;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

class Events
{
    function __construct() {
        ini_set('memory_limit', '1024M');
        Jieba::init();
        Finalseg::init();
    }

   /**
    * 有消息时
    * @param int $client_id
    * @param mixed $message
    */
    public static function onMessage($client_id, $message)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";
        
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return;
        }

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
        global $serve_queue;
        global $cs_list;
        global $link;

        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // pong操作
            // 用户客户端回应服务端的心跳
            // 若无客服在线，则为所有用户计时，超时发出暂时无法服务
            // 检测空闲客服，若有把两者加入case_id的聊天室
            // 所有在线客服都忙，为用户计时，超时发出暂时无法服务
            // message格式: {type:0, product_id:xxx}
            case '0':
                // debug
                // echo "-------serve_queue------\n";
                // var_dump($serve_queue);
                // echo "------------------------\n";

                if (isset($_SESSION['room_id'])) { // 在房间中则直接pong
                    return;
                }
                $product_id = $message_data['product_id'];
                if(!isset($serve_queue[$product_id]) or empty($serve_queue[$product_id]))  //服务队列数组中某产品的服务队列不存在或为空抛出异常
                {
                    throw new \Exception("\$serve_queue[\$product_id] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                if(!isset($cs_list[$product_id]) or empty($cs_list[$product_id]))  //客服列表数组中某产品的客服列表不存在或为空，为用户计时，超时发出暂时无法服务
                {
                    if (isset($_SESSION['wait_time'])) {
                        if ($_SESSION['wait_time'] == 290) {
                            $user_message = array('type' => 'cant_serve_overtime');
                            Gateway::sendToClient($client_id, json_encode($user_message));
                            return;
                        }
                        $wait_time = $_SESSION['wait_time'] + 10;
                    }
                    else {
                        $wait_time = 10;
                    }
                    $_SESSION['wait_time'] = $wait_time;
                    $user_message = array(
                        'type' => 'no_cs_online',
                        'wait_time' => $wait_time
                    );
                    Gateway::sendToClient($client_id, json_encode($user_message));
                    return;
                }
                //寻找空闲客服
                $product_cs_list = $cs_list[$product_id];
                $cs_list_quantity = count($product_cs_list);
                for ($i = 0; $i < $cs_list_quantity; $i++) {
                    $cs = $product_cs_list[$i];
                    if ($cs[1] == 0) {
                        break;
                    }
                }
                if ($i < $cs_list_quantity) {  //找到空闲客服，把用户从服务队列中取出，把客服列表中的忙状态置为1，把两者加入case_id的聊天室
                    $user_client_id = array_shift($serve_queue[$product_id]);
                    $cs_list[$product_id][$i][1] = 1;
                    $cs_client_id = $cs[0];
                    $user_sessions = Gateway::getSession($user_client_id);
                    $cs_sessions = Gateway::getSession($cs_client_id);
                    $case_id = $user_sessions['case_id'];
                    $cs_id = $cs_sessions['cs_id'];

                    // cs_id插入数据库
                    $caseCSIDQuery = "UPDATE cases SET CCSID = '".$cs_id."' WHERE CID = '".$case_id."';";
                    if ($link->query($caseCSIDQuery) === FALSE) {
                        throw new \Exception("\$link->query(\$caseCSIDQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                    }
                    // 加入房间并通知
                    Gateway::joinGroup($user_client_id, $case_id);
                    Gateway::joinGroup($cs_client_id, $case_id);
                    Gateway::updateSession($user_client_id, array('room_id' => $case_id));
                    Gateway::updateSession($cs_client_id, array('room_id' => $case_id));
                    $user_message = array(
                        'type' => 'cs_connected',
                        'cs_id' => $cs_id,
                        'time' => date('Y-m-d H:i:s')
                        );
                    Gateway::sendToClient($user_client_id, json_encode($user_message));
                    $cs_message = array(
                        'type' => 'user_connected',
                        'time' => date('Y-m-d H:i:s')
                        );
                    Gateway::sendToClient($cs_client_id, json_encode($cs_message));
                    return;
                }
                else {  //所有在线客服都忙，为用户计时，超时发出暂时无法服务
                    if (isset($_SESSION['wait_time'])) {
                        if ($_SESSION['wait_time'] == 290) {
                            $user_message = array('type' => 'cant_serve_overtime');
                            Gateway::sendToClient($client_id, json_encode($user_message));
                            return;
                        }
                        $wait_time = $_SESSION['wait_time'] + 10;
                    }
                    else {
                        $wait_time = 10;
                    }
                    $_SESSION['wait_time'] = $wait_time;
                    $user_message = array(
                        'type' => 'no_cs_online',
                        'wait_time' => $wait_time
                    );
                    Gateway::sendToClient($client_id, json_encode($user_message));
                    return;
                }

            // pong操作
            // 客服客户端回应服务端的心跳
            // message格式: {type:1}
            case '1':

                return;
            
            // 用户登录操作
            // 根据其要求的产品id，加入服务队列
            // message格式: {type:2, product_id:xxx, case_id:xxx}
            case '2':
                $product_id = $message_data['product_id'];
                $case_id = $message_data['case_id'];
                $_SESSION['product_id'] = $product_id;
                $_SESSION['case_id'] = $case_id;
                if (!isset($serve_queue[$product_id])) {
                    $serve_queue[$product_id] = array();
                }
                array_push($serve_queue[$product_id], $client_id);
                return;
                
            // 客服登录操作
            // 加入xx产品在线客服列表
            // message格式: {type:3, cs_id:xxx, product_id:xxx}
            case '3':
                $cs_id = $message_data['cs_id'];
                $product_id = $message_data['product_id'];
                $_SESSION['cs_id'] = $cs_id;
                $_SESSION['product_id'] = $product_id;
                if (!isset($cs_list[$product_id])) {
                    $cs_list[$product_id] = array();
                }
                $cs_client_busy_doublet = array($client_id, 0);
                array_push($cs_list[$product_id], $cs_client_busy_doublet);
                return;

            // 发言操作
            // 检验是否在case_id的聊天室中
            // 发言
            // message: {type:4, content:xxx}
            case '4':
                if (!isset($_SESSION['room_id'])) {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $room_sessions = Gateway::getClientSessionsByGroup($room_id);
                foreach ($room_sessions as $temp_client_id => $item) {
                    if ($temp_client_id != $client_id) {
                        $new_message = array(
                            'type' => 'say',
                            'content'=>nl2br(htmlspecialchars($message_data['content'])),
                            'time'=>date('Y-m-d H:i:s'),
                        );
                        Gateway::sendToClient($temp_client_id, json_encode($new_message));
                        break;
                    }
                }
                return;
            
            // 用户退出，客服记录操作
            // 
            // message: {type:5, question_id:xxx/ question:xxx}
            case '5':
                if (isset($message_data['question_id'])) {
                    $caseQuestionQuery = "INSERT INTO `case-question` (CQCID, CQQID) VALUES ('".$_SESSION['room_id']."', '".$message_data['question_id']."');";
                    if ($link->query($caseQuestionQuery) === FALSE) {
                        throw new \Exception("\$link->query(\$caseQuestionQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                    }
                    return;
                }
                if (empty($message_data['question'])) {
                    return;
                }
                $question = $message_data['question'];
                $questionQuery = "INSERT INTO questions (QPID, QTitle, QVisitTime, QUsefulTime, QUselessTime, QUnanswerable) VALUES ('".$_SESSION['product_id']."', '".$question."', '1', '0', '0', '1');";
                if ($link->query($questionQuery) === FALSE) {
                    throw new \Exception("\$link->query(\$questionQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                $questionIDQuery = "SELECT QID FROM questions WHERE QTitle = '".$question."';";
                $questionIDQueryResultset = $link->query($questionIDQuery);
                if ($questionIDQueryResultset->num_rows) {
                    $questionIDQueryResult = $questionIDQueryResultset->fetch_assoc();
                    $question_id = $questionIDQueryResult['QID'];
                    $questionIDQueryResultset->free();
                }
                else {
                    throw new \Exception("\$link->query(\$questionIDQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }

                /* $url = "http://120.26.6.172/get.php?source=".$question."&param1=0.5&param2=0";
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch,CURLOPT_HEADER, false);
                $words = explode("\n",trim(curl_exec($ch)));
                curl_close($ch); */

                $words = Jieba::cut(explode("\n",trim($question)));

                foreach ($words as $word) {
                    $wordQuery = "INSERT INTO word (WQID, WContent) VALUES ('".$question_id."', '".$word."');";
                    if ($link->query($wordQuery) === FALSE) {
                        throw new \Exception("\$link->query(\$wordQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                    }
                }
                $caseQuestionQuery = "INSERT INTO `case-question` (CQCID, CQQID) VALUES ('".$_SESSION['room_id']."', '".$question_id."');";
                if ($link->query($caseQuestionQuery) === FALSE) {
                    throw new \Exception("\$link->query(\$caseQuestionQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                return;

                // 用户评价客服操作
                // message: {type:6, isSatisfied:1/0}
                case '6':
                    $isSatisfiedQuery = "UPDATE cases SET CSatisfied = '".$message_data['isSatisfied']."' WHERE CID = '".$_SESSION['case_id']."';";
                    if ($link->query($isSatisfiedQuery) === FALSE) {
                        throw new \Exception("\$link->query(\$isSatisfiedQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                    }
                return;
        }
   }
   
   /**
    * 当客户端断开连接时
    * @param int $client_id
    */
   public static function onClose($client_id)
   {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
        
        global $serve_queue;
        global $cs_list;
        global $link;

        $product_id = $_SESSION['product_id'];

        if (isset($_SESSION['cs_id'])) {      // 客服退出
            $cs_id = $_SESSION['cs_id'];
            $cs_list_quantity = count($cs_list[$product_id]);
            for ($i = 0; $i < $cs_list_quantity; $i++) {
                if ($cs_list[$product_id][$i][0] == $client_id) {
                    unset($cs_list[$product_id][$i]);
                    $cs_list[$product_id] = array_values($cs_list[$product_id]);
                    break;
                }
            }
            if (isset($_SESSION['room_id'])) {
                $room_id = $_SESSION['room_id'];
                $room_sessions = Gateway::getClientSessionsByGroup($room_id);
                foreach ($room_sessions as $temp_client_id => $item) {
                    if ($temp_client_id != $client_id) {
                        $new_message = array('type'=>'cs_logout');
                        Gateway::sendToClient($temp_client_id, json_encode($new_message));
                        Gateway::leaveGroup($temp_client_id, $room_id);
                        break;
                        // 用户重新排队？
                    }
                }
            }
        }
        else {            // 用户退出
            if (isset($_SESSION['room_id'])) {
                $room_id = $_SESSION['room_id'];
                $room_sessions = Gateway::getClientSessionsByGroup($room_id);
                foreach ($room_sessions as $temp_client_id => $item) {
                    if ($temp_client_id != $client_id) {
                        $product_cs_list = $cs_list[$product_id];
                        $cs_list_quantity = count($product_cs_list);
                        for ($i = 0; $i < $cs_list_quantity; $i++) {
                            $cs = $product_cs_list[$i];
                            if ($cs[0] == $temp_client_id) {
                                $cs_list[$product_id][$i][1] = 0;
                                break;
                            }
                        }
                        $new_message = array('type'=>'user_logout');
                        Gateway::sendToClient($temp_client_id, json_encode($new_message));
                        Gateway::leaveGroup($temp_client_id, $room_id);
                        break;
                    }
                }
            }
            else {
                $product_queue_quantity = count($serve_queue[$product_id]);
                for ($i = 0; $i < $product_queue_quantity; $i++) {
                    if ($serve_queue[$product_id][$i] == $client_id) {
                        unset($serve_queue[$product_id][$i]);
                        $serve_queue[$product_id] = array_values($serve_queue[$product_id]);
                        break;
                    }
                }
            }
            $caseEndTimeQuery = "UPDATE cases SET CEndTime = '".date('Y-m-d H:i:s')."' WHERE CID = '".$_SESSION['case_id']."';";
            if ($link->query($caseEndTimeQuery) === FALSE) {
                throw new \Exception("\$link->query(\$caseEndTimeQuery) failed. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
            }
        }
    }

}