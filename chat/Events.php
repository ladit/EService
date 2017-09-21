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
use Fukuball\Jieba\JiebaAnalyse;

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
                        'client_id' => 'is_busy',
                        'client_id' => 'is_busy',
                        'client_id' => 'is_busy',
                    ),
    'product_id' => array(
                        'client_id' => 'is_busy',
                        'client_id' => 'is_busy',
                        'client_id' => 'is_busy',
                    ),
    );
*/

class Events
{
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
                foreach ($product_cs_list as $cs_client_id => $is_busy) {
                    if ($is_busy == 0) {  //找到空闲客服，把用户从服务队列中取出，把客服列表中的忙状态置为1，把两者加入case_id的聊天室
                        $user_client_id = array_shift($serve_queue[$product_id]);
                        $cs_list[$product_id][$cs_client_id] = 1;
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
                        Gateway::updateSession($user_client_id, array('binded_cs_client_id' => $cs_client_id));
                        Gateway::updateSession($cs_client_id, array('room_id' => $case_id));
                        Gateway::updateSession($cs_client_id, array('binded_user_client_id' => $user_client_id));
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
                }
                //所有在线客服都忙，为用户计时，超时发出暂时无法服务
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
                    'type' => 'cant_serve_busy',
                    'wait_time' => $wait_time
                );
                Gateway::sendToClient($client_id, json_encode($user_message));
                return;

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
                $cs_list[$product_id][$client_id] = 0;
                return;

