<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
  
  $sql = "select * from enterprise where EID={$_SESSION["EID"]}";
  $result = $link->query($sql);
  $row = $result->fetch_assoc();
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>企业信息管理</title>
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
          <li class="active"><a href="#"><i class="glyphicon glyphicon-edit"></i><span>企业信息管理</span></a></li>
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
          <div class="panel-heading"> 主页 > 企业信息 </div>
          <div class="panel-body">
            <div class="enter-list">
              <div class="enter-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>企业信息</span>               
              </div>
              <div class="enter-list-body">
                <div class="enter-show">
                  <div class="enter-show-left">
                    <div class="enter-show-item">
                      <p><label for="enter-name">企业名称</label>&emsp;&emsp;&emsp;&emsp;<?php echo $row['EName'];  ?></p>
                    </div>
					<div class="enter-show-item">
                      <p><label for="enter-name">法人</label>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<?php echo $row['ELegalPerson'];  ?></p>
                    </div>
					<div class="enter-show-item">
                      <p><label for="enter-name">法人身份证号</label>&emsp;&emsp;<?php echo $row['ELegalPersonID'];  ?></p>
                    </div>
					<div class="enter-show-item">
                      <p><label for="enter-name">登录名称</label>&emsp;&emsp;&emsp;&emsp;<?php echo $row['ELoginName'];  ?></p>
                    </div>
					<button type="buttom" class="btn" onclick=location.href="enterprise_modify.php">修改信息</button> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>