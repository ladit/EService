<?php
session_start();
if (!isset($_SESSION["EID"])) {
    echo "<script language=javascript>";
    echo "alert(\"您未登录！\");";
    echo "location=\"/enterprise_login.php\";";
    echo "</script>";
    exit;
}
?>