if (typeof console === 'undefined') {
  this.console = { log: function (msg) {

  } }
}
// 如果浏览器不支持websocket，会使用这个flash自动模拟websocket协议，此过程对开发者透明
WEB_SOCKET_SWF_LOCATION = 'assets/swf/WebSocketMain.swf'
// 开启flash的websocket debug
WEB_SOCKET_DEBUG = true

var ws

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
  var login_data = '{"type":"3","cs_id":"' + cs_id + '","product_id":"' + product_id + '"}'
  console.log('websocket握手成功，发送登录数据:' + login_data)
  ws.send(login_data)
  var content = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/user_head_image.jpg" alt=""></div><p class="speech-item-content">' + '您已登录，等待用户连入...' + '</p></div>'
  $('.chatting-content').append(content)
  $('.chatting-content').scrollTop(largeNumber)
  $('#sendArea').val('')
  $('#sendArea').focus()
}

var largeNumber = 999999999
var user_connected = 0

// 服务端发来消息时
function onmessage (e) {
  console.log(e.data)
  var data = eval ('(' + e.data + ')') // 浏览器如果说JSON.parse用不了的话改成 var data = e.data
  var message = ''
  switch (data['type']) {
  // 服务端ping客户端
    case 'ping':
      ws.send('{"type":"1"}')
      break

    // 用户连入
    // message格式: {"type":"user_connected","time":"xxx"}
    case 'user_connected':
    // 聊天框提示客服用户连入，准备开始聊天
      message = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/user_head_image.jpg" alt=""></div><p class="speech-item-content">' + '有用户连入！' + '</p></div>'
      $('.chatting-content').append(message)
      $('.chatting-content').scrollTop(largeNumber)
      $('#sendArea').val('')
      $('#sendArea').focus()
      user_connected = 1
      break

    // 用户发来消息
    // message格式: {"type":"say","content":xxx,"time":"xxx"}
    case 'say':
      // 把消息显示在聊天框中
      message = '<div class="speech-item-lef"><div class="speech-item-head-lef">' +
      '<img src="assets/images/user_head_image.jpg" alt=""></div><p class="speech-item-content">' + data['content'] + '</p></div>'
      $('.chatting-content').append(message)
      $('.chatting-content').scrollTop(largeNumber)
      $('#sendArea').val('')
      $('#sendArea').focus()
      break

    // 用户中途退出
    // message格式: {"type":"user_logout"}
    case 'user_logout':
      // 提示客服用户突然退出，记录主要问题
      $('.popup').show()
      break
  }
}
// 发消息
function sendMessage () {
  if (ws.readyState != 1 && user_connected) {
    return;
  }
  var input = $('#sendArea').val()
  ws.send(JSON.stringify({
    type: '4',
    content: input
  }))
  var message = '<div class="speech-item-rig"><div class="speech-item-head-rig">' +
  '<img src="assets/images/cs_head_image.jpg" alt=""></div><p class="speech-item-content">' + $('#sendArea').val() + '</p></div>'
  $('.chatting-content').append(message)
  $('.chatting-content').scrollTop(largeNumber)
  $('#sendArea').val('')
  $('#sendArea').focus()
}

$('#main-question-submit').click(function () {
  if (ws.readyState != 1 && user_connected) {
    return;
  }
  var input = $('#new-problem').val()
  $('#new-problem').val('')
  ws.send(JSON.stringify({
    type: '5',
    question: input
  }))
  $('.popup').hide()
})