function classToggle() {
  $(this).next().slideToggle();
  $(this).parent().prevAll().children('ul').slideUp();
  $(this).parent().nextAll().children('ul').slideUp();
  return false;
}

function appendNavClass(KClass) {
  if (KClass != "root") {
    var classToAppend = '<li>' +
      '<a href="javascript:void(0);" onclick="classToggle()"><i class="glyphicon glyphicon-search"></i><span>'+KClass+'</span></a>'+
      '<ul class="nav" id="knowledge_class_'+ KClass +'"></ul>' +
    '</li>';
    $('#nav-stacked').append(classToAppend);
  }
}

function appendNavKnowledge(KID, KClass, KTitle) {
  if (KClass == "root") {
    $('#nav-stacked').append('<li><a href="knowledge.php?pid='+product_id+'&kid='+KID+'"><p>'+KTitle+'</p></a></li>');
  }
  else {
    $('#knowledge_class_'+KClass).append('<li><a href="knowledge.php?pid='+product_id+'&kid='+KID+'"><p>'+KTitle+'</p></a></li>');
  }
}

function appendKnowledge(KTitle, KDescription, KContent, KVisitTime, KUsefulTime, KUselessTime) {
  $('#panel-heading').text(KTitle);
  if (KDescription) {
    $('#KDescription').append(KDescription);
  }
  $('#KContent').append(KContent);
  $('#KVisitTime').text(Number(KVisitTime) + 1);
  var total_time = Number(KUsefulTime) + Number(KUselessTime);
  if (total_time != 0) {
    $('#useful-useless-percent').text(KUsefulTime + "/" + total_time + "人认为有帮助");
  }
}

function noKnowledge() {
  $('#nav-stacked').empty();
  $('#panel-heading').text("无知识！");
  $('#panel-body').empty();
  $('#panel-body').append('<p>此产品无任何知识库！</p>');
}

function useful() {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'functions/action.php?action=addKnowledgeUsefulTime', true);
  xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xhr.send('KID='+knowledge_id);

  xhr.onreadystatechange = function()
  {
    if(xhr.readyState==4 && xhr.status==200)
    {
      if(xhr.responseText == "success")
      {
        $('#useful-btn').attr("onclick","");
      }
      else {
        alert("内部错误！");
      }
    }
  }
}

function useless() {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'functions/action.php?action=addKnowledgeUselessTime', true);
  xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xhr.send('KID='+knowledge_id);

  xhr.onreadystatechange = function()
  {
    if(xhr.readyState==4 && xhr.status==200)
    {
      if(xhr.responseText == "success")
      {
        $('#useless-btn').attr("onclick","");
      }
      else {
        alert("内部错误！");
      }
    }
  }
}

$('ul.nav.nav-stacked li ul').hide();