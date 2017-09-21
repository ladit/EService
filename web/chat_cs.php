<!DOCTYPE HTML>

 <?php
    session_start();
    require __DIR__ . '/functions/cs_authentication.php';
    require_once __DIR__ . '/functions/connect_database.php';
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
        echo 'var cs_id = "'.$_SESSION["CSID"].'";';
        echo 'var cs_name = "'.$_SESSION["CSName"].'";';
        echo 'var product_id = "'.$_SESSION["CSPID"].'";';
      ?>
    </script>
  </head>
  <body onload="connect();">
    <div class="sidebar">
      <div class="sidebar-top">
        <span>EService</span>
      </div>
      <div class="sidebar-mid">
        <span>客服信息</span>
      </div>
      <div class="sidebar-bot">
        <div class="cs-inform">
          <span>客服号：</span>
          <span><?php echo $_SESSION["CSID"]; ?></span>
        </div>
        <div class="cs-inform">
          <span>登录名：</span>
          <span><?php echo $_SESSION["CSName"]; ?></span>
        </div>
        <div class="cs-inform">
          <span>产品号：</span>
          <span><?php echo $_SESSION["CSPID"]; ?></span>
        </div>
      </div>
    </div>
    <div class="my-container">
      <div class="container-topbar">
        <nav class="navbar navbar-inverse">
          <ul class="nav nav-pills nav-inverse">
            <li role="presentation"><a href="functions/action.php?action=CSLogout">退出</a></li>
          </ul>
        </nav>
      </div>
      <div class="containerr">
        <div class="container-lef">
          <div class="container-lef-top">
            <span>聊天窗口</span>
          </div>
          <div class="container-lef-bot">
            <div class="chatting-content">
            </div>
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
          $sql = "select * from product where PID={$_SESSION['CSPID']} ";
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
            <img id="pro-image-place" src="functions/action.php?action=showProductImage&PID=<?=$_SESSION['CSPID']?>" />
          </div>
        </div>
      </div>
    </div>
    <div class="popup">
      <div class="popup-in">
        <h3>用户已退出！</h3>
        <form>
          <div class="form-group">
            <label for="new-problem">请提交主要问题</label>
            <input type="text" class="form-control" id="new-problem">
          </div>
        </form>
        <button type="button" class="btn" id="main-question-submit">提交</button>
        <button type="button" class="btn" onclick="$('.popup').hide();$('#new-problem').val('');">关闭</button>
      </div>   
    </div>
    <script type="text/javascript" src="assets/js/swfobject.js"></script>
    <script type="text/javascript" src="assets/js/web_socket.js"></script>
    <script type="text/javascript" src="assets/js/chat_cs.js"></script>
  </body>
</html>