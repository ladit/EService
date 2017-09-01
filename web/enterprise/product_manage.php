<!DOCTYPE HTML>

<?php
  session_start();
  require_once "../functions/enterprise_authentication.php";
  require_once "../functions/connect_database.php";
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>产品管理</title>
    <link href="../css/co-manage.css" rel="stylesheet">
    <?php require_once "../functions/co-header.php"; ?>
    <script>
			function deleteProduct(PID)
			{
				if(confirm("确定要删除吗？"))
				{
					window.location="../functions/action.php?action=deleteProduct&PID="+PID;
				}
			}
	  </script>
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
          <li><a href="co-manage-center.php"><i class="glyphicon glyphicon-list-alt"></i><span>总览</span></a></li>
          <li class="active"><a href="co-manage-pro.php"><i class="glyphicon glyphicon-search"></i><span>产品管理</span></a></li>
          <li><a href="co-manage-cs.php"><i class="glyphicon glyphicon-cog"></i><span>客服设置</span></a></li>
          <li><a href="co-manage-kno.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库</span></a></li>
          <li><a href="co-manage-que.php"><i class="glyphicon glyphicon-question-sign"></i><span>问题库</span></a></li>
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
                <form action="">
                  <label for="search">
                    <span>search</span>
                    <input type="text" id="search">
                    <input type="submit" class="btn">
                  </label>
                </form>
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
											<a href='co-manage-pro-show.php?PID={$row['PID']}'><span class='glyphicon glyphicon-search'></span></a>
											<a href='co-manage-pro-edit.php?PID={$row['PID']}'><span class='glyphicon glyphicon-wrench'></span></a>
											<a href='javascript:deleteProduct({$row['PID']})'><span class='glyphicon glyphicon-trash'></span></a>
                      <a href='http://qr.liantu.com/api.php?text=http://127.0.0.1:7777/chat_intel.php?pid={$row['PID']}' title='下载二维码' download><span class='glyphicon glyphicon-qrcode'></span></a>
										  </td>";
								echo "</tr>";
							}
							$link->close();
						?>
                  </tbody>
                </table>
                <button type="buttom" class="btn" onclick=location.href="co-manage-pro-add.php">添加</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>