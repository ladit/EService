<!DOCTYPE HTML>

<?php
  session_start();
  require_once "../functions/enterprise_authentication.php";
  require_once "../functions/connect_database.php";
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>知识库管理</title>
    <link href="../css/co-manage.css" rel="stylesheet">
    <?php require_once "../functions/co-header.php"; ?>
    <script>
			function deleteknowledge(KID)
			{
				if(confirm("确定要删除吗？"))
				{
					window.location="../functions/action.php?action=deleteknowledge&KID="+KID;
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
          <li><a href="co-manage-pro.php"><i class="glyphicon glyphicon-search"></i><span>产品管理</span></a></li>
          <li><a href="co-manage-cs.php"><i class="glyphicon glyphicon-cog"></i><span>客服设置</span></a></li>
          <li class="active"><a href="co-manage-kno.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库</span></a></li>
          <li><a href="co-manage-que.php"><i class="glyphicon glyphicon-question-sign"></i><span>问题库</span></a></li>
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
										<a href='co-manage-kno-show.php?KID={$row['KID']}'><span class='glyphicon glyphicon-search'></span></a>
										<a href='co-manage-kno-edit.php?KID={$row['KID']}'><span class='glyphicon glyphicon-wrench'></span></a>
										<a href='javascript:deleteknowledge({$row['KID']})'><span class='glyphicon glyphicon-trash'></span></a>
									 </td>";
							echo "</tr>";
							echo "</form>";
						}
						$link->close();
					?>
                  </tbody>
                </table>
                <button type="buttom" class="btn" onclick=location.href="co-manage-kno-add.php">添加</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>