if (typeof console === 'undefined') {
  this.console = { log: function (msg) {

  } }
}
// 如果浏览器不支持websocket，会使用这个flash自动模拟websocket协议，此过程对开发者透明
WEB_SOCKET_SWF_LOCATION = 'assets/swf/WebSocketMain.swf'
// 开启flash的websocket debug
WEB_SOCKET_DEBUG = true

var ws
var cs_connected = 0

// 连接服务端
function connect () {
  // 创建websocket
  ws = new WebSocket('ws://' + document.domain + ':7272')
  // 当socket连接打开时
  ws.onopen = onopen
  // 当有消息时根据消息类型显示不同信息
  ws.onmessage = onmessage
  ws.onclose = function () {
    console.log('连接关闭，定时重连')
    connect()
  }
  ws.onerror = function () {
    console.log('出现错误')
  }
}

// 连接建立时发送登录信息
function onopen () {
  var login_data = '{"type":"2","product_id":"' + product_id + '","case_id":"' + case_id + '"}'
  console.log('websocket握手成功，发送登录数据:' + login_data)
  ws.send(login_data)
  var content = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/cs_head_image.jpg" alt=""></div><p class="speech-item-content">' + '您好，正在为您转接人工客服，请稍后...' + '</p></div>'
  $('.chatting-content').append(content)
  $('.chatting-content').scrollTop(largeNumber)
  $('#sendArea').val('')
  $('#sendArea').focus()
}

var largeNumber = 999999999

// 服务端发来消息时
function onmessage (e) {
  console.log(e.data)
  var data = eval ('(' + e.data + ')') // 浏览器如果说JSON.parse用不了的话改成 var data = e.data
  var message = ''
  switch (data['type']) {
  // 服务端ping客户端
    case 'ping':
      ws.send('{"type":"0","product_id":"' + product_id + '"}')
      break

    // 当前无客服在线
    // message格式: {"type":"no_cs_online","wait_time":xxx}
    case 'no_cs_online':
    // 在聊天框显示当前无客服在线，自动等待
      var content = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/cs_head_image.jpg" alt=""></div><p class="speech-item-content">' + '您好，当前无人工客服在线，已为您自动等待，等待时间：' + data['wait_time'] + '秒。</p></div>'
      $('.chatting-content').append(content)
      $('.chatting-content').scrollTop(largeNumber)
      break

    // 所有客服忙，进行计时
    // message格式: {"type":"cant_serve_busy","wait_time":xxx}
    case 'cant_serve_busy':
    // 当前所有客服忙，自动排队计时
      var content = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/cs_head_image.jpg" alt=""></div><p class="speech-item-content">' + '当前客服繁忙，预计等待时间为：' + data['wait_time'] + '秒。</p></div>'
      $('.chatting-content').append(content)
      $('.chatting-content').scrollTop(largeNumber)
      break

    // 计时超时无客服在线且空闲，无法服务
    // message格式: {"type":"cant_serve_overtime"}
    case 'cant_serve_overtime':
    // 弹窗告知无法服务，关闭网页，要求用户稍后再来
      alert('抱歉，计时超时，客服暂时无法服务，即将转向首页，请稍后再试')
      location.href = 'index.php'
      break

    // 客服连入
    // message格式: {"type":"cs_connected","cs_id":xxx,"time":"xxx"}
    case 'cs_connected':
    // 聊天框提示用户客服连入，准备开始聊天
      message = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/cs_head_image.jpg" alt=""></div><p class="speech-item-content">' + '您好，客服工号' + data['cs_id'] + '为您服务！' + '</p></div>'
      $('.chatting-content').append(message)
      $('.chatting-content').scrollTop(largeNumber)
      $('#sendArea').val('')
      $('#sendArea').focus()
      cs_connected = 1
      break

    // 客服发来消息
    // message格式: {"type":"say","content":xxx,"time":"xxx"}
    case 'say':
      // 把消息显示在聊天框中
      message = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/cs_head_image.jpg" alt=""></div><p class="speech-item-content">' + data['content'] + '</p></div>'
      $('.chatting-content').append(message)
      $('.chatting-content').scrollTop(largeNumber) 
      $('#sendArea').val('')
      $('#sendArea').focus()
      break

    // 客服中途退出
    // message格式: {"type":"cs_logout"}
    case 'cs_logout':
      // 提示用户客服突然退出，关闭网页
      alert('抱歉，客服离线，即将转向首页')
      location.href = 'index.php'
      break
  }
}
// 发消息
function sendMessage () {
  if (ws.readyState != 1 && cs_connected) {
    return;
  }
  var input = $('#sendArea').val()
  ws.send(JSON.stringify({
    type: '4',
    content: input
  }))
  var message = '<div class="speech-item-rig"><div class="speech-item-head-rig">' +
  '<img src="assets/images/user_head_image.jpg" alt=""></div><p class="speech-item-content">' + $('#sendArea').val() + '</p></div>'
  $('.chatting-content').append(message)
  $('.chatting-content').scrollTop(largeNumber)
  $('#sendArea').val('')
  $('#sendArea').focus()
}

function sendQuestion (question) {
  if (ws.readyState != 1 && cs_connected) {
    return;
  }
  ws.send(JSON.stringify({
    type: '4',
    content: question
  }))
  var message = '<div class="speech-item-rig"><div class="speech-item-head-rig">' +
  '<img src="assets/images/user_head_image.jpg" alt=""></div><p class="speech-item-content">' + question + '</p></div>'
  $('.chatting-content').append(message)
  $('.chatting-content').scrollTop(largeNumber)
  $('#sendArea').val('')
  $('#sendArea').focus()
}

function appraise (flag) {
  if (ws.readyState != 1 && cs_connected) {
    return;
  }
  ws.send(JSON.stringify({
    type: '6',
    isSatisfied: flag
  }))
}