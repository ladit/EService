<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业管理中心</title>
    <link href="../assets/css/enterprise-common.css" rel="stylesheet">
    <?php require __DIR__ . '/../functions/header.php'; ?>
  </head>
  <body>
    <div class="topbar">
        <span>EService</span>
        <div class="dropdown">
          <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $_SESSION['EName']; ?></button>
          <a class="btn btn-danger" href="../functions/action.php?action=enterpriseLogout">退出</a>
        </div>
    </div>
    <div class="main-container">
      <div class="container-left">
        <ul class="nav nav-stacked">
          <li class="active"><a href="#"><i class="glyphicon glyphicon-list-alt"></i><span>总览</span></a></li>
          <li><a href="bill.php"><i class="glyphicon glyphicon-usd"></i><span>账单</span></a></li>
          <li><a href="enterprise_manage.php"><i class="glyphicon glyphicon-edit"></i><span>企业信息管理</span></a></li>
          <li><a href="product_manage.php"><i class="glyphicon glyphicon-th-large"></i><span>产品管理</span></a></li>
          <li><a href="cs_manage.php"><i class="glyphicon glyphicon-cog"></i><span>客服管理</span></a></li>
          <li><a href="knowledge_manage.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库管理</span></a></li>
          <li><a href="question_manage.php"><i class="glyphicon glyphicon-question-sign"></i><span>问题库管理</span></a></li>
          <li><a href="case_list.php"><i class="glyphicon glyphicon-list-alt"></i><span>服务记录</span></a></li>
          <li><a href="knowledge_assess.php"><i class="glyphicon glyphicon-stats"></i><span>知识库评估</span></a></li>
          <li><a href="question_assess.php"><i class="glyphicon glyphicon-stats"></i><span>问题库分析</span></a></li>
          <li><a href="word_assess.php"><i class="glyphicon glyphicon-stats"></i><span>热词分析</span></a></li>
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
                  $sql = "SELECT count(PID) AS productCount FROM Product WHERE PEID = {$_SESSION['EID']}";
                  $result = $link->query($sql);
                  $row = $result->fetch_assoc();
                  $productCount = $row["productCount"];
                  $result->close();
                ?>
                <a href="product_manage.php"><?= $productCount ?></a>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">客服数量</div>
                <?php
                  $sql = "SELECT count(CSID) AS csCount FROM `customer-service` WHERE CSEID = {$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $row = $result->fetch_assoc();
                  $csCount = $row["csCount"];
                  $result->close();
                ?>
                <a href="cs_manage.php"><?= $csCount ?></a>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">问题数量</div>
                <?php
                  $sql = "SELECT count(QID) AS questionCount FROM Questions, Product WHERE QPID = PID AND PEID = {$_SESSION['EID']}";
                  $result = $link->query($sql);
                  $row = $result->fetch_assoc();
                  $questionCount = $row["questionCount"];
                  $result->close();
                ?>
                <a href="question_manage.php"><?= $questionCount ?></a>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">服务次数</div>
                <?php
                  $sql = "SELECT count(CID) AS caseCount FROM Cases, `customer-service` WHERE CCSID = CSID AND CSEID = {$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $row = $result->fetch_assoc();
                  $caseCount = $row["caseCount"];
                  $result->close();
                ?>
                <a href="case_list.php"><?= $caseCount ?></a>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">人工客服满意度</div>
                <?php
                  $sql = "SELECT count(CID) AS caseCount FROM cases, `customer-service` WHERE CCSID = CSID AND CSEID = {$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $row = $result->fetch_assoc();
                  $caseCount = $row["caseCount"];
                  $result->close();
                  
                  $sql = "SELECT count(CID) AS satisfiedCaseCount FROM cases, `customer-service` WHERE CCSID = CSID AND CSEID = {$_SESSION['EID']} AND CSatisfied = '1' ";
                  $result = $link->query($sql);
                  $row = $result->fetch_assoc();
                  $satisfiedCaseCount = $row["satisfiedCaseCount"];
                  $result->close();
                ?>
                <a href="cs_manage.php"><?= $satisfiedCaseCount ?>/<?= $caseCount ?></a>
              </div>
              <div class="pro-block">
                <div class="pro-block-top">访问量</div>
                <?php
                  $sql = "SELECT KVisitTime FROM Knowledge, Product WHERE KPID = PID AND PEID = {$_SESSION['EID']} ";
                  $result = $link->query($sql);
				          $KVisitTimeCount = 0; 
                  while ($row = $result->fetch_assoc()) {
                    $KVisitTimeCount += $row["KVisitTime"];
                  }
                  $result->close();
                  $sql = "SELECT QVisitTime FROM Questions, Product WHERE QPID = PID AND PEID = {$_SESSION['EID']} ";
                  $result = $link->query($sql);
                  $QVisitTimeCount = 0;
                  while ($row = $result->fetch_assoc()) {
                    $QVisitTimeCount += $row["QVisitTime"];
                  }
                  $result->close();
                  $visitTimeCount = $KVisitTimeCount + $QVisitTimeCount + $caseCount;
                ?>
                <a href="#"><?= $visitTimeCount ?></a>
              </div>
            </div>
            <div class="row-flex">
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../assets/fonts/box1.png" alt="">                 
                  <div class="inform-block-detail">
                    <p>知识库评估</p>
                    <?php
                      $sql = "SELECT count(KID) AS knowledgeCount FROM Knowledge, Product WHERE KPID = PID AND PEID = {$_SESSION['EID']} ";
                      $result = $link->query($sql);
                      $row = $result->fetch_assoc();
                      $knowledgeCount = $row["knowledgeCount"];
                      $result->close();
                    ?>
                    <a href="knowledge_assess.php"><?= $knowledgeCount ?></a>
                  </div>
                </div>
                <div class="inform-block-footer">
                  <a href="knowledge_assess.php">详细报告</a>
                </div>
              </div>
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../assets/fonts/box2.png" alt="">
                  <div class="inform-block-detail">
                    <p>问题库分析</p>
                    <?php
                      $sql = "SELECT count(QID) AS questionCount FROM Questions, Product WHERE QPID = PID AND PEID = {$_SESSION['EID']} AND QUnanswerable = '1' ";
                      $result = $link->query($sql);
                      $row = $result->fetch_assoc();
                      $questionCount = $row["questionCount"];
                      $result->close();
                    ?>
                    <a href="question_assess.php"><?= $questionCount ?></a>
                  </div>
                </div>
                <div class="inform-block-footer">
                  <a href="question_assess.php">详细报告</a>
                </div>
              </div>
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../assets/fonts/box3.png" alt="">
                  <div class="inform-block-detail">
                    <p>咨询热词</p>
                    <?php
                      $sql = "SELECT count(WID) AS wordCount FROM Word, `question-word`, Questions, Product WHERE WID = QWWID AND QWQID = QID AND QPID = PID AND PEID = {$_SESSION['EID']} ";
                      $result = $link->query($sql);
                      $row = $result->fetch_assoc();
                      $wordCount = $row["wordCount"];
                      $result->close();
                    ?>
                    <a href="word_assess.php"><?= $wordCount ?></a>
                  </div>
                </div>
                <div class="inform-block-footer">
                  <a href="word_assess.php">详细报告</a>
                </div>
              </div>
              <div class="inform-block">
                <div class="inform-block-top">
                  <img src="../assets/fonts/box4.png" alt="">
                  <div class="inform-block-detail">
                    <p>账单</p>
                    <?php
                      $chargeCOunt = 0;
                      $sql = "select * from cases,`customer-service` where CSID=CCSID and CSEID={$_SESSION['EID']} order by CID asc";
                      $result = $link->query($sql);
                      while($row = $result->fetch_assoc()) {
                          $timeCost = strtotime($row['CEndTime'])-strtotime($row['CBeginTime']);
                          if ($timeCost > 0) {
                            $charge = floor($timeCost/60*0.2*100)/100;
                            $chargeCOunt += $charge;
                          }
                      }
                    ?>
                    <a href="bill.php">￥<?= $chargeCOunt ?></a>
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
  <script type="text/javascript">
    function classToggle() {
      $(this).next().slideToggle();
      $(this).parent().prevAll().children('ul').slideUp();
      $(this).parent().nextAll().children('ul').slideUp();
      return false;
    }
    // $('ul.nav.nav-stacked li ul').hide();
  </script>
</html>