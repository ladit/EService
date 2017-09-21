<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';

  $sql = "select * from `customer-service`,product where CSPID=PID and CSID={$_GET['CSID']}";
  $result = $link->query($sql);
  $row = $result->fetch_assoc();
  
  $sql_countCCSID = "select * from cases where CCSID={$_GET['CSID']}";
  $result_countCCSID = $link->query($sql_countCCSID);
  $row_countCCSID = $result_countCCSID->num_rows;
            
  $sql_countSatisfied = "select * from cases where CCSID={$_GET['CSID']} and CSatisfied='1'  ";
  $result_countSatisfied = $link->query($sql_countSatisfied);
  $row_countSatisfied = $result_countSatisfied->num_rows;
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>客服信息</title>
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
          <li><a href="console.php"><i class="glyphicon glyphicon-list-alt"></i><span>总览</span></a></li>
          <li><a href="bill.php"><i class="glyphicon glyphicon-usd"></i><span>账单</span></a></li>
          <li><a href="enterprise_manage.php"><i class="glyphicon glyphicon-edit"></i><span>企业信息管理</span></a></li>
          <li><a href="product_manage.php"><i class="glyphicon glyphicon-th-large"></i><span>产品管理</span></a></li>
          <li class="active"><a href="cs_manage.php"><i class="glyphicon glyphicon-cog"></i><span>客服管理</span></a></li>
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
        <div class="panel-heading"><a href="console.php">主页</a> > <a href="cs_manage.php">客服管理</a> > 客服信息</div>
          <div class="panel-body">
            <div class="cs-list">
              <div class="cs-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>客服信息</span>
              </div>
              <div class="cs-list-body">
                <div class="cs-show">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>客服号</th>
                        <th>产品名</th>
                        <th>登录名</th>
                        <th>登录密码</th>
                        <th>满意度</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo $row['CSID'];?></td>
                        <td><?php echo $row['PName'];?></td>
                        <td><?php echo $row['CSName'];?></td>
                        <td><?php echo $row['CSPassword'];?></td>
                        <td><?php echo "{$row_countSatisfied}/{$row_countCCSID}";?></td>
                      </tr>
                    </tbody>
                  </table>
                  <button type="button" class="btn" onclick="window.location.href='cs_manage.php'">返回</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>