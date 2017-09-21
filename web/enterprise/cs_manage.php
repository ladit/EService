<!DOCTYPE HTML>

<?php 
  session_start();
  require_once __DIR__ . '/../functions/enterprise_authentication.php';
  require_once __DIR__ . '/../functions/connect_database.php';
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>客服管理</title>
    <link href="../assets/css/enterprise-common.css" rel="stylesheet">
    <?php require __DIR__ . '/../functions/header.php'; ?>
    <script>
      function deleteCustomerService(CSID) {
        if(confirm("确定要删除吗？")) {
          window.location="../functions/action.php?action=deletecustomerservice&CSID="+CSID;
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
        <div class="panel-heading"><a href="console.php">主页</a> > 客服管理</div>
          <div class="panel-body">
            <div class="cs-list">
              <div class="cs-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>客服列表</span>
              </div>
              <div class="cs-list-body">
                <div class="search-button-flex">
                  <button type="button" class="btn" onclick=location.href="cs_add.php">添加客服</button>
                  <form action="">
                    <label for="search">搜索</label>
                    <input type="text" id="search">
                    <input type="submit" class="btn">
                  </form>
                </div>
                 <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>客服号</th>
                      <th>产品名</th>
                      <th>登录名</th>
					            <th>满意度</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php	
                      $sql = "select * from `customer-service`,product where CSPID=PID and CSEID={$_SESSION['EID']} order by CSID asc";
                      $result = $link->query($sql);
                      while($row = $result->fetch_assoc())
                      {
                        $CSIDid=$row['CSID'];
                        
                        $sql_countCCSID = "select * from cases where CCSID={$CSIDid}";
                        $result_countCCSID = $link->query($sql_countCCSID);
                        $row_countCCSID = $result_countCCSID->num_rows;
                        
                        $sql_countSatisfied = "select * from cases where CCSID={$CSIDid} and CSatisfied='1'  ";
                        $result_countSatisfied = $link->query($sql_countSatisfied);
                        $row_countSatisfied = $result_countSatisfied->num_rows;
                        
                        echo "<tr>";
                          echo "<td>{$row['CSID']}</td>";
                          echo "<td>{$row['PName']}</td>";
                          echo "<td>{$row['CSName']}</td>";
                          echo "<td>{$row_countSatisfied}/{$row_countCCSID}</td>";
                          echo "<td class='table-ope-icon'>
                                  <a href='cs_list.php?CSID={$row['CSID']}' title='展示客服'><span class='glyphicon glyphicon-search'></span></a>
                                  <a href='cs_modify.php?CSID={$row['CSID']}' title='修改客服'><span class='glyphicon glyphicon-wrench'></span></a>
                                  <a href='javascript:deleteCustomerService({$row['CSID']})' title='删除客服'><span class='glyphicon glyphicon-trash'></span></a>
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