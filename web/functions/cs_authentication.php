<?php
if (!session_id()) session_start();
if (!isset($_SESSION["CSID"])) {
    echo "<script language=javascript>";
    echo "alert(\"您未登录！\");";
    echo "location=\"/cs_login.php\";";
    echo "</script>";
    exit;
}
?>