<!DOCTYPE HTML>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EService</title>
    <?php require __DIR__ . '/functions/header.php'; ?>
	  <link href="assets/css/index-common.css" rel="stylesheet">
	  <link href="assets/css/css-index.css" rel="stylesheet">
  </head>
  <body>
    <div class="main-container">
      <div class="main-cover">
        <div class="main-left">
          <h1>EService</h1>
          <p>随着大数据与云服务的技术发展，智能客服成为了自然而然的选择，使用智能客服相对人工客服有以下优点：成本低，只需要建立企业产品知识库，而无需建立过多的人工团队；效率高，智能客服可以实现全天候24小时响应，对问题进自动归类动归类，有利于大数据挖掘；服务规范，通过数据分析，可以将常见问题整理成规范的问题解答。</p>
          <button class="btn buy">点此订购该服务</button>
        </div>
        <div class="main-right">
          <h3 class="form-title text-center">EService</h3>
          <form class="form-header">
            <div class="form-group">
              <input type="button" class="btn btn-warning btn-block btn-lg" value="企业登录" onclick="window.location='enterprise_login.php'">
            </div>
            <div class="form-group">
              <input type="button" class="btn btn-warning btn-block btn-lg" value="企业注册" onclick="window.location='register.php'">
            </div>
            <div class="form-group">
              <input type="button" class="btn btn-warning btn-block btn-lg" value="客服登录" onclick="window.location='cs_login.php'">
            </div>
            <p class="privacy text-center">使用本产品将默认您同意<a href="">服务条款</a>.</p>
          </form>
        </div>
      </div>
    </div>
    <div class="bot-container">
      <nav class="navbar" id="navbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#chat">聊天室界面</a></li>
          <li><a href="#kno">知识库界面</a></li>
          <li><a href="#co">企业管理界面</a></li>
        </ul>
      </nav>
      <div class="bot-item" id="chat">
        <div class="bot-item-intro">
          <img src="assets/images/chat_page.png" alt="">
          <p>智能客服部分采用 Coreseek 及其自带的 Sphinx 对用户输入的问题进行分词与全文搜索，对于数据库中不存在的问题，自动记录以待企业之后查阅；人工客服使用了 Workerman 作为底层框架，实现了多客服、多用户进行聊天，客服忙自动等待等功能。</p>
        </div>
      </div>
      <div class="bot-item" id="kno">
        <div class="bot-item-intro">
          <p>知识库页面允许用户查看产品的知识文档，并可统计浏览与是否有帮助。</p>
          <img src="assets/images/knowledge_page.png" alt="">
        </div>
      </div>
      <div class="bot-item" id="co">
        <div class="bot-item-intro">
          <img src="assets/images/enterprise_page.png" alt="">
          <p>企业管理部分功能丰富，可以管理客服、产品、问题、知识库等，自动收集分析了产品问题、知识库缺陷等以供查看。</p>
        </div>
      </div>
    </div>
    <div class="last-container">
      <div class="main-cover">
        <h1>想要了解我们更多的信息吗？</h1>
        <button class="btn buy">点此订购该服务</button>
      </div>
    </div>
    <script type="text/javascript">
      window.addEventListener('scroll', function () {
        var topScroll = window.scrollY
        console.log(topScroll)
        var nav = document.getElementById('navbar')
        if (topScroll > 700) {
          nav.style.position = 'fixed'
          nav.style.top = '0'
          nav.style.right = '0'
          nav.style.zIndex = '9999'
        } else {
          nav.style.position = 'static'
        }
      })
    </script>
  </body>
</html>