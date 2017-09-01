<!DOCTYPE HTML>

<?php
  session_start();
  require_once "../functions/enterprise_authentication.php";
  require_once "../functions/connect_database.php";

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
    <title>客服信息界面</title>
    <link href="../css/co-manage.css" rel="stylesheet">
    <?php require_once "../functions/co-header.php"; ?>
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
          <li class="active"><a href="co-manage-cs.php"><i class="glyphicon glyphicon-cog"></i><span>客服设置</span></a></li>
          <li><a href="co-manage-kno.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库</span></a></li>
          <li><a href="co-manage-que.php"><i class="glyphicon glyphicon-question-sign"></i><span>问题库</span></a></li>
        </ul>
      </div>
      <div class="container-right">
        <div class="panel panel-default">
          <div class="panel-heading">
			主页 > <a href="co-manage-cs.php">客服库</a> > 客服信息
		  </div>
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
                  <button type="buttom" class="btn" onclick=location.href="co-manage-cs.php">返回</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>