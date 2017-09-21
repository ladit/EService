<!DOCTYPE HTML>

<?php 
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
  
  $sql = "select * from `customer-service` where CSID={$_GET['CSID']}";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑客服</title>
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
          <li class="active"><a href="cs_manage.php"><i class="glyphicon glyphicon-cog"></i><span>客服管理</span></a></li>
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
          <div class="panel-heading"><a href="console.php">主页</a> > <a href="cs_manage.php">客服管理</a> > 编辑客服</div>
          <div class="panel-body">
            <div class="cs-list cs-list-inside">
              <div class="cs-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>编辑客服</span>
              </div>
              <div class="cs-list-body">
                <div class="cs-add">
                  <form id="form" action="../functions/action.php?action=updatecustomerservice" method="POST" >
                    <div class="form-group">
                      <label for="cs-id">客服号</label>
                      <input type="hidden" class="form-control" id="CSID" name="CSID" value="<?php echo $row['CSID'] ?>">
					            <br>
                      <?php echo $row['CSID']; ?>
                    </div>
                    <div class="form-group">
                      <label for="cs-name">客服名</label>
                      <input type="text" class="form-control" id="CSName" name="CSName" value="<?php echo $row['CSName'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="cs-pass">密码</label>
                      <input type="text" class="form-control" id="CSPassword" name="CSPassword" value="<?php echo $row['CSPassword'] ?>">
                    </div>
                    <div class="form-group">
                      <label for="">产品号</label>
					            <select class="form-control" id="PID" name="PID">
                        <?php
                          $sql1 = "select * from product where PEID={$_SESSION['EID']} order by PID asc";
                          $result1 = $link->query($sql1);
                          while($row1 = $result1->fetch_assoc())
                          {
                            if($row1['PID']==$row['CSPID'])
                              echo "<option selected='{$row1['PID']}'>{$row1['PID']}&nbsp;  &nbsp;  &nbsp;  {$row1['PName']}</option>";
                            else
                              echo "<option value='{$row1['PID']}'>{$row1['PID']}&nbsp;  &nbsp;  &nbsp;  {$row1['PName']}</option>";
                          }
                        ?>
                      </select>
                    </div>
                    <input type="submit" class="btn form-button" value="确定">
                    <input type="reset" class="btn form-button" value="重置">
                    <button type="button" class="btn" onclick="window.location.href='cs_manage.php'">返回</button>
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