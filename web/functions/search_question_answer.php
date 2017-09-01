<?php
	require_once __DIR__ . '/vendor/coreseek-4.1-win32/api/sphinxapi.php';
	
	$product_id = $_GET["product_id"];
	$input = $_GET["input"];

  $cl = new SphinxClient();
	$cl->SetServer("localhost", 9312);
	$cl->SetConnectTimeout(3);
	$cl->SetArrayResult(true);
	$cl->SetMatchMode(SPH_MATCH_EXTENDED2);
	$cl->SetFilter("QPID", array($product_id));
	$cl->SetFilter("QUnanswerable", array(0));
	$cl->SetSortMode(SPH_SORT_RELEVANCE);
	$result = $cl->Query($input, "*");

	if ($result === false) {
		echo "Query failed: " . $cl->GetLastError() . ".\n";
	}
	else {
		if ($cl->GetLastWarning()) {
			echo "WARNING: " . $cl->GetLastWarning() . "\n\n";
		}
		
		echo "Query '$input' retrieved $result[total] of $result[total_found] matches in $result[time] sec.\n";
		echo "Query stats:\n";
		if (is_array($result["words"])) {
			foreach ($result["words"] as $word => $info) {
				echo "    '$word' found $info[hits] times in $info[docs] documents\n";
			}
		}
		echo "\n";
		if (is_array($result["matches"])) {
			var_dump($result["matches"]);
			// foreach ($result["matches"] as $question) {

			// }
		}
	}



/* 	if($result['total'] == 0) {
		$ids="";
	}
	else {
		$ids = join(",", array_keys($result['matches']));
	}
	
	$sql="select * from questions,product where QID in({$ids}) and PID=QPID and PID={$_SESSION['PID']}  order by field(QID,{$ids})";
	$link->query("set names utf8");
	$rst= $link->query($sql);

	$opts = array(
		'before_match' => '<b style="color:red">',
		'after_match'  => '</b>',
		'chunk_separator' => '...',
		'limit'    => 60,
		'around'   => 3,
	);
	
	if($rst && $rst->num_rows >0) {
		while($row=$rst->fetch_assoc()) {
			$rst2=$cl->buildExcerpts($row,"mysql",$input,$opts);
			echo "<a href='javascript:showPopularQuestion({$row['QID']})'>问题：{$rst2[2]}</a><br>";
			echo "答案：{$rst2[3]}<br>";
			echo "<hr>";
		}
	}
	else {
		echo "没有您要搜索的内容！";
		$sql = "insert into questions values( NULL,'{$_SESSION['PID']}','{$keyword}',null,'0','0','0','1')  ";
		$link->query($sql);
	} */
?>