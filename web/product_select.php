<!DOCTYPE HTML>

<?php
  session_start();
  require_once __DIR__ . '/functions/connect_database.php';
  function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $productQuery = "SELECT * FROM product ORDER BY PEID;";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputProductName = testInput($_POST["inputProductName"]);
    if (!empty($inputProductName)) {
      $productQuery = "SELECT * FROM product WHERE PName LIKE '%".$inputProductName."%' ORDER BY PEID;";
    }
  }
?>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>产品选择</title>
    <?php require __DIR__ . '/functions/header.php'; ?>
    <link href="assets/css/product-select.css" rel="stylesheet">
  </head>
  <body>
    <div class="topbar">
      <p>请选择您的产品</p>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <div class="form-group">
          <label for="inputProductName">输入产品名</label>
          <input type="text" class="form-control" name="inputProductName" id="inputProductName">
        </div>
        <div class="form-group">
          <button type="submit" class="btn">搜索</button>
        </div>
      </form>
    </div>
    <div class="main-container" id="main-container">
      <div class="mid main-child">
        <?php
          $productQueryResultset = $link->query($productQuery);
          if ($productQueryResultset->num_rows):
            $product_quantity = $productQueryResultset->num_rows;
            for ($i = 1; $i <= $product_quantity; $i++):
              
              $productQueryResult = $productQueryResultset->fetch_assoc();
              if ($i % 3 == 1):
              $product_id = $productQueryResult["PID"];
              $product_name = $productQueryResult["PName"];
              $product_image = $productQueryResult["PImage"];
              $productKnowledgeIndexQuery = "SELECT KID FROM knowledge WHERE KPID = '".$product_id."' AND KIndex = '1';";
              $productKnowledgeIndexQueryResultset = $link->query($productKnowledgeIndexQuery);
              if ($productKnowledgeIndexQueryResultset->num_rows) {
                $productKnowledgeIndexQueryResult = $productKnowledgeIndexQueryResultset->fetch_assoc();
                $product_knowledge_index_id = $productKnowledgeIndexQueryResult["KID"];
                $productKnowledgeIndexQueryResultset->close();
              }
        ?>
        <div class="box">
          <img src="functions/action.php?action=showProductImage&PID=<?= $product_id ?>" width="400" height="220">
          <div class="box-hover">
            <div class="name">
              <p><?= $product_name ?></p>
            </div>
             <div class="actions">
              <a href="" onclick="gotoIntelChat('<?= $product_id ?>');">智能客服</a>
              <?php
                if (isset($product_knowledge_index_id)) {
                  echo '<a href="knowledge.php?pid='.$product_id.'&kid='.$product_knowledge_index_id.'">知识库</a>';
                }
              ?>
            </div>
          </div>
        </div>
          <?php
            unset($product_knowledge_index_id);
            endif;
            endfor;
            $productQueryResultset->close();
            endif;
          ?>
      </div>
      <div class="mid main-child">
        <?php
          $productQueryResultset = $link->query($productQuery);
          if ($productQueryResultset->num_rows):
            $product_quantity = $productQueryResultset->num_rows;
            for ($i = 1; $i <= $product_quantity; $i++):
              
              $productQueryResult = $productQueryResultset->fetch_assoc();
              if ($i % 3 == 2):
              $product_id = $productQueryResult["PID"];
              $product_name = $productQueryResult["PName"];
              $product_image = $productQueryResult["PImage"];
              $productKnowledgeIndexQuery = "SELECT KID FROM knowledge WHERE KPID = '".$product_id."' AND KIndex = '1';";
              $productKnowledgeIndexQueryResultset = $link->query($productKnowledgeIndexQuery);
              if ($productKnowledgeIndexQueryResultset->num_rows) {
                $productKnowledgeIndexQueryResult = $productKnowledgeIndexQueryResultset->fetch_assoc();
                $product_knowledge_index_id = $productKnowledgeIndexQueryResult["KID"];
                $productKnowledgeIndexQueryResultset->close();
              }
        ?>
        <div class="box">
          <img src="functions/action.php?action=showProductImage&PID=<?= $product_id ?>" width="400" height="220">
          <div class="box-hover">
            <div class="name">
              <p><?= $product_name ?></p>
            </div>
             <div class="actions">
              <a href="" onclick="gotoIntelChat('<?= $product_id ?>');">智能客服</a>
              <?php
                if (isset($product_knowledge_index_id)) {
                  echo '<a href="knowledge.php?pid='.$product_id.'&kid='.$product_knowledge_index_id.'">知识库</a>';
                }
              ?>
            </div> 
          </div>
        </div>
          <?php
            unset($product_knowledge_index_id);
            endif;
            endfor;
            $productQueryResultset->close();
            endif;
          ?>
      </div>
      <div class="mid main-child">
        <?php
          $productQueryResultset = $link->query($productQuery);
          if ($productQueryResultset->num_rows):
            $product_quantity = $productQueryResultset->num_rows;
            for ($i = 1; $i <= $product_quantity; $i++):
              
              $productQueryResult = $productQueryResultset->fetch_assoc();
              if ($i % 3 == 0):
              $product_id = $productQueryResult["PID"];
              $product_name = $productQueryResult["PName"];
              $product_image = $productQueryResult["PImage"];
              $productKnowledgeIndexQuery = "SELECT KID FROM knowledge WHERE KPID = '".$product_id."' AND KIndex = '1';";
              $productKnowledgeIndexQueryResultset = $link->query($productKnowledgeIndexQuery);
              if ($productKnowledgeIndexQueryResultset->num_rows) {
                $productKnowledgeIndexQueryResult = $productKnowledgeIndexQueryResultset->fetch_assoc();
                $product_knowledge_index_id = $productKnowledgeIndexQueryResult["KID"];
                $productKnowledgeIndexQueryResultset->close();
              }
        ?>
        <div class="box">
          <img src="functions/action.php?action=showProductImage&PID=<?= $product_id ?>" width="400" height="220">
          <div class="box-hover">
            <div class="name">
              <p><?= $product_name ?></p>
            </div>
             <div class="actions">
              <a href="" onclick="gotoIntelChat('<?= $product_id ?>');">智能客服</a>
              <?php
                if (isset($product_knowledge_index_id)) {
                  echo '<a href="knowledge.php?pid='.$product_id.'&kid='.$product_knowledge_index_id.'">知识库</a>';
                }
              ?>
            </div> 
          </div>
        </div>
          <?php
            unset($product_knowledge_index_id);
            endif;
            endfor;
            $productQueryResultset->close();
            endif;
          ?>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    function gotoIntelChat(product_id) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'functions/action.php?action=setProductID', true);
      xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhr.send('PID='+product_id);

      xhr.onreadystatechange = function()
      {
        if(xhr.readyState==4 && xhr.status==200)
        {
          if(xhr.responseText == "success")
          {
            window.location.href="chat_intel.php?pid="+product_id;
          }
          else {
            alert("内部错误！");
          }
        }
      }
    }
  </script>
</html>