            // 发言操作
            // 检验是否在case_id的聊天室中
            // 发言
            // message: {type:4, content:xxx}
            case '4':
                if (!isset($_SESSION['room_id'])) {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                if (empty($message_data['content'])) {
                    return;
                }
                $room_id = $_SESSION['room_id'];
                if (isset($_SESSION['binded_cs_client_id'])) {
                    $new_message = array(
                        'type' => 'say',
                        'content'=>nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($_SESSION['binded_cs_client_id'], json_encode($new_message));
                    return;
                }
                else if (isset($_SESSION['binded_user_client_id'])) {
                    $new_message = array(
                        'type' => 'say',
                        'content'=>nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($_SESSION['binded_user_client_id'], json_encode($new_message));
                    return;
                }
                else {
                    throw new \Exception("\$_SESSION['binded_cs_client_id'] or \$_SESSION['binded_user_client_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                return;
            
            // 用户退出后，客服记录操作
            // 
            // message: {type:5, question:xxx}
            case '5':
                if (empty($message_data['question'])) {
                    return;
                }
                $product_id = $_SESSION['product_id'];
                $case_id = $_SESSION['room_id'];
                $question = $message_data['question'];
                // 无法回答问题插入问题表
                $questionQuery = "INSERT INTO questions (QPID, QTitle, QVisitTime, QUsefulTime, QUselessTime, QUnanswerable) VALUES ('".$product_id."', '".$question."', '1', '0', '0', '1');";
                if ($link->query($questionQuery) === FALSE) {
                    throw new \Exception("New Unanswerable Question Query failed.");
                }
                $questionIDQuery = "SELECT LAST_INSERT_ID() AS QID;";
                $questionIDQueryResultset = $link->query($questionIDQuery);
                if ($questionIDQueryResultset->num_rows) {
                    $questionIDQueryResult = $questionIDQueryResultset->fetch_assoc();
                    $question_id = $questionIDQueryResult['QID'];
                    $questionIDQueryResultset->free();
                }
                else {
                    throw new \Exception("Last Unanswerable Question ID Query failed.");
                }

                // 无法回答问题插入case-question
                $caseQuestionQuery = "INSERT INTO `case-question` (CQCID, CQQID) VALUES ('".$case_id."', '".$question_id."');";
                if ($link->query($caseQuestionQuery) === FALSE) {
                    throw new \Exception("Case Question Query failed.");
                }

                // 关键词记录

                // 输入关键词提取
                $question = preg_replace("/\s(?=\s)/","\\1", $question);
                $question = str_replace(array("\r\n", "\r", "\n"), "", $question);
                $question = strip_tags($question);
                $question = trim($question);

                $top_k = 6;
                $keywords = JiebaAnalyse::extractTags($question, $top_k);

                // 已有关键词表
                $keywordTableQuery = "SELECT WID, WContent FROM word;";
                $keywordTableQueryResultset = $link->query($keywordTableQuery);
                if ($keywordTableQueryResultset->num_rows) {
                    $keywordTable = array();
                    while ($keywordTableQueryResult = $keywordTableQueryResultset->fetch_assoc()) {
                        $keywordTable[$keywordTableQueryResult["WID"]] = $keywordTableQueryResult["WContent"];
                    }
                    $keywordTableQueryResultset->free();
                }

                if (empty($keywordTable)) {  // 已有关键词表为空，插入新的关键词
                    foreach ($keywords as $keyword => $value) {
                        $newKeywordQuery = "INSERT INTO Word (WContent, WVisitTime) VALUES ('".$keyword."', '1');";
                        if ($link->query($newKeywordQuery) === FALSE) {
                            throw new \Exception("new Keyword Query failed.");
                        }
                        $newKeywordIDQuery = "SELECT LAST_INSERT_ID() AS WID;";
                        $newKeywordIDQueryResultset = $link->query($newKeywordIDQuery);
                        if ($newKeywordIDQueryResultset->num_rows) {
                                $newKeywordIDQueryResult = $newKeywordIDQueryResultset->fetch_assoc();
                                $word_id = $newKeywordIDQueryResult['WID'];
                                $newKeywordIDQueryResultset->free();
                        }
                        else {
                            throw new \Exception("Last New Keyword ID Query failed.");
                        }
                        // 问题关键词绑定
                        $questionWordQuery = "INSERT INTO `question-word` (QWQID, QWWID) VALUES ('".$question_id."', '".$word_id."');";
                        if ($link->query($questionWordQuery) === FALSE) {
                            throw new \Exception("Question Word Query failed.");
                        }
                    }
                }
                else {  // 已有关键词表非空
                    foreach ($keywords as $keyword => $value) {
                        $exist_in_keywordTable = false;
                        foreach ($keywordTable as $word_id => $word) {
                            if ($keyword == $word) { // 输入的关键词在已有关键词表里
                                $keywordVisitQuery = "UPDATE Word SET WVisitTime = WVisitTime + 1 WHERE WID = '".$word_id."';";
                                if ($link->query($keywordVisitQuery) === FALSE) {
                                    throw new \Exception("keyword Visit Query failed.");
                                }
                                $exist_in_keywordTable = true;
                                break;
                            }
                        }
                        if (!$exist_in_keywordTable) {
                            // 输入的关键词不在已有关键词表里
                            $newKeywordQuery = "INSERT INTO Word (WContent, WVisitTime) VALUES ('".$keyword."', '1');";
                            if ($link->query($newKeywordQuery) === FALSE) {
                                throw new \Exception("new Keyword Query failed.");
                            }
                            $newKeywordIDQuery = "SELECT LAST_INSERT_ID() AS WID;";
                            $newKeywordIDQueryResultset = $link->query($newKeywordIDQuery);
                            if ($newKeywordIDQueryResultset->num_rows) {
                                $newKeywordIDQueryResult = $newKeywordIDQueryResultset->fetch_assoc();
                                $word_id = $newKeywordIDQueryResult['WID'];
                                $newKeywordIDQueryResultset->free();
                            }
                            else {
                                throw new \Exception("Last New Keyword ID Query failed.");
                            }
                        }
                        // 问题关键词绑定
                        $questionWordQuery = "INSERT INTO `question-word` (QWQID, QWWID) VALUES ('".$question_id."', '".$word_id."');";
                        if ($link->query($questionWordQuery) === FALSE) {
                            throw new \Exception("Question Word Query failed.");
                        }
                    }
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
            $product_cs_list = $cs_list[$product_id];
            foreach ($product_cs_list as $cs_client_id => $is_busy) {
                if ($cs_client_id == $client_id) {
                    unset($cs_list[$product_id][$client_id]);
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
                        $cs_list[$product_id][$temp_client_id] = 0;
                        $new_message = array('type'=>'user_logout');
                        Gateway::sendToClient($temp_client_id, json_encode($new_message));
                        Gateway::leaveGroup($temp_client_id, $room_id);
                        break;
                    }
                }
            }
            else {
                $product_serve_queue = $serve_queue[$product_id];
                foreach ($product_serve_queue as $index => $user_client_id) {
                    if ($user_client_id == $client_id) {
                        unset($serve_queue[$product_id][$index]);
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