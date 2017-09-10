<?php
	ini_set('memory_limit', '1024M');

	use Fukuball\Jieba\Jieba;
	use Fukuball\Jieba\Finalseg;
	use Fukuball\Jieba\JiebaAnalyse;
	
	require_once __DIR__ . '/../../vendor/autoload.php';
	require_once __DIR__ . '/../../vendor/coreseek-4.1-win32/api/sphinxapi.php';
	require_once __DIR__ . '/connect_database.php';

	function testInput($data) {
		$data = preg_replace("/\s(?=\s)/","\\1", $data);
		$data = str_replace(array("\r\n", "\r", "\n"), "", $data);
		$data = strip_tags($data);
		$data = trim($data);
    return $data;
	}
	
	$product_id = $_GET["product_id"];
	$case_id = $_GET["case_id"];
	$input = $_GET["input"];

  $cl = new SphinxClient();
	$cl->SetServer("localhost", 9312);
	$cl->SetConnectTimeout(3);
	$cl->SetArrayResult(true);
	$cl->SetMatchMode(SPH_MATCH_EXTENDED2);
	$cl->SetFilter("QPID", array($product_id));
	$cl->SetFilter("QUnanswerable", array(0));
	$cl->SetSortMode(SPH_SORT_RELEVANCE);
	$result = $cl->Query(testInput($input), "*");

	if ($result === false) {
		throw new \Exception("Sphinx Query failed: " . $cl->GetLastError());
	}
	if ($cl->GetLastWarning()) {
		throw new \Exception("Sphinx WARNING: " . $cl->GetLastWarning());
	}

	// if (is_array($result["words"])) {
	// 	$keywords = array();
	// 	foreach ($result["words"] as $word => $word_detail) {
	// 		array_push($keywords, $word);
	// 	}
	// }

	if (is_array($result["matches"])) {
		$matched_ids = array();
		foreach ($result["matches"] as $match => $match_detail) {
			array_push($matched_ids, $match_detail["id"]);
		}
		$matched_ids = join(",", $matched_ids);
		$opts = array(
			'before_match' => '<b style="color:red">',
			'after_match'  => '</b>',
			'chunk_separator' => '...',
			'limit'    => 60,
			'around'   => 3,
		);
		$matchedQuestionsQuery = "SELECT QID, QTitle, QAnswer, QVisitTime, QUsefulTime, QUselessTime FROM Questions WHERE QID in(".$matched_ids.");";
		$matchedQuestionsQueryResultset = $link->query($matchedQuestionsQuery);
		if ($matchedQuestionsQueryResultset->num_rows) {
			while ($matchedQuestionsQueryResult = $matchedQuestionsQueryResultset->fetch_assoc()) {
				$excerpts = $cl->buildExcerpts($matchedQuestionsQueryResult, "mysql", $input, $opts);
				echo "<a href='javascript:showQuestion(".$excerpts[0].")'>".$excerpts[1]."</a><br>";
				echo "    ".$excerpts[2]."<br>";
				echo "<hr>";
			}
			$matchedQuestionsQueryResultset->free();
		}
	}
	else {
		echo "没有您要搜索的内容！";

		// 无法回答问题插入问题表
		$questionQuery = "INSERT INTO questions (QPID, QTitle, QVisitTime, QUsefulTime, QUselessTime, QUnanswerable) VALUES ('".$product_id."', '".$input."', '1', '0', '0', '1');";
		if ($link->query($questionQuery) === FALSE) {
			throw new \Exception("New Unanswerable Question Query failed.");
		}
		$questionIDQuery = "SELECT LAST_INSERT_ID() AS QID;";
		$questionIDQueryResultset = $link->query($questionIDQuery);
		if ($questionIDQueryResultset->num_rows) {
				$questionIDQueryResult = $questionIDQueryResultset->fetch_assoc();
				$question_id = $questionIDQueryResult['QID'];
				$questionIDQueryResultset->free();
		}
		else {
			throw new \Exception("Last Unanswerable Question ID Query failed.");
		}

		// 无法回答问题插入case-question
		$caseQuestionQuery = "INSERT INTO `case-question` (CQCID, CQQID) VALUES ('".$case_id."', '".$question_id."');";
		if ($link->query($caseQuestionQuery) === FALSE) {
				throw new \Exception("Case Question Query failed.");
		}

		// 关键词记录

		// 输入关键词提取
		Jieba::init(array('dict'=>'small'));
		Finalseg::init();
		JiebaAnalyse::init();
		$top_k = 6;
		$keywords = JiebaAnalyse::extractTags(testInput($input), $top_k);

		// 已有关键词表
		$keywordTableQuery = "SELECT WID, WContent FROM word;";
		$keywordTableQueryResultset = $link->query($keywordTableQuery);
		if ($keywordTableQueryResultset->num_rows) {
				$keywordTable = array();
				while ($keywordTableQueryResult = $keywordTableQueryResultset->fetch_assoc()) {
					$keywordTable[$keywordTableQueryResult["WID"]] = $keywordTableQueryResult["WContent"];
				}
				$keywordTableQueryResultset->free();
		}

		foreach ($keywords as $keyword => $value) {
			if (!empty($keywordTable)) {  // 已有关键词表非空
				foreach ($keywordTable as $word_id => $word) {
					if ($keyword == $word) { // 输入的关键词在已有关键词表里
						$keywordVisitQuery = "UPDATE Word SET WVisitTime = WVisitTime + 1 WHERE WID = '".$word_id."';";
						if ($link->query($keywordVisitQuery) === FALSE) {
							throw new \Exception("keyword Visit Query failed.");
						}
					}
					else {  // 输入的关键词不在已有关键词表里
						$newKeywordQuery = "INSERT INTO Word (WContent, WVisitTime) VALUES ('".$keyword."', '1');";
						if ($link->query($newKeywordQuery) === FALSE) {
							throw new \Exception("new Keyword Query failed.");
						}
						$newKeywordIDQuery = "SELECT LAST_INSERT_ID() AS WID;";
						$newKeywordIDQueryResultset = $link->query($newKeywordIDQuery);
						if ($newKeywordIDQueryResultset->num_rows) {
								$newKeywordIDQueryResult = $newKeywordIDQueryResultset->fetch_assoc();
								$word_id = $newKeywordIDQueryResult['WID'];
								$newKeywordIDQueryResultset->free();
						}
						else {
							throw new \Exception("Last New Keyword ID Query failed.");
						}
					}
					// 问题关键词绑定
					$questionWordQuery = "INSERT INTO `question-word` (QWQID, QWWID) VALUES ('".$question_id."', '".$word_id."');";
					if ($link->query($questionWordQuery) === FALSE) {
						throw new \Exception("Question Word Query failed.");
					}
				}
			}
			else {
				$newKeywordQuery = "INSERT INTO Word (WContent, WVisitTime) VALUES ('".$keyword."', '1');";
				if ($link->query($newKeywordQuery) === FALSE) {
					throw new \Exception("new Keyword Query failed.");
				}
				$newKeywordIDQuery = "SELECT LAST_INSERT_ID() AS WID;";
				$newKeywordIDQueryResultset = $link->query($newKeywordIDQuery);
				if ($newKeywordIDQueryResultset->num_rows) {
						$newKeywordIDQueryResult = $newKeywordIDQueryResultset->fetch_assoc();
						$word_id = $newKeywordIDQueryResult['WID'];
						$newKeywordIDQueryResultset->free();
				}
				else {
					throw new \Exception("Last New Keyword ID Query failed.");
				}
				// 问题关键词绑定
				$questionWordQuery = "INSERT INTO `question-word` (QWQID, QWWID) VALUES ('".$question_id."', '".$word_id."');";
				if ($link->query($questionWordQuery) === FALSE) {
					throw new \Exception("Question Word Query failed.");
				}
			}
		}
	}
?>