<?php 
/**
 *      [verification-code] (C)2015-2100 jingwhale.
 *      
 *      This is a freeware
 *      $Id: codeimg.php 2015-02-05 20:53:56 jingwhale$
 */

//开启session
session_start();

//引入全局函数库（自定义）
require_once __DIR__ . '/captcha_image.php';

//运行验证码函数。通过数据库的_captcha方法，设置验证码的各种属性,生成图片
_captcha(208, 55, 6, false);

?>