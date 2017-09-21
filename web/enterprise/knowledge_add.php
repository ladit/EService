<!DOCTYPE HTML>

<?php 
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';

  $sql = "SHOW TABLE STATUS LIKE 'knowledge'";
  $result = $link->query($sql);
  $row = $result->fetch_assoc();
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>知识库管理</title>
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
			主页 > <a href="knowledge_manage.php">知识库</a> > 知识添加
		  </div>
          <div class="panel-body">
            <div class="kno-list kno-list-inside">
              <div class="kno-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>知识添加</span> 
              </div>
              <div class="kno-list-body">
                <div class="kno-add">
                  <form id="form" action="../functions/action.php?action=addKnowledge" method="POST">
                    <div class="form-group">
                      <label for="k-id">知识号</label>
                      <input type="hidden" class="form-control" id="KID" name="KID"  value="<?php echo $row['Auto_increment'];?>" >
					  <br /><?php echo $row['Auto_increment'];?>
                    </div>
                    <div class="form-group">
                      <label for="kp-id">产品号</label>
					  <select class="form-control" id="PID" name="PID">
					  <?php
							$sql = "select * from product where PEID={$_SESSION['EID']} order by PID asc";
							$result = $link->query($sql);
							while($row = $result->fetch_assoc())
							{
								echo "<option value='{$row['PID']}'>{$row['PID']}&nbsp;  &nbsp;  &nbsp;  {$row['PName']}</option>";
							}
					  ?>
					</select>
                    </div>
                    <div class="form-group">
                      <label for="kp-id">是否主页</label>
                      <select class="form-control"  id="KIndex" name="KIndex">
                      <option value="1">是</option>
                      <option value="0">否</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="k-class">分类</label>
                      <input type="text" class="form-control" id="KClass" name="KClass">
                    </div>
                    <div class="form-group">
                      <label for="k-title">标题</label>
                      <input type="text" class="form-control" id="KTitle" name="KTitle">
                    </div>
                    <div class="form-group">
                      <label for="k-des">描述</label>
					            <textarea type="text" class="form-control" id="KDescription" name="KDescription" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="k-content">内容</label>
                      <textarea type="text" class="form-control" id="KContent" name="KContent" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="k-content">访问次数</label>
                      <input type="hidden" class="form-control" id="KVisitTime" name="KVisitTime" value="0">
					  <br />0
                    </div>
					<div class="form-group">
                      <label for="k-content">有用次数</label>
					   <input type="hidden" class="form-control" id="KUsefulTime" name="KUsefulTime" value="0">
					    <br />0
                    </div>
					<div class="form-group">
                      <label for="k-content">无用次数</label>
					   <input type="hidden" class="form-control" id="KUselessTime" name="KUselessTime" value="0">
					    <br />0
                    </div>

                    <input type="submit" class="btn form-button" value="添加">
                    <input type="reset" class="btn form-button" value="重置">
					<input type="button" class="btn form-button" onclick=location.href="knowledge_manage.php" value="返回">
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>