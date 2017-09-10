<!DOCTYPE html>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>企业注册</title>
    <?php require __DIR__ . '/functions/header.php'; ?>
    <link href="assets/css/index-common.css" rel="stylesheet">
  </head>
  <body>
    <div class="main-container">
      <div class="main-cover-mid">
        <div class="main-mid">
          <h3 class="form-title text-center">注册信息</h3>
          <form class="form-header" role="form">
            <div class="form-group">
              <input class="form-control input-lg" name="inputLoginName" id="inputLoginName" type="text" placeholder="登录名">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" name="inputPassword" id="inputPassword" type="password" placeholder="密码">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" name="inputPasswordAgain" id="inputPasswordAgain" type="password" placeholder="确认密码">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" name="inputName" id="inputName" type="text" placeholder="企业名称">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" name="inputLegalPerson" id="inputLegalPerson" type="text" placeholder="法人">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" name="inputLegalPersonID" id="inputLegalPersonID" type="text" placeholder="法人身份证号">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" name="inputCaptcha" id="inputCaptcha" type="text" placeholder="验证码">
              <img src="functions/generate_captcha.php" id="CaptchaImage" onclick="changeCaptcha()" />
            </div>
            <div>
              <p align="center" id="error" style="color:red"></p>
            </div>
            <div class="form-group last">
              <input type="button" class="btn btn-warning btn-block btn-lg" onclick="check()" value="注册">
            </div>
            <div class="form-group last">
              <input type="button" class="btn btn-warning btn-block btn-lg" onclick="window.location.href='index.php'" value="返回">
            </div>
            <p class="privacy text-center">使用本产品将默认您同意<a href="">服务条款</a>.</p>
          </form>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    function check() {
      var inputLoginName = document.getElementById('inputLoginName').value;
      var inputPassword = document.getElementById('inputPassword').value;
      var inputPasswordAgain = document.getElementById('inputPasswordAgain').value;
      var inputName = document.getElementById('inputName').value;
      var inputLegalPerson = document.getElementById('inputLegalPerson').value;
      var inputLegalPersonID = document.getElementById('inputLegalPersonID').value;
      var inputCaptcha = document.getElementById('inputCaptcha').value;

      if (inputPassword != inputPasswordAgain) {
        document.getElementById("error").innerHTML = "两次输入密码不一致";
      } else {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'functions/action.php?action=register', true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send('inputLoginName=' + inputLoginName + '&inputPassword=' + inputPassword + '&inputName=' + inputName +
          '&inputLegalPerson=' + inputLegalPerson + '&inputLegalPersonID=' + inputLegalPersonID + '&inputCaptcha=' +
          inputCaptcha);

        xhr.onreadystatechange = function () {
          if (xhr.responseText == "success") {
            alert("注册成功！");
            window.location.href = "enterprise_login.php";
          } else {
            document.getElementById("error").innerHTML = xhr.responseText;
          }
        }
      }
    }
  </script>
</html>