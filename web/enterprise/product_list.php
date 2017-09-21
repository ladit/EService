<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';

  $sql = "select * from product where PID={$_GET['PID']}";
  $result = $link->query($sql);
  $row = $result->fetch_assoc();
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>展示产品</title>
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
          <li class="active"><a href="#"><i class="glyphicon glyphicon-th-large"></i><span>产品管理</span></a></li>
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
          <div class="panel-heading">
			主页 > <a href="product_manage.php">产品库</a> > 产品展示
		  </div>
          <div class="panel-body">
            <div class="pro-list">
              <div class="pro-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>产品展示界面</span>               
              </div>
              <div class="pro-list-body">
                <div class="pro-show">
                  <div class="pro-show-left">
                    <div class="pro-show-item">
                      <label for="pro-id">产品id</label>
                      <p><?php echo $row['PID'];  ?></p>
                    </div>
                    <div class="pro-show-item">
                      <label for="pro-name">产品名称</label>
                      <p><?php echo $row['PName'];  ?></p>
                    </div>
                    <div class="pro-show-item">
                      <label for="pro-intro">产品介绍</label>
                      <p><?php echo $row['PIntroduction'];  ?></p>
                    </div>
                    <button type="buttom" class="btn" onclick=location.href="product_manage.php">返回</button>
                  </div>
                  <div class="pro-show-right">
                    <div class="pro-show-item">
                      <label for="pro-image">产品图片</label>
                      <img  id="pro-image-place" src="../functions/action.php?action=showProductImage&PID=<?=$_GET['PID']?>"   />
                    </div>
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