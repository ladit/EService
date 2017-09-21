<!DOCTYPE HTML>

<?php 
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';

  $sql = "select * from knowledge,product where KPID=PID and KID={$_GET['KID']}";
  $result = $link->query($sql);
  $row = $result->fetch_assoc();	
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>知识库展示</title>
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
          <li><a href="cs_manage.php"><i class="glyphicon glyphicon-cog"></i><span>客服管理</span></a></li>
          <li class="active"><a href="#"><i class="glyphicon glyphicon-briefcase"></i><span>知识库管理</span></a></li>
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
			主页 > <a href="knowledge_manage.php">知识库</a> > 知识列表
		  </div>
          <div class="panel-body">
            <div class="kno-list kno-list-inside">
              <div class="kno-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>知识列表</span> 
              </div>
              <div class="kno-list-body">
                <div class="kno-show">
                  <div class="kno-show-item">
                    <label for="">知识号</label>
                    <span><?php echo $row['KID']; ?></span>
                  </div>
                  <div class="kno-show-item">
                    <label for="">产品名</label>
                    <span><?php echo $row['PName']; ?></span>
                  </div>
                  <div class="kno-show-item">
                    <label for="">是否主页</label>
                    <span>
						<?php 
							if($row['KIndex']=='1')
								echo "是"; 
							else
								echo "不是";
						?>
					</span>
                  </div>
                  <div class="kno-show-item">
                    <label for="">分类</label>
                    <span><?php echo $row['KClass']; ?></span>
                  </div>
                  <div class="kno-show-item">
                    <label for="">标题</label>
                    <div><?php echo $row['KTitle']; ?></div>
                  </div>
                  <div class="kno-show-item">
                    <label for="">描述</label>
                    <div> <?php echo $row['KDescription']; ?></div>
                  </div>
                  <div class="kno-show-item">
                    <label for="">内容</label>
                    <div><?php echo $row['KContent']; ?></div>
                  </div>
				  <div class="kno-show-item">
                    <label for="">访问次数</label>
                    <div><?php echo $row['KVisitTime']; ?></div>
                  </div>
				  <div class="kno-show-item">
                    <label for="">有用次数</label>
                    <div><?php echo $row['KUsefulTime']; ?></div>
                  </div>
				   <div class="kno-show-item">
                    <label for="">无用次数</label>
                    <div><?php echo $row['KUselessTime']; ?></div>
                  </div>
                  <button type="buttom" class="btn" onclick=location.href="knowledge_manage.php">返回</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>