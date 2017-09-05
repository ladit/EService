<?php
session_start();
require_once __DIR__ . '/connect_database.php';

date_default_timezone_set('Asia/Shanghai');

function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

switch ($_GET["action"])
{
	case 'register':  // 企业注册
		$inputCaptcha = testInput($_POST["inputCaptcha"]);
		$inputCaptcha = strtolower($inputCaptcha);
		if (empty($inputCaptcha))
		{
			echo "验证码不能为空！";
			break;
		}
		if (!preg_match("/^.{6,6}$/",$inputCaptcha) or $inputCaptcha != $_SESSION['captcha'])
		{
			echo "验证码错误！";
			break;
		}
		$inputLoginName = testInput($_POST["inputLoginName"]);
		$inputPassword = testInput($_POST["inputPassword"]);
		$inputName = testInput($_POST["inputName"]);
		$inputLegalPerson = testInput($_POST["inputLegalPerson"]);
		$inputLegalPersonID = testInput($_POST["inputLegalPersonID"]);
		if (empty($inputLoginName))
		{
			echo "登录名不能为空！";
			break;
		}
		if (empty($inputPassword))
		{
			echo "密码不能为空！";
			break;
		}
		if (empty($inputName))
		{
			echo "企业名称不能为空！";
			break;
		}
		if (empty($inputLegalPerson))
		{
			echo "法人不能为空！";
			break;
		}
		if (empty($inputLegalPersonID))
		{
			echo "法人身份证号不能为空！";
			break;
		}
		if (!preg_match('/[A-Za-z0-9_\-\x{4e00}-\x{9fa5}]{5,50}/u',$inputLoginName))
		{
			echo "登录名应为5到50位的字母、数字、中文、符号“_”、“-”组合！";
			break;
		}
		if (!preg_match("/^[A-Za-z0-9_\-]{5,100}$/",$inputPassword))
		{
			echo "密码应为5到100位的字母、数字、符号“_”、“-”组合！";
			break;
		}
		if (!preg_match("/[\x{4e00}-\x{9fa5}]{5,150}/u",$inputName))
		{
			echo $inputName."企业名称应为5到150位的中文！";
			break;
		}
		if (!preg_match("/[\x{4e00}-\x{9fa5}]{2,50}/u",$inputLegalPerson))
		{
			echo "法人应为2到50位的中文！";
			break;
		}
		if (!preg_match("/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/",$inputLegalPersonID))
		{
			echo "法人身份证号格式错误！";
			break;
		}
		$loginNameDuplicateQuery = "SELECT * FROM Enterprise WHERE ELoginName = '".$inputLoginName."';";
		$loginNameDuplicateQueryResultset = $link->query($loginNameDuplicateQuery);
		if ($loginNameDuplicateQueryResultset->num_rows) {
			$loginNameDuplicateQueryResultset->close();
			echo "登录名已被注册！";
			break;
		}
		$loginNameDuplicateQueryResultset->close();
		$registerQuery = "INSERT INTO Enterprise (ELoginName, EPassword, EName, ELegalPerson, ELegalPersonID) VALUES ('".$inputLoginName."', '".$inputPassword."', '".$inputName."', '".$inputLegalPerson."', '".$inputLegalPersonID."');";
		if ($link->query($registerQuery) === FALSE) {
			echo "服务器问题，注册失败！";
			break;
		}
		else {
			echo "success";
			break;
		}
	break;

	case "enterpriseLogin":  // 企业登录
		$inputCaptcha = testInput($_POST["inputCaptcha"]);
		$inputCaptcha = strtolower($inputCaptcha);
		if (empty($inputCaptcha))
		{
			echo "验证码不能为空！";
			break;
		}
		if (!preg_match("/^.{6,6}$/",$inputCaptcha) or $inputCaptcha != $_SESSION['captcha'])
		{
			echo "验证码错误！";
			break;
		}
		$inputAccount = testInput($_POST["inputAccount"]);
		$inputPassword = testInput($_POST["inputPassword"]);
		if (empty($inputAccount))
		{
			echo "账户不能为空！";
			break;
		}
		if (empty($inputPassword))
		{
			echo "密码不能为空！";
			break;
		}
		if (!preg_match('/[A-Za-z0-9_\-\x{4e00}-\x{9fa5}]{5,50}/u',$inputAccount))
		{
			echo "账户应为5到50位的字母、数字、中文、符号“_”、“-”组合！";
			break;
		}
		if (!preg_match("/^[A-Za-z0-9_\-]{5,100}$/",$inputPassword))
		{
			echo "密码应为5到100位的字母、数字、符号“_”、“-”组合！";
			break;
		}
		$enterpriseQuery = "SELECT * FROM Enterprise WHERE ELoginName = '".$inputAccount."' AND EPassword = '".$inputPassword."';";
		$enterpriseQueryResultset = $link->query($enterpriseQuery);
		if ($enterpriseQueryResultset->num_rows) {
			$enterpriseQueryResult = $enterpriseQueryResultset->fetch_assoc();
			$_SESSION["EID"] = $enterpriseQueryResult["EID"];
			$_SESSION["ELoginName"] = $enterpriseQueryResult["ELoginName"];
			$_SESSION["EPassword"] = $enterpriseQueryResult["EPassword"];
			$_SESSION["EName"] = $enterpriseQueryResult["EName"];
			$_SESSION["ELegalPerson"] = $enterpriseQueryResult["ELegalPerson"];
			$_SESSION["ELegalPersonID"] = $enterpriseQueryResult["ELegalPersonID"];
			$enterpriseQueryResultset->close();
			echo "success";
			break;
		}
		else {
			echo "账号密码错误！";
			break;
		}
	break;

	case "enterpriseLogout":  // 企业退出
		unset($_SESSION['EID']);
		unset($_SESSION['ELoginName']); 
		unset($_SESSION['EPassword']);
		unset($_SESSION['EName']);
		unset($_SESSION['ELegalPerson']);
		unset($_SESSION['ELegalPersonID']);
		header("Location:../index.php");
	break;

	case "addCustomerService":  // 添加客服
		$CSID       = $_POST['CSID'];
		$EID        = $_SESSION['EID'];
		$PID        = $_POST["PID"];
		$CSName     = $_POST["CSName"];
		$CSPassword = $_POST["CSPassword"];
		$sql = "insert into `customer-service` values( '{$CSID}','{$EID}','{$PID}','{$CSName}','{$CSPassword}')  ";
		$link->query($sql);
		header("Location:../enterprise/cs_manage.php");
	break;

	case "modifyCustomerService":  // 修改客服
		$CSID          = $_POST["CSID"];
		$EID           = $_SESSION['EID'];
		$PID           = $_POST["PID"];
		$CSName        = $_POST["CSName"];
		$CSPassword    = $_POST["CSPassword"];
		$sql = "update `customer-service` set CSEID='{$EID}',CSPID='{$PID}',CSName='{$CSName}',CSPassword='{$CSPassword}' where CSID={$CSID}";
		$link->query($sql);
		header("Location:../enterprise/cs_manage.php");
	break;

	case "deleteCustomerService":  // 删除客服
		$CSID=$_GET['CSID'];
		$sql = "delete from `customer-service` where CSID={$CSID}";
		$link->query($sql);
		header("Location:../enterprise/cs_manage.php");
	break;

	case "CSLogin":   // 客服登录
		$inputCaptcha = testInput($_POST["inputCaptcha"]);
		$inputCaptcha = strtolower($inputCaptcha);
		if (empty($inputCaptcha))
		{
			echo "验证码不能为空！";
			break;
		}
		if (!preg_match("/^.{6,6}$/",$inputCaptcha) or $inputCaptcha != $_SESSION['captcha'])
		{
			echo "验证码错误！";
			break;
		}
		$inputAccount = testInput($_POST["inputAccount"]);
		$inputPassword = testInput($_POST["inputPassword"]);
		if (empty($inputAccount))
		{
			echo "账户不能为空！";
			break;
		}
		if (empty($inputPassword))
		{
			echo "密码不能为空！";
			break;
		}
		if (!preg_match('/[A-Za-z0-9_\-\x{4e00}-\x{9fa5}]{5,50}/u',$inputAccount))
		{
			echo "账户应为5到50位的字母、数字、中文、符号“_”、“-”组合！";
			break;
		}
		if (!preg_match("/^[A-Za-z0-9_\-]{5,100}$/",$inputPassword))
		{
			echo "密码应为5到100位的字母、数字、符号“_”、“-”组合！";
			break;
		}
		$customerServiceQuery = "SELECT * FROM `customer-service` WHERE CSName = '".$inputAccount."' AND CSPassword = '".$inputPassword."';";
		$customerServiceQueryResultset = $link->query($customerServiceQuery);
		if ($customerServiceQueryResultset->num_rows) {
			$customerServiceQueryResult = $customerServiceQueryResultset->fetch_assoc();
			$_SESSION["CSID"] = $customerServiceQueryResult["CSID"];
			$_SESSION["CSEID"] = $customerServiceQueryResult["CSEID"];
			$_SESSION["CSPID"] = $customerServiceQueryResult["CSPID"];
			$_SESSION["CSName"] = $customerServiceQueryResult["CSName"];
			$_SESSION["CSPassword"] = $customerServiceQueryResult["CSPassword"];
			$customerServiceQueryResultset->close();
			echo "success";
			break;
		}
		else {
			echo "账号密码错误！";
			break;
		}
	break;

	case "CSLogout":  // 客服退出
		unset($_SESSION['CSID']);
		unset($_SESSION['CSEID']); 
		unset($_SESSION['CSPID']);
		unset($_SESSION['CSName']);
		unset($_SESSION['CSPassword']);
		header("Location:../index.php");
	break;

	case "addProduct":  // 添加产品
		$EID            = $_SESSION['EID'];
		$PName          = $_POST["PName"];
		$PIntroduction  = $_POST["PIntroduction"];
		$PImage = $link->escape_string(file_get_contents($_FILES['PImage']['tmp_name'])) ; 
		$sql  = "insert into product values( null,'{$EID}','{$PName}','{$PIntroduction}','{$PImage}')";
		$link->query($sql);
		header("Location:../enterprise/product_manage.php");
	break;

	case "modifyProduct":  // 修改产品
		$PID           = $_POST["PID"];
		$PName         = $_POST["PName"];
		$PIntroduction = $_POST["PIntroduction"];
		$PImage = $link->escape_string(file_get_contents($_FILES['PImage']['tmp_name'])) ; 
		$sql = "update product set PIntroduction='{$PIntroduction}',PName='{$PName}',PImage='{$PImage}' where PID={$PID}";
		$link->query($sql);
		header("Location:../enterprise/product_manage.php");
	break;

	case "deleteProduct":  // 删除产品
		$PID=$_GET['PID'];
		$sql = "delete from product where PID={$PID} ";
		$link->query($sql);
		header("Location:../enterprise/product_manage.php");
	break;

	case "showProductImage":  // 展示产品图片
		$sql="select * from product where PID={$_GET['PID']}";
		$result = $link->query($sql);
		$row=$result->fetch_assoc();
		echo $row['PImage'];
	break;

	case "addQuestions":  // 添加问题
		$PID           = $_POST["PID"];
		$QTitle        = $_POST["QTitle"];
		$QAnswer       = $_POST["QAnswer"];
		$QVisitTime    = $_POST["QVisitTime"];
		$QUsefulTime   = $_POST["QUsefulTime"];
		$QUselessTime  = $_POST["QUselessTime"];
		$QUnanswerable = $_POST["QUnanswerable"];	
		$sql = "insert into questions values( NULL,'{$PID}','{$QTitle}','{$QAnswer}','{$QVisitTime}','{$QUsefulTime}','{$QUselessTime}','{$QUnanswerable}')  ";
		$link->query($sql);
		header("Location:../enterprise/question_manage.php");
	break;
	
	case "modifyQuestions":  // 修改问题
		$QID           = $_POST["QID"];
		$PID           = $_POST["PID"];
		$QTitle        = $_POST["QTitle"];
		$QAnswer       = $_POST["QAnswer"];
		$QVisitTime    = $_POST["QVisitTime"];
		$QUsefulTime   = $_POST["QUsefulTime"];
		$QUselessTime  = $_POST["QUselessTime"];
		$QUnanswerable = $_POST["QUnanswerable"];
		$sql = "update questions set QPID='{$PID}',QTitle='{$QTitle}',QAnswer='{$QAnswer}',QVisitTime='{$QVisitTime}',QUsefulTime='{$QUsefulTime}',QUselessTime='{$QUselessTime}',QUnanswerable='{$QUnanswerable}' where QID={$QID}";
		$link->query($sql);
		header("Location:../enterprise/question_manage.php");
	break;
	
	case "deleteQuestions":  // 删除问题
		$QID=$_GET['QID'];
		$sql = "delete from questions where QID={$QID}";
		$link->query($sql);
		header("Location:../enterprise/question_manage.php");
	break;

	case "addQuestionUsefulTime":  // 增加问题有帮助次数
		$QID = $_GET['QID'];
		$sql = "UPDATE questions SET QUsefulTime = QUsefulTime+1 WHERE QID='".$QID."';";
		if ($link->query($sql) === TRUE) {
			echo "success";
			break;
		}
		echo "error";
		break;
	break;

	case "addQuestionUselessTime":  // 增加问题无帮助次数
		$QID = $_GET['QID'];
		$sql = "UPDATE questions SET QUselessTime = QUselessTime+1 WHERE QID='".$QID."';";
		if ($link->query($sql) === TRUE) {
			echo "success";
			break;
		}
		echo "error";
		break;
	break;

	case "addKnowledge":  // 添加知识
		$KID           = $_POST["KID"];
		$PID           = $_POST["PID"];
		$KIndex        = $_POST["KIndex"];
		$KClass        = $_POST["KClass"];
		$KTitle        = $_POST["KTitle"];
		$KDescription  = $_POST["KDescription"];
		$KContent      = $_POST["KContent"];
		$KVisitTime    = $_POST["KVisitTime"];	
		$KUsefulTime   = $_POST["KUsefulTime"];
		$KUselessTime  = $_POST["KUselessTime"];
		$sql = "insert into knowledge values( '{$KID}','{$PID}','{$KIndex}','{$KClass}','{$KTitle}','{$KDescription}','{$KContent}','{$KVisitTime}','{$KUsefulTime}','{$KUselessTime}')  ";
		$link->query($sql);
		header("Location:../enterprise/knowledge_manage.php");
	break;
	
	case "modifyKnowledge":  // 修改知识
		$KID           = $_POST["KID"];
		$PID          = $_POST["PID"];
		$KIndex        = $_POST["KIndex"];
		$KClass        = $_POST["KClass"];
		$KTitle        = $_POST["KTitle"];
		$KDescription  = $_POST["KDescription"];
		$KContent      = $_POST["KContent"];
		$KVisitTime    = $_POST["KVisitTime"];	
		$KUsefulTime   = $_POST["KUsefulTime"];
		$KUselessTime  = $_POST["KUselessTime"];
		$sql = "update knowledge set KPID='{$PID}',KIndex='{$KIndex}',KClass='{$KClass}',KTitle='{$KTitle}',KDescription='{$KDescription}',KContent='{$KContent}',KVisitTime='{$KVisitTime}',KUsefulTime='{$KUsefulTime}',KUselessTime='{$KUselessTime}' where KID={$KID}";
		$link->query($sql);
		header("Location:../enterprise/knowledge_manage.php");
	break;
	
	case "deleteKnowledge":  // 删除知识
		$KID=$_GET['KID'];
		$sql = "delete from knowledge where KID={$KID}";
		$link->query($sql);
		header("Location:../enterprise/knowledge_manage.php");
	break;

	case "showKnowledge":  // 展示知识内容
		$KID = $_GET['KID'];
		$sql = "select * from knowledge where KID={$KID}";
		$result = $link->query($sql);
		if( $row=$result->fetch_assoc())
			$response = $row['KContent'];
		echo $response;
	break;

	case "addKnowledgeUsefulTime":  // 增加知识有帮助次数
		$KID = $_POST["KID"];
		$sql = "UPDATE knowledge SET KUsefulTime = KUsefulTime+1 WHERE KID='".$KID."';";
		if ($link->query($sql) === TRUE) {
			echo "success";
			break;
		}
		echo "error";
		break;
	break;

	case "addKnowledgeUselessTime":  // 增加知识无帮助次数
		$KID = $_POST["KID"];
		$sql = "UPDATE knowledge SET KUselessTime = KUselessTime+1 WHERE KID='".$KID."';";
		if ($link->query($sql) === TRUE) {
			echo "success";
			break;
		}
		echo "error";
		break;
	break;

	case 'setProductID':  // 用户选择产品，设置$_SESSION["PID"]
		if (!empty($_POST["PID"])) {
			$_SESSION["PID"] = $_POST["PID"];
			echo "success";
			break;
		}
		echo "error";
		break;
	break;

	case "showQuestion":  // 智能客服显示问题和答案
		$question_id = $_GET['question_id'];
		$case_id = $_GET['case_id'];
		$response = "";

		$visitQuestionQuery = "UPDATE Questions SET QVisitTime = QVisitTime+1 WHERE QID = '".$question_id."';";
		if ($link->query($visitQuestionQuery) === FALSE) {
			throw new \Exception("Visit Question Query failed.");
		}

		$existCaseQuestionQuery = "SELECT CQID FROM `case-question` WHERE CQCID = '".$case_id."' AND CQQID = '".$question_id."';";
		$existCaseQuestionQueryResultset = $link->query($existCaseQuestionQuery);
		if (!$existCaseQuestionQueryResultset->num_rows) {
			$caseQuestionQuery = "INSERT INTO `case-question` (CQCID, CQQID) VALUES ('".$case_id."', '".$question_id."');";
			if ($link->query($caseQuestionQuery) === FALSE) {
					throw new \Exception("Case Question Query failed.");
			}
		}

		$keywordVisitQuery = "UPDATE Word SET WVisitTime = WVisitTime + 1 WHERE WID IN (SELECT QWWID FROM `question-word` WHERE QWQID = '".$question_id."');";
		if ($link->query($keywordVisitQuery) === FALSE) {
			throw new \Exception("keyword Visit Query failed.");
		}

		$questionQuery = "SELECT QID, QTitle, QAnswer, QVisitTime, QUsefulTime, QUselessTime FROM Questions WHERE QID = '".$question_id."';";
		$questionQueryResultset = $link->query($questionQuery);
		if ($questionQueryResultset->num_rows) {
			$questionQueryResult = $questionQueryResultset->fetch_assoc();
			$response = "<p class='question-title'>".$questionQueryResult['QTitle']."</p>".
			"<p class='question-content'>&nbsp; &nbsp;".$questionQueryResult['QAnswer']."</p>".
			"<span class='buttons'>".
				"<button type='button' id='useful-btn' class='btn' onclick='useful({$question_id})'><i class='glyphicon glyphicon-thumbs-up'></i>有帮助</button>".
				"<button type='button' id='useless-btn' class='btn' onclick='useless({$question_id})'><i class='glyphicon glyphicon-thumbs-down'></i>无帮助</button>".
				"<button type='button' id='turnto-arti-btn' class='btn' onclick='window.location=\"chat_arti.php\"'><i class='glyphicon glyphicon-user'></i>人工客服</button>".
			"</span>";
			$questionQueryResultset->free();
		}
		echo $response;
	break;

	case "intelCaseEnd":
		if (!empty($_GET['CID'])) {
			$caseEndTimeQuery = "UPDATE cases SET CEndTime = '".date('Y-m-d H:i:s')."' WHERE CID = '".$_GET['CID']."';";
			$link->query($caseEndTimeQuery);
		}
	break;
}
$link->close();
?>