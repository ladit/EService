<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>账单</title>
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
          <li class="active"><a href="#"><i class="glyphicon glyphicon-usd"></i><span>账单</span></a></li>
          <li><a href="enterprise_manage.php"><i class="glyphicon glyphicon-edit"></i><span>企业信息管理</span></a></li>
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
          <div class="panel-heading">主页 > 账单</div>
          <div class="panel-body">
            <div class="bill-list">
              <div class="bill-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>账单列表</span>               
              </div>
              <div class="bill-list-body">
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
					            <th>服务号</th>
                      <th>服务开始时间</th>
                      <th>服务结束时间</th>
                      <th>花费时长</th>
					            <th>收费</th>
                    </tr>
                  </thead>
                  <tbody>
						<?php
							$timeCount = 0;
							$chargeCOunt = 0;
							$sql = "select * from cases,`customer-service` where CSID=CCSID and CSEID={$_SESSION['EID']} order by CID asc";
							$result = $link->query($sql);
							while($row = $result->fetch_assoc())
							{
								echo "<tr>";
									echo "<td>{$row['CID']}</td>";
									echo "<td>{$row['CBeginTime']}</td>";
									echo "<td>{$row['CEndTime']}</td>";
									$timeCost=strtotime($row['CEndTime'])-strtotime($row['CBeginTime']);
									if ($timeCost > 0)
									{
										$charge = floor( $timeCost/60*0.2*100)/100;
										$timeCount += $timeCost;
										$chargeCOunt += $charge ;
										echo "<td>". floor( $timeCost/60 ) ."分". $timeCost%60 ."秒</td>";
										echo "<td>". $charge ."元</td>";
									}
									else
									{
										echo "<td>0</td>";
										echo "<td>0</td>";
									}
										
								echo "</tr>";
							}
							$link->close();
						?>
                  </tbody>
                </table>
				        <p> 收费规则：0.2元/分钟</p>
				        <p> 总计时长：<?php echo floor( $timeCost/60 ) ."分". $timeCost%60 ."秒"; ?> </p>
				        <p> 总计收费：<?php echo $chargeCOunt."元"; ?> </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>