<!DOCTYPE HTML>

 <?php
  session_start();
  require_once __DIR__ . '/functions/connect_database.php';
  if (!isset($_SESSION["PID"]) or empty($_SESSION["PID"])) {
    echo '<script language=javascript>';
    echo 'alert("请选择产品！")';
    echo 'location="/product_select.php"';
    echo '</script>';
    exit();
  }
  if (!isset($_SESSION["CID"]) or empty($_SESSION["CID"])) {
    $newCaseBeginTime = date('Y-m-d H:i:s');
    $newCaseQuery = "INSERT INTO cases (CBeginTime) VALUES ('".$newCaseBeginTime."');";
    if ($link->query($newCaseQuery) === FALSE) {
      die("new case query error!");
    }
    $newCaseIDQuery = "SELECT CID FROM cases WHERE CBeginTime = '".$newCaseBeginTime."';";
    $newCaseIDQueryResultset = $link->query($newCaseIDQuery);
    if ($newCaseIDQueryResultset->num_rows) {
      $newCaseIDQueryResult = $newCaseIDQueryResultset->fetch_assoc();
      $_SESSION["CID"] = $newCaseIDQueryResult["CID"];
      $newCaseIDQueryResultset->close();
    }
    if (!isset($_SESSION["CID"]) or empty($_SESSION["CID"])) {
      die("new case id query error!");
    }
  }
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在线客服</title>
    <link href="assets/css/chat-common.css" rel="stylesheet">
    <?php require __DIR__ . '/functions/header.php'; ?>
    <script type="text/javascript">
      <?php
        echo 'var product_id = "'.$_SESSION["PID"].'";';
        echo 'var case_id = "'.$_SESSION["CID"].'";';
      ?>
    </script>
  </head>
  <body onload="connect();">
    <div class="sidebar">
      <div class="sidebar-top">
        <span>EService</span>
      </div>
      <div class="sidebar-mid">
        <span>热门问题</span>
      </div>
      <div class="sidebar-bot">
        <ul class="nav">
          <?php
            $sql = "select * from questions where QPID={$_SESSION['PID']} and QUnanswerable='0' order by QVisitTime desc";
            $result = $link->query($sql);
            while($row = $result->fetch_assoc())
            {
              echo '<li><a href="javascript:sendQuestion(\''.$row['QTitle'].'\')">'.$row['QTitle'].'</a></li>';
            }
            $result->close();
          ?>
        </ul>
      </div>
    </div>
    <div class="my-container">
      <div class="container-topbar">
        <nav class="navbar navbar-inverse">
          <ul class="nav nav-pills nav-inverse">
            <li role="presentation"><a href="#">问题反馈</a></li>
          </ul>
        </nav>
      </div>
      <div class="containerr">
        <div class="container-lef">
          <div class="container-lef-top">
            <span id="appraise">
              <span>请评价客服：</span>
              <button type="button" id="satisfied-btn" class="btn" onclick="appraise(1)"><i class="glyphicon glyphicon-thumbs-up"></i>满意</button>
							<button type="button" id="unsatisfied-btn" class="btn" onclick="appraise(0)"><i class="glyphicon glyphicon-thumbs-down"></i>不满意</button>
            </span>
          </div>
          <div class="container-lef-bot">
            <div class="chatting-content"></div>
            <div class="chatting-sending">
              <form>
                <textarea name="" id="sendArea"></textarea>
                <div class="chatting-sending-btn">
                  <button type="button" class="btn btn-primary" onclick="sendMessage();">发送</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
          $sql = "select * from product where PID={$_SESSION['PID']} ";
          $result = $link->query($sql);
          $row = $result->fetch_assoc();
        ?>
        <div class="container-rig">
          <div class="container-rig-top">
            <span>产品相关信息</span>
          </div>
          <div class="container-rig-bot">
            <span>产品名称</span>
            <p><?php echo $row['PName']; ?></p>
            <br /><br />
            <span>产品信息</span>
            <p><?php echo $row['PIntroduction']; ?></p>
            <img id="pro-image-place" src="functions/action.php?action=showProductImage&PID=<?=$_SESSION['PID']?>" />
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="assets/js/swfobject.js"></script>
    <script type="text/javascript" src="assets/js/web_socket.js"></script>
    <script type="text/javascript" src="assets/js/chat_arti.js"></script>
  </body>
</html>