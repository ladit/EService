<!DOCTYPE HTML>

<?php 
  session_start();
  require_once "../functions/enterprise_authentication.php";
  require_once "../functions/connect_database.php";

  $sql = "SHOW TABLE STATUS LIKE 'product'";
  $result = $link->query($sql);
  $row = $result->fetch_assoc();
?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>产品添加界面</title>
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
          <li class="active"><a href="co-manage-pro.php"><i class="glyphicon glyphicon-search"></i><span>产品管理</span></a></li>
          <li><a href="co-manage-cs.php"><i class="glyphicon glyphicon-cog"></i><span>客服设置</span></a></li>
          <li><a href="co-manage-kno.php"><i class="glyphicon glyphicon-briefcase"></i><span>知识库</span></a></li>
          <li><a href="co-manage-que.php"><i class="glyphicon glyphicon-question-sign"></i><span>问题库</span></a></li>
        </ul>
      </div>
      <div class="container-right">
        <div class="panel panel-default">
          <div class="panel-heading">
			主页 > <a href="co-manage-pro.php">产品库</a> > 产品添加
		  </div>
          <div class="panel-body">
            <div class="pro-list">
              <div class="pro-list-heading">
                <span class="glyphicon glyphicon-user"></span>
                <span class="break"></span>
                <span>产品添加界面</span>               
              </div>
              <div class="pro-list-body">
                <div class="pro-add">
                  <form id="form" action="../functions/action.php?action=addProduct" method="POST" enctype="multipart/form-data">
                    <div class="pro-add-left">
                      <div class="form-group">
                        <label for="pro-id">产品id</label>
                        <input type="hidden" class="form-control" id="PID" name="PID" value="<?php echo $row['Auto_increment'];?>"  >
						<br /><?php echo $row['Auto_increment'];?>
                      </div>
                      <div class="form-group">
                        <label for="pro-name">产品名称</label>
                        <input type="text" class="form-control" id="PName" name="PName">
                      </div>
                      <div class="form-group">
                        <label for="pro-intro">产品介绍</label>
                        <textarea type="text" class="form-control" id="PIntroduction"  name="PIntroduction" rows="10"></textarea>
                      </div>
                      <input type="submit" class="btn form-button" value="添加">
                    </div>
                    <div class="pro-add-right">
                      <div class="form-group">
                        <label for="PImage">产品图片</label>
                        <input type="file" id="PImage"  name="PImage" >
						<img  id="pro-image-place" src=""    />
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      document.getElementById('PImage').addEventListener('change', function () {
        var res = document.getElementById('pro-image-place')
        var file = this.files[0]
        var reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = function () {
          res.src = this.result
        }
      })
    </script>
  </body>
</html>