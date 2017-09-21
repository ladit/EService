<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>知识库管理</title>
    <link href="../assets/css/enterprise-common.css" rel="stylesheet">
    <?php require __DIR__ . '/../functions/header.php'; ?>    <script>
			function deleteKnowledge(KID)	{
				if(confirm("确定要删除吗？")) {
					window.location="../functions/action.php?action=deleteKnowledge&KID="+KID;
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
          <div class="panel-heading">主页 > 知识库</div>
          <div class="panel-body">
            <div class="kno-list">
              <div class="kno-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>知识库列表</span> 
              </div>
              <div class="kno-list-body">
                <div class="search-button-flex">
                  <button type="button" class="btn" onclick=location.href="knowledge_add.php">添加知识</button>
                  <form action="">
                    <label for="search">
                      <span>search</span>
                      <input type="text" id="search">
                      <input type="submit" class="btn">
                    </label>
                  </form>
                </div>
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>知识号</th>
                      <th>产品名</th>
                      <th>分类</th>    
                      <th>知识标题</th>
                      <th>访问次数</th> 
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
						$sql = "select * from knowledge,product where PEID={$_SESSION['EID']} and KPID=PID  order by KPID asc";
						$result = $link->query($sql);
						while($row = $result->fetch_assoc())
						{    
							echo "<tr>";
								echo "<td>{$row['KID']}</td>";
								echo "<td>{$row['PName']}</td>";
								echo "<td>{$row['KClass']}</td>";
								echo "<td>{$row['KTitle']}</td>";
								echo "<td>{$row['KVisitTime']}</td>";
								echo "<td class='table-ope-icon'>
										<a href='knowledge_list.php?KID={$row['KID']}' title='展示知识'><span class='glyphicon glyphicon-search'></span></a>
										<a href='knowledge_modify.php?KID={$row['KID']}' title='修改知识'><span class='glyphicon glyphicon-wrench'></span></a>
										<a href='javascript:deleteKnowledge({$row['KID']})' title='删除知识'><span class='glyphicon glyphicon-trash'></span></a>
									 </td>";
							echo "</tr>";
							echo "</form>";
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