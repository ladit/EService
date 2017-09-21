<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>问题管理</title>
    <link href="../assets/css/enterprise-common.css" rel="stylesheet">
    <?php require __DIR__ . '/../functions/header.php'; ?>
    <script>
      function deleteQuestions(QID) {
        if(confirm("确定要删除吗？")) {
          window.location="../functions/action.php?action=deleteQuestions&QID="+QID;
        }
      }
	  </script>
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
          <li><a href="knowledge_manage.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库管理</span></a></li>
          <li class="active"><a href="#"><i class="glyphicon glyphicon-question-sign"></i><span>问题库管理</span></a></li>
          <li><a href="case_list.php"><i class="glyphicon glyphicon-list-alt"></i><span>服务记录</span></a></li>
          <li><a href="knowledge_assess.php"><i class="glyphicon glyphicon-stats"></i><span>知识库评估</span></a></li>
          <li><a href="question_assess.php"><i class="glyphicon glyphicon-stats"></i><span>问题库分析</span></a></li>
          <li><a href="word_assess.php"><i class="glyphicon glyphicon-stats"></i><span>热词分析</span></a></li>
        </ul>
      </div>
      <div class="container-right">
        <div class="panel panel-default">
          <div class="panel-heading">主页 > 问题库</div>
          <div class="panel-body">
            <div class="que-list">
              <div class="que-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>问题列表</span>               
              </div>
              <div class="que-list-body">
                <div class="search-button-flex">
                  <button type="button" class="btn" onclick=location.href="question_add.php">添加问题</button>
                  <form action="">
                    <label for="search">
                      <span>search</span>
                      <input type="text" id="search">
                      <input type="submit" class="btn">
                    </label>
                  </form>
                </div>
                <span>待查问题表：</span>
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>问题号</th>
                      <th>产品名</th>
                      <th>问题描述</th>
                      <th>访问次数</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
						$sql = "select * from questions,product where PEID={$_SESSION['EID']} and QPID=PID and QUnanswerable='1'  order by QID asc";
						$result = $link->query($sql);
						while($row = $result->fetch_assoc())
						{
							echo "<tr>";
								echo "<td>{$row['QID']}</td>";
								echo "<td>{$row['PName']}</td>";
								echo "<td>{$row['QTitle']}</td>";
								echo "<td>{$row['QVisitTime']}</td>";
								echo "<td class='table-ope-icon'>
										<a href='question_list.php?QID={$row['QID']}' title='展示问题'><span class='glyphicon glyphicon-search'></span></a>
										<a href='question_modify.php?QID={$row['QID']}' title='修改问题'><span class='glyphicon glyphicon-wrench'></span></a>
										<a href='javascript:deleteQuestions({$row['QID']})' title='删除问题'><span class='glyphicon glyphicon-trash'></span></a>
									 </td>";
							echo "</tr>";
						}
					?>
                  </tbody>
                </table>
                <br>
                <span>非待查问题表：</span>
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>问题号</th>
                      <th>产品名</th>
                      <th>问题描述</th>
                      <th>访问次数</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
						$sql = "select * from questions,product where PEID={$_SESSION['EID']} and QPID=PID and QUnanswerable='0'  order by QID asc";
						$result = $link->query($sql);
						while($row = $result->fetch_assoc()) {
							echo "<tr>";
								echo "<td>{$row['QID']}</td>";
								echo "<td>{$row['PName']}</td>";
								echo "<td>{$row['QTitle']}</td>";
								echo "<td>{$row['QVisitTime']}</td>";
								echo "<td class='table-ope-icon'>
										<a href='question_list.php?QID={$row['QID']}' title='展示问题'><span class='glyphicon glyphicon-search'></span></a>
										<a href='question_modify.php?QID={$row['QID']}' title='修改问题'><span class='glyphicon glyphicon-wrench'></span></a>
										<a href='javascript:deleteQuestions({$row['QID']})' title='删除问题'><span class='glyphicon glyphicon-trash'></span></a>
									 </td>";
							echo "</tr>";
						}
						$link->close();
					?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>