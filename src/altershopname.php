<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
	
 require_once 'SqlHelper.class.php';

$sqlhelper=new SqlHelper();
$sql1="select admin_shopnumber from administrator";
    $res1=$sqlhelper->execute_dql($sql1);
    if($row=mysql_fetch_assoc($res1))
        $oldshopnumber=$row['admin_shopnumber'];
if(!empty($_POST[1]))
{
 for($i=1;$i<=$oldshopnumber;$i++)
 {
 $newname=$_POST[$i];

 $sql2="update shop set shop_name='$newname' where shop_id=$i";
  $sqlhelper->execute_dml($sql2);
 }
 echo "修改成功</br>";
}
 if(!empty($_POST['shopname']))
{
	$oldshopnumber=$oldshopnumber+1;
	$fieldname="goods_".$oldshopnumber."_shop";
	$shopname=$_POST['shopname'];
	$sql6="update administrator set admin_shopnumber=$oldshopnumber";
	
	$sql4="alter table goods add $fieldname int(128) null";
	$sql5="insert into shop(shop_name) values('$shopname')";
	if((0!=$sqlhelper->execute_dml($sql4))&&(0!=$sqlhelper->execute_dml($sql5))&&0!=$sqlhelper->execute_dml($sql6))
		echo "添加店成功！</br>";
} 
echo "<a href='createshop.php'>返回分店管理</a></br><a href='admin.php'>返回主目录</a>";
 ?>