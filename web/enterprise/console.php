<!DOCTYPE HTML>

<?php
  session_start();
  require_once "../functions/enterprise_authentication.php";
  require_once "../functions/connect_database.php";
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>企业管理中心</title>
    <link href="../css/co-manage.css" rel="stylesheet">
    <?php require_once "../functions/co-header.php"; ?>
  </head>
  <body>
    <div class="topbar">
        <span>SMART-Q&A</span>
        <div class="dropdown">
          <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $_SESSION['EName']; ?><span class="caret"></span></button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="../functions/action.php?action=enterpriseOut">退出</a></li>
          </ul>
        </div>
    </div>
    <div class="main-container">
      <div class="container-left">
        <ul class="nav nav-stacked">
          <li class="active"><a href="co-manage-center.php"><i class="glyphicon glyphicon-list-alt"></i><span>总览</span></a></li>
          <li><a href="co-manage-pro.php"><i class="glyphicon glyphicon-search"></i><span>产品管理</span></a></li>
          <li><a href="co-manage-cs.php"><i class="glyphicon glyphicon-cog"></i><span>客服设置</span></a></li>
          <li><a href="co-manage-kno.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库</span></a></li>
          <li><a href="co-manage-que.php"><i class="glyphicon glyphicon-question-sign"></i><span>问题库</span></a></li>
        </ul>
      </div>
      <div class="container-right">
        <div class="panel panel-default">
          <div class="panel-heading">主页 > 总览</div>
          <div class="panel-body">
            <div class="row-flex">
              <div class="pro-block">
                <div class="pro-block-top">产品数量</div>
                <?php
                  $sql = "select * from product where PEID={$_SESSION['EID']}";
                  $result = $link->query($sql);
                  $row_count = $result->num_rows;
                ?>
                <p><?= $row_count ?></p>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">问题数量</div>
                <?php
                  $sql = "select * from questions,product where QPID=PID and PEID={$_SESSION['EID']}";
                  $result = $link->query($sql);
                  $row_count = $result->num_rows;
                ?>
                <p><?= $row_count ?></p>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">知识库评估</div>
                <?php
                  $sql = "select * from   knowledge,product where KPID=PID and PEID={$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $row_count = $result->num_rows;
                ?>
                <p><?= $row_count ?></p>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">客服数量</div>
                <?php
                  $sql = "select * from   `customer-service` where CSEID={$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $row_count = $result->num_rows;
                ?>
                <p><?= $row_count ?></p>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">服务次数</div>
                <?php
                  $sql = "select * from cases,`customer-service` where CCSID=CSID and CSEID={$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $row = $result->num_rows;
                ?>
                <p><?= $row_count ?></p>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">咨询热词</div>
                <?php
                  $sql = "select * from   questions,product where QPID=PID and PEID={$_SESSION['EID']} and QUnanswerable='0' ";
                  $result = $link->query($sql);
                  $row_count = $result->num_rows;
                ?>
                <p><?= $row_count ?></p>
              </div>
            </div>
            <div class="row-flex">
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../fonts/box1.png" alt="">                 
                  <div class="inform-block-detail">
                    <p>流量</p>
                    <p>271</p>
                  </div>
                </div>
                <div class="inform-block-footer">
                  <a href="">详细报告</a>
                </div>
              </div>
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../fonts/box2.png" alt="">
                  <div class="inform-block-detail">
                    <p>产品问题分析</p>
                    <p>6</p>
                  </div>
                </div>
                <div class="inform-block-footer">
                  <a href="">详细报告</a>
                </div>
              </div>
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../fonts/box3.png" alt="">
                  <div class="inform-block-detail">
                    <p>人工客服满意度</p>
                    <?php
                      $sql = "select * from cases,`customer-service` where CCSID=CSID and CSEID={$_SESSION['EID']} ";
                      $result = $link->query($sql);
                      $row = $result->num_rows;
                      
                      $sql2 = "select * from cases,`customer-service` where CCSID=CSID and CSEID={$_SESSION['EID']} and CSatisfied='1'  ";
                      $result2 = $link->query($sql2);
                      $row2 = $result2->num_rows;
                    ?>
                    <p><?= $row2?>/<?=$row ?></p>
                    
                </div>
                </div>
                <div class="inform-block-footer">
                  <a href="">详细报告</a>
                </div>
              </div>
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../fonts/box4.png" alt="">
                  <div class="inform-block-detail">
                    <p>费用</p>
                    <p>￥183</p>
                  </div>	  
                </div>
                <div class="inform-block-footer">
                  <a href="">详细报告</a>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </body>
</html>