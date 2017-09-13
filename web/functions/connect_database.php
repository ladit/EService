<?php
//数据库配置信息
define("DB_HOST","localhost");      //主机名
define("DB_USER","root");           //账号
define("DB_PASSWORD","123456");     //密码
define("DB_NAME","e-service");      //数据库名

$link = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($link->connect_errno) {
    die('Connect Error (' . $link->connect_errno . ') ' . $link->connect_error);
}
?>