<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
$goodname=$_POST["adjustgoodnametype"];
$goodnumber=$_POST["adjustgoodnumber"];

$employ=$_SESSION['name'];
$shop=$_SESSION['shop'];
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
//

//
$sql1="select adjust_id from adjustgoods where adjust_employname='$employ' and adjust_goodname='$goodname' and adjust_state=0";
$res1=$sqlhelper->execute_dql($sql1);
if(mysql_fetch_array($res1))
{
    echo "此商品还有调货处于审核中，请联系管理员批准";
exit;
}
$sql="insert into adjustgoods(adjust_shopid,adjust_employname,adjust_goodname,adjust_goodnumber,adjust_time)
      values('$shop','$employ','$goodname',$goodnumber,now())";
if(1==$sqlhelper->execute_dml($sql))
	echo "<a href='employ.php'>申请成功，等待审核!</a>";
?>