<!DOCTYPE HTML>

<?php 
  session_start();
  require_once "../functions/enterprise_authentication.php";
  require_once "../functions/connect_database.php";
  
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
    <title>客服编辑界面</title>
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
			主页 > <a href="co-manage-cs.php">客服库</a> > 客服编辑
		  </div>
          <div class="panel-body">
            <div class="cs-list cs-list-inside">
              <div class="cs-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>客服编辑</span>
              </div>
              <div class="cs-list-body">
                <div class="cs-add">
                  <form id="form" action="../functions/action.php?action=updatecustomerservice" method="POST" >
                    <div class="form-group">
                      <label for="cs-id">客服号</label>
                      <input type="hidden" class="form-control" id="CSID" name="CSID" value="<?php echo $row['CSID'] ?>">
					  <br><?php echo $row['CSID']; ?>
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
                    
                    <input type="submit" class="btn form-button" value="编辑">
                    <input type="reset" class="btn form-button" value="恢复原值">
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