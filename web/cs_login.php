<!DOCTYPE HTML>

<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>客服登录</title>
		<?php require __DIR__ . '/functions/header.php'; ?>
		<link href="assets/css/index-common.css" rel="stylesheet">
	</head>
	<body>
		<div class="main-container">
			<div class="main-cover">
				<div class="main-left">
					<h1>EService</h1>
					<p>随着大数据与云服务的技术发展，智能客服成为了自然而然的选择，使用智能客服相对⼈⼯客服有以下优点：成本低，只需要建立企业产品知识库，而无需建立过多的人工团队；效率高，智能客服可以实现全天候24小时响应，对问题进自动归类动归类，有利于大数据挖掘；服务规范，通过数据分析，可以将常见问题整理成规范的问题解答。</p>
				</div>
				<div class="main-right">
					<h3 class="form-title text-center">EService</h3>
					<form class="form-header">
						<div class="form-group">
							<input class="form-control input-lg" name="inputAccount" id="inputAccount" type="text" placeholder="账号">
						</div>
						<div class="form-group">
							<input class="form-control input-lg" name="inputPassword" id="inputPassword" type="password" placeholder="密码">
						</div>
						<div class="form-group">
							<input class="form-control input-lg" name="inputCaptcha" id="inputCaptcha" type="text" placeholder="验证码">
							<div class="flex">
								<img src="functions/generate_captcha.php" id="CaptchaImage" onclick = "changeCaptcha()" />
								<p align="center" id="error" style="color:red"></p>
							</div>
						</div>
						<div class="form-group last">
							<input type="button" class="btn btn-warning btn-block btn-lg" value="登录" onclick="login()">
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
		function changeCaptcha() {
			document.getElementById('CaptchaImage').src='functions/generate_captcha.php?tm='+Math.random();
		}
		
		function login() {
			var inputAccount = document.getElementById('inputAccount').value;
			var inputPassword = document.getElementById('inputPassword').value;
			var inputCaptcha = document.getElementById('inputCaptcha').value;
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'functions/action.php?action=CSLogin', true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send('inputAccount='+inputAccount+'&inputPassword='+inputPassword+'&inputCaptcha='+inputCaptcha);

			xhr.onreadystatechange = function() {
				if(xhr.readyState==4 && xhr.status==200) {
					if(xhr.responseText == "success") {
						window.location.href="chat_cs.php";
					}
					else {
						document.getElementById("error").innerHTML=xhr.responseText;
					}
				}
			}
		}
	</script>
</html>
