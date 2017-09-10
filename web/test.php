<?php
  ini_set('memory_limit', '1024M');
  
  require_once __DIR__ . '/../vendor/autoload.php';
  use Fukuball\Jieba\Jieba;
  use Fukuball\Jieba\Finalseg;
  use Fukuball\Jieba\JiebaAnalyse;

  //Jieba::init(array('mode'=>'test','dict'=>'small'));
  Jieba::init(array('dict'=>'small'));
  Finalseg::init();
  JiebaAnalyse::init();
  
  $top_k = 6;
  
  $tags = JiebaAnalyse::extractTags("小明硕士毕业于中国科学院计算所，后在日本京都大学深造", $top_k);
  
  var_dump($tags);
?>