<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>产品管理</title>
    <link href="../assets/css/enterprise-common.css" rel="stylesheet">
    <?php require __DIR__ . '/../functions/header.php'; ?>
    <script>
			function deleteProduct(PID) {
				if(confirm("确定要删除吗？")) {
					window.location="../functions/action.php?action=deleteProduct&PID="+PID;
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
          <div class="panel-heading">主页 > 产品库</div>
          <div class="panel-body">
            <div class="pro-list">
              <div class="pro-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>产品列表</span>               
              </div>
              <div class="pro-list-body">
                <div class="search-button-flex">
                  <button type="button" class="btn" onclick=location.href="product_add.php">添加产品</button>
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
                      <th>产品编号</th>
                      <th>产品名称</th>
                      <th>产品介绍</th>
					            <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
						<?php
							$sql = "select * from product where PEID={$_SESSION['EID']} order by PID asc";
							$result = $link->query($sql);
							while($row = $result->fetch_assoc())
							{
								echo "<tr>";
									echo "<td>{$row['PID']}</td>";
									echo "<td>{$row['PName']}</td>";
									echo "<td>{$row['PIntroduction']}</td>";
									echo "<td class='table-ope-icon'>
											<a href='product_list.php?PID={$row['PID']}' title='展示产品'><span class='glyphicon glyphicon-search'></span></a>
											<a href='product_modify.php?PID={$row['PID']}' title='修改产品'><span class='glyphicon glyphicon-wrench'></span></a>
											<a href='javascript:deleteProduct({$row['PID']})' title='删除产品'><span class='glyphicon glyphicon-trash'></span></a>
											<a href='http://qr.liantu.com/api.php?text=http://127.0.0.1:7777/chat_intel.php?pid={$row['PID']}' title='下载二维码' download><span class='glyphicon glyphicon-qrcode'></span></a>
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