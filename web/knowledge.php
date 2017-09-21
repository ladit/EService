<!DOCTYPE HTML>

<?php
  require_once __DIR__ . '/functions/connect_database.php';
  if (empty($_GET["pid"]) or empty($_GET["kid"])) {
    echo '<script language=javascript>';
    echo 'alert("访问地址错误！");';
    echo 'location="/product_select.php";';
    echo '</script>';
    exit;
  }
  function testOutput($data) {
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    $data = nl2br($data);
    $data = str_replace(PHP_EOL, '', $data);
    return $data;
  }
  $product_id = $_GET["pid"];
  $knowledge_id = $_GET["kid"];
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>知识库</title>
    <link href="assets/css/knowledge.css" rel="stylesheet">
    <?php require __DIR__ . '/functions/header.php'; ?>
    <script type="text/javascript">
      <?php
        echo 'var product_id = "'.$product_id.'";';
        echo 'var knowledge_id = "'.$knowledge_id.'";';
      ?>
    </script>
  </head>
  <body>
    <div class="topbar">
      <span>EService</span>
    </div>
    <div class="main-container">
      <div class="container-left">
        <p>知识库</p>
        <ul class="nav nav-stacked" id="nav-stacked"></ul>
      </div>
      <div class="container-right">
        <div class="panel panel-default">
          <div class="panel-heading" id="panel-heading">标题</div>
          <div class="panel-body" id="panel-body">
            <b>描述：</b>
            <p id="KDescription"></p>
            <b>内容：</b>
            <p id="KContent"></p>
            <p>
              <span>
                <i class="glyphicon glyphicon-eye-open"></i>
                <span>阅读数</span>
                <span id="KVisitTime"></span>
              </span>
              <span id="useful-useless-percent"></span>
              <span>
                <button type="button" id="useful-btn" class="btn" onclick="useful()"><i class="glyphicon glyphicon-thumbs-up"></i>有帮助</button>
              </span>
              <span>
                <button type="button" id="useless-btn" class="btn" onclick="useless()"><i class="glyphicon glyphicon-thumbs-down"></i>无帮助</button>
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="assets/js/knowledge.js"></script>
  <?php
    $knowledgeClassQuery = "SELECT DISTINCT KClass FROM knowledge WHERE KPID = '".$product_id."';";
    $knowledgeClassQueryResultset = $link->query($knowledgeClassQuery);
    if ($knowledgeClassQueryResultset->num_rows) {
      echo '<script type="text/javascript">';
      while ($knowledgeClassQueryResult = $knowledgeClassQueryResultset->fetch_assoc()) {
          echo 'appendNavClass("'.testOutput($knowledgeClassQueryResult["KClass"]).'");';
      }
      echo '</script>';
      $knowledgeClassQueryResultset->close();

      $navKnowledgeQuery = "SELECT KID, KClass, KTitle FROM knowledge WHERE KPID = '".$product_id."';";
      $navKnowledgeQueryResultset = $link->query($navKnowledgeQuery);
      if ($navKnowledgeQueryResultset->num_rows) {
        echo '<script type="text/javascript">';
        while ($navKnowledgeQueryResult = $navKnowledgeQueryResultset->fetch_assoc()) {
            echo 'appendNavKnowledge("'.$navKnowledgeQueryResult["KID"].'","'.testOutput($navKnowledgeQueryResult["KClass"]).'","'.testOutput($navKnowledgeQueryResult["KTitle"]).'");';
        }
        echo '</script>';
        $navKnowledgeQueryResultset->close();
      }

      $knowledgeQuery = "SELECT * FROM knowledge WHERE KID = '".$knowledge_id."';";
      $knowledgeQueryResultset = $link->query($knowledgeQuery);
      if ($knowledgeQueryResultset->num_rows) {
        $knowledgeQueryResult = $knowledgeQueryResultset->fetch_assoc();
        echo '<script type="text/javascript"> appendKnowledge("'.testOutput($knowledgeQueryResult["KTitle"]).'","'.testOutput($knowledgeQueryResult["KDescription"]).'","'.testOutput($knowledgeQueryResult["KContent"]).'","'.$knowledgeQueryResult["KVisitTime"].'","'.$knowledgeQueryResult["KUsefulTime"].'","'.$knowledgeQueryResult["KUselessTime"].'"); </script>';
        $knowledgeQueryResultset->close();
        $knowledgeVisitTimeQuery = "UPDATE knowledge SET KVisitTime = KVisitTime+1 WHERE KID='".$knowledge_id."';";
        if ($link->query($knowledgeVisitTimeQuery) === FALSE) {
          die("knowledgeVisitTimeQueryError");
        }
      }
    }
    else {
      echo '<script type="text/javascript"> noKnowledge(); </script>';
    }
  ?>
</html>