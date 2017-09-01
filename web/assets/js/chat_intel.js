function showPopularQuestion(QID) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'functions/action.php?action=showPopularQuestion&QID='+QID, true);
    xhr.send();
    xhr.onreadystatechange=function()
    {
        if(xhr.readyState==4 && xhr.status==200)
        {
        document.getElementById("chatting").innerHTML=xhr.responseText;				
        }
    }
}

function searchQuestion() {
    var input = $('#sendArea').val();
    $('#sendArea').val('');
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'functions/search_question_answer.php?product_id='+product_id+'?input='+input, true);
    xhr.send();
    xhr.onreadystatechange=function()
    {
        if(xhr.readyState==4 && xhr.status==200)
        {
        document.getElementById("chatting").innerHTML=xhr.responseText;				
        }
    }
}

function useful(QID) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'functions/action.php?action=addQuestionUsefulTime&QID='+QID, true);
    xhr.send();
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

function useless(QID) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'functions/action.php?action=addQuestionUselessTime&QID='+QID, true);
    xhr.send();
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

window.onbeforeunload = function(){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'functions/action.php?action=intelCaseEnd&CID='+case_id, true);
    xhr.send();
